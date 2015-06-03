<?php

/**
 * Controller to handle logic for merge vars in config
 *
 * @author Ebizmarts Team <info@ebizmarts.com>
 */
class Ebizmarts_SweetMonkey_Adminhtml_MageMonkeyController extends Mage_Adminhtml_Controller_Action
{

    /**
     * Get merge vars for given list
     */
    public function varsForListAction()
    {
        $listId = $this->getRequest()->getParam('list_id', null);

        $mergeVars = array();

        if ($listId) {
            $api = Mage::getModel('monkey/api');

            $mergeVars = $api->listMergeVars($listId);
        }

        $this->getResponse()->setBody(Zend_Json::encode($mergeVars));
        return;
    }

    /**
     * Add merge vars to list
     */
    public function varsToListAction()
    {

        $listId = $this->getRequest()->getPost('list_id', null);

        if ($listId) {

            $api = Mage::getModel('monkey/api');
            parse_str($this->getRequest()->getPost('merge_vars'), $mergeVars);

            $options = array();
            foreach ($mergeVars as $tag => $name) {

                //Date for some fields, format is "MM/DD/YYYY"
                if ($tag == 'PTSSPENT' || $tag == 'PTSEARN' || $tag == 'PTSEXP') {
                    $options ['field_type'] = 'date';
                } else {

                    //We add a new mergevar for INT points.
                    if ($tag == 'PTS') {
                        $options = array('field_type' => 'number');
                        $api->listMergeVarAdd($listId, "POINTS", "Points", $options);

                        $options = array();
                    }
                }

                $api->listMergeVarAdd($listId, $tag, $name, $options);

                $options = array();
            }
        }

        $this->getResponse()->setBody(Zend_Json::encode(array()));
        return;
    }

}