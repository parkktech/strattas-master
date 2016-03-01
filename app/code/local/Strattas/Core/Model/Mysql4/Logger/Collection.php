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
class Strattas_Core_Model_Mysql4_Logger_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('stcore/logger');
    }

    /**
     * Add filter to find records older than specified date
     * @param Zend_Date $Date
     * @return Strattas_Core_Model_Mysql4_Logger_Collection
     */
    public function addOlderThanFilter(Zend_Date $Date)
    {
        $this->getSelect()->where('date<?', $Date->toString(Strattas_Core_Model_Abstract::DB_DATETIME_FORMAT));
        return $this;
    }
}