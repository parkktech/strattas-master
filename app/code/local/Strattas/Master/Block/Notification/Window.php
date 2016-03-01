<?php
/**
 * Strattas
 * LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.strattasecomm.com/LICENSE.txt
 *
 * @category   Strattas
 * @package    Strattas_Master
 * @version    1.0
 * @copyright  Copyright (c) 2012 Strattas (http://www.strattasecomm.com)
 * @license    http://www.strattasecomm.com/LICENSE.txt
 */

class Strattas_Master_Block_Notification_Window extends Mage_Adminhtml_Block_Notification_Window
{
    protected function _construct()
    {
        parent::_construct();

        if (!Mage::getStoreConfig('strattasmaster/install/run')) {
            $c = Mage::getModel('core/config_data');
            $c
                    ->setScope('default')
                    ->setPath('strattasmaster/install/run')
                    ->setValue(time())
                    ->save();
            $this->setHeaderText($this->__("Strattas Notifications Setup"));
            $this->setIsFirstRun(1);
            $this->setIsHtml(1);

        }
    }

    protected function _toHtml()
    {
        if ($this->getIsHtml()) {
            $this->setTemplate('strattas_master/notification/window.phtml');
        }
        return parent::_toHtml();
    }

    public function presetFirstSetup()
    {

    }

    public function getNoticeMessageText()
    {
        if ($this->getIsFirstRun()) {
            $child = $this->getLayout()->createBlock('core/template')->setTemplate('strattas_master/notification/window/first-run.phtml')->toHtml();
            return $child;
        } else {
            return $this->getData('notice_message_text');
        }
    }


}
