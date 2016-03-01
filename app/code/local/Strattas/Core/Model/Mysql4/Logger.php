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

class Strattas_Core_Model_Mysql4_Logger extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('stcore/logger', 'id');
    }

    /**
     * Truncates all log records
     * @return Strattas_Core_Model_Mysql4_Logger
     */
    public function truncateAll()
    {
        $this->_getWriteAdapter()->delete($this->getMainTable(), '');
        return $this;
    }
}