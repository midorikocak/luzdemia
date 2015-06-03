<?php

/*
 * Endpoint for Github Webhook URLs
 *
 * see: https://help.github.com/articles/post-receive-hooks
 *
 */

// script errors will be send to this email:
$error_mail = "developer@luzdemia.com";

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

    // check if the request comes from github server
    $github_ips = array('207.97.227.253', '50.57.128.197', '108.171.174.178', '50.57.231.61');
    if (in_array($_SERVER['REMOTE_ADDR'], $github_ips)) {
        foreach ($config['endpoints'] as $endpoint) {
            // check if the push came from the right repository and branch
            if ($payload->repository->url == 'https://github.com/' . $endpoint['repo']
                && $payload->ref == 'refs/heads/' . $endpoint['branch']) {

                // execute update script, and record its output
                ob_start();
                passthru('sh '.$endpoint['run']);
                $output = ob_end_contents();

                // prepare and send the notification email
                if (isset($config['email'])) {
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
                    
                    magento_mail($config['email']['to'],$body,$endpoint['action'],$payload->pusher->email);
                }
                return true;
            }
        }
    } else {
        throw new Exception("This does not appear to be a valid requests from Github.\n");
    }
}

try {
    if ($_SERVER['HTTP_X_GITHUB_EVENT'] != 'push') {
        echo "Works fine.";
    } else {
       echo "Running";
        run();
    }
} catch ( Exception $e ) {
    $msg = $e->getMessage();
    mail($error_mail, $msg, ''.$e);
}
