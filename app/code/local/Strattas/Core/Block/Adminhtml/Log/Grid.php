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

class Strattas_Core_Block_Adminhtml_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('stcoreLogGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('stcore/logger')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('date', array(
                                      'header' => Mage::helper('stcore')->__('Date'),
                                      'align' => 'right',
                                      'width' => '5',
                                      'index' => 'date',
                                      'type' => 'datetime'
                                 ));
        $this->addColumn('id', array(
                                    'header' => Mage::helper('stcore')->__('ID'),
                                    'align' => 'right',
                                    'width' => '5',
                                    'index' => 'id',
                               ));

        $this->addColumn('module', array(
                                        'header' => Mage::helper('stcore')->__('Module'),
                                        'align' => 'left',
                                        'index' => 'module',
                                   ));

        $this->addColumn('type', array(
                                      'header' => Mage::helper('stcore')->__('Title'),
                                      'align' => 'left',
                                      'index' => 'title',

                                 ));

        $this->addColumn('content', array(
                                         'header' => Mage::helper('stcore')->__('Details'),
                                         'align' => 'left',
                                         'index' => 'content',

                                    ));

        $this->addColumn('object', array(
                                        'header' => Mage::helper('stcore')->__('Object'),
                                        'align' => 'left',
                                        'index' => 'object',
                                   ));

        //$this->addExportType('*/*/exportCsv', Mage::helper('helpdesk')->__('CSV'));
        //$this->addExportType('*/*/exportXml', Mage::helper('helpdesk')->__('XML'));

        $ret = parent::_prepareColumns();


        return $ret;
    }

    public function getRowUrl($row)
    {
        return false;
    }

}
