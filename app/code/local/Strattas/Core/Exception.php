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

class Strattas_Core_Exception extends Mage_Core_Exception
{
    /**
     * @deprecated
     * @static
     * @var mixed
     */
    protected static $_log;

    public function __construct($message, $details = '', $level = 1, $module = '')
    {
        Mage::helper('stcore/logger')->log($this, $message, Strattas_Core_Model_Logger::LOG_SEVERITY_WARNING, $details, $this->getLine());
        return parent::__construct($message);
    }
}