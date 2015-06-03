<?php

/*
 * Endpoint for Github Webhook URLs
 *
 * see: https://help.github.com/articles/post-receive-hooks
 *
 */

// script errors will be send to this email:
$error_mail = "developer@luzdemia.com";

function v4CIDRtoMask($cidr) {
    $cidr = explode('/', $cidr);
    return array($cidr[0], long2ip(-1 << (32 - (int)$cidr[1])));
}
function ipv4Breakout ($ip_address, $ip_nmask) {
    $hosts = array();
    //convert ip addresses to long form
    $ip_address_long = ip2long($ip_address);
    $ip_nmask_long = ip2long($ip_nmask);
    //caculate network address
    $ip_net = $ip_address_long & $ip_nmask_long;
    //caculate first usable address
    $ip_host_first = ((~$ip_nmask_long) & $ip_address_long);
    $ip_first = ($ip_address_long ^ $ip_host_first) + 1;
    //caculate last usable address
    $ip_broadcast_invert = ~$ip_nmask_long;
    $ip_last = ($ip_address_long | $ip_broadcast_invert) - 1;
    //caculate broadcast address
    $ip_broadcast = $ip_address_long | $ip_broadcast_invert;
    // foreach (range($ip_first, $ip_last) as $ip) {
    //         array_push($hosts, $ip);
    // }
    $block_info = array("network" => "$ip_net", 
    	"first_host" => "$ip_first", 
    	"last_host" => "$ip_last", 
    	"broadcast" => "$ip_broadcast");
    return $block_info;
}

function magento_mail($to,$content,$subject,$cc) {
   // Send mail, the Magento way:
   require_once('app/Mage.php');
   Mage::app();
   
   // Create a simple contact form mail:
   $emailTemplate = Mage::getModel('core/email_template')
       ->loadDefault('contacts_email_email_template');
   $data = new Varien_Object();
   $data->setData(    
       array(
           'name' => 'Developer',
           'email' => 'developer@luzdemia.com',
           'content' => $content
       )
   );
   $vars = array('data' => $data);
 
   // Set sender information:
   $storeId = Mage::app()->getStore()->getId();
   $emailTemplate->setSenderEmail(
       Mage::getStoreConfig('trans_email/ident_general/email', $storeId));
   $emailTemplate->setSenderName(
       Mage::getStoreConfig('trans_email/ident_general/name', $storeId));
   $emailTemplate->setTemplateSubject($subject);
   $emailTemplate->addBcc($cc);
 
   // Send the mail:
   $output = $emailTemplate->send($to, null, $vars);
   var_dump($output);
}

function run() {
    global $rawInput;

    // read config.json
    $config_filename = 'config.json';
    if (!file_exists($config_filename)) {
        throw new Exception("Can't find ".$config_filename);
    }
    $config = json_decode(file_get_contents($config_filename), true);

    $postBody = $_POST['payload'];
    $payload = json_decode($postBody);
    echo "function runs\n";
    // check if the request comes from github server
 	$github_ips = v4CIDRtoMask('192.30.252.0/22');
 	$ips_data = ipv4Breakout($github_ips[0], $github_ips[1]);
 	$first_ip = sprintf("%u", $ips_data['first_host']);
 	$last_ip = sprintf("%u", $ips_data['last_host']);
 	$webhook_ip = sprintf("%u", ip2long($_SERVER['REMOTE_ADDR']));
 	if ($last_ip > $webhook_ip && $webhook_ip > $first_ip) {
           echo "in array ip check\n";
           var_dump($config);
        foreach ($config['endpoints'] as $endpoint) {
           var_dump($endpoint);
           echo 'https://github.com/' . $endpoint['repo'] . "\n";
           echo $payload->repository->url."\n";
           echo 'refs/heads/' . $endpoint['branch']."\n";
           echo $payload->ref. "\n";
            // check if the push came from the right repository and branch
            if ($payload->repository->url == 'https://github.com/' . $endpoint['repo']
                && $payload->ref == 'refs/heads/' . $endpoint['branch']) {
    echo "repo and branch check\n";
                // execute update script, and record its output
		echo 'sh '.$endpoint['run']."\n";
                $output = exec('git pull');
    echo $output;
                // prepare and send the notification email
                if (isset($config['email'])) {
                       echo "sending email\n";
                    // send mail to someone, and the github user who pushed the commit
                    $body = '<p>The Github user <a href="https://github.com/'
                    . $payload->pusher->name .'">@' . $payload->pusher->name . '</a>'
                    . ' has pushed to ' . $payload->repository->url
                    . ' and consequently, ' . $endpoint['action']
                    . '.</p>';

                    $body .= '<p>Here\'s a brief list of what has been changed:</p>';
                    $body .= '<ul>';
                    foreach ($payload->commits as $commit) {
                        $body .= '<li>'.$commit->message.'<br />';
                        $body .= '<small style="color:#999">added: <b>'.count($commit->added)
                            .'</b> &nbsp; modified: <b>'.count($commit->modified)
                            .'</b> &nbsp; removed: <b>'.count($commit->removed)
                            .'</b> &nbsp; <a href="' . $commit->url
                            . '">read more</a></small></li>';
                    }
                    $body .= '</ul>';
                    $body .= '<p>What follows is the output of the script:</p><pre>';
                    $body .= $output. '</pre>';
                    $body .= '<p>Cheers, <br/>Github Webhook Endpoint</p>';
                    
                    $mailfunction = magento_mail($config['email']['to'],$body,$endpoint['action'],$payload->pusher->email);
                    
                    var_dump($mailfunction);
                }
                return true;
            }
        }
    } else {
        echo "exception\n";
        throw new Exception("This does not appear to be a valid requests from Github.\n");
    }
}

try {
    if ($_SERVER['HTTP_X_GITHUB_EVENT'] != 'push') {
        echo "Works fine.\n";
    } else {
       echo "Running\n";
        run();
    }
} catch ( Exception $e ) {
    $msg = $e->getMessage();
    magento_mail($error_mail,$msg,''.$e);
    //mail($error_mail, $msg, ''.$e);
}
