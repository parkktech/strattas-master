<?php

class Strattas_Master_Model_System_Check extends Mage_Core_Model_Config_Data {

    protected function _afterSave() {
        $value = $this->getValue();
        Mage::getSingleton('core/session')->setLicenceCheck(array());
    }

}
?>
