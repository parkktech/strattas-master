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
 * @package    Strattas_Core
 * @version    1.0
 * @copyright  Copyright (c) 2012 Strattas (http://www.strattasecomm.com)
 * @license    http://www.strattasecomm.com/LICENSE.txt
 */

class Strattas_Core_ViewlogController extends Mage_Adminhtml_Controller_action
{
    public function indexAction()
    {
        $this
                ->loadLayout()
                ->_addContent($this->getLayout()->createBlock('stcore/adminhtml_log'))
                ->renderLayout();
    }

    /**
     * Clears all records in log table
     * @return Strattas_Core_ViewlogController
     */
    public function clearAction()
    {
        try {
            Mage::getResourceSingleton('stcore/logger')->truncateAll();
            Mage::getSingleton('adminhtml/session')->addSuccess("Log successfully cleared");
            $this->_redirect('*/*');

        } catch (Mage_Core_Exception $E) {
            Mage::getSingleton('adminhtml/session')->addError($E->getMessage());
            $this->_redirectReferer();
        }
        return $this;
    }

}