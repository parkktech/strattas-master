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

class Strattas_Core_Block_Adminhtml_Log extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_controller = 'adminhtml_log';
        $this->_blockGroup = 'stcore';
        $this->_headerText = Mage::helper('stcore')->__('Strattas Extensions Log');

        parent::__construct();

        $this->setTemplate('widget/grid/container.phtml');
        $this->_removeButton('add');
        $this->_addButton('clear', array(
                                        'label' => Mage::helper('stcore')->__('Clear Log'),
                                        'onclick' => 'if(confirm(\'' . Mage::helper('stcore')->__('Are you sure to clear all log entries?') . '\'))setLocation(\'' . $this->getClearUrl() . '\')',
                                        'class' => 'delete',
                                   ));
    }

    /**
     * Return url for log cleanup
     * @return string
     */
    public function getClearUrl()
    {
        return Mage::getSingleton('adminhtml/url')->getUrl('strattascore_admin/viewlog/clear');
    }

}