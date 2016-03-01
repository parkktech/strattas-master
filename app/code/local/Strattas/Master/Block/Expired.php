<?php

class Strattas_Master_Block_Expired extends Mage_Adminhtml_Block_System_Config_Edit {

    public function __construct() {
        parent::__construct();

        $sectionCode = $this->getRequest()->getParam('section');


        $sections = Mage::getSingleton('adminhtml/config')->getSections();

        $this->_section = $sections->$sectionCode;

        $this->setTitle((string) $this->_section->label);

        $licenceKey = Mage::helper('strattasmaster/data')->getLicenceKeyFromSection($sectionCode);
        
        $moduleEnabled = Mage::getStoreConfig("strattasmaster/modules_disable_output/{$licenceKey}");

//        if ($licenceKey != 'NOKEY' && ($moduleEnabled == 1 || !is_null($moduleEnabled)) ){
        if ($licenceKey != 'NOKEY' && ($moduleEnabled == 1 || (!is_null($moduleEnabled) && $moduleEnabled != 0)) ) {

            $this->setTemplate('strattas_master/expired.phtml');
        }
    }

}

