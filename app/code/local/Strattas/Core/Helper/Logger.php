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

class Strattas_Core_Helper_Logger extends Mage_Core_Helper_Abstract
{

    const PARENT_HELPER = 'Mage_Core_Helper_Abstract';
    const PARENT_MODEL = 'Mage_Core_Model_Abstract';
    const RESOURCE_MODEL = 'Mage_Core_Model_Mysql4_Abstract';
    const RESOURCE_COLLECTION = 'Mage_Core_Model_Mysql4_Collection_Abstract';
    const BLOCK_TEMPLATE = 'Mage_Core_Block_Template';
    /** Config path to use logger or not */
    const XML_PATH_ENABLE_LOG = 'strattasmaster/stcore/logger_enabled';

    /**
     * Property containing logger object
     * @static
     * @var Strattas_Core_Model_Logger
     */
    protected static $_logger;

    /**
     * Returns loggersingleton. Inits logger instance if not initialized.
     * @return Strattas_Core_Model_Logger
     */
    protected function _getLogger()
    {
        if (self::$_logger instanceof Strattas_Core_Model_Logger) {
        } else {
            self::$_logger = Mage::getSingleton('stcore/logger');
        }
        return self::$_logger;
    }

    /**
     * Writes message to log
     * @param object $Object
     * @param string $message
     * @param object $severity [optional]
     * @return Strattas_Core_Helper_Logger
     */
    public function log($Object, $message, $severity = null, $description = null, $line = null)
    {

        if (!Mage::getStoreConfig(self::XML_PATH_ENABLE_LOG)) {
            return $this;
        }
        $class_name = get_class($Object);
        $this->_getLogger()->setData(array());
        if (preg_match("/Strattas_([a-z]+)+/i", $class_name, $matches)) {
            $this->_getLogger()->setModule(@$matches[1]);
        } else {
            $this->_getLogger()->setModule('');
        }
        $this->_getLogger()
                ->setObject($class_name)
                ->setTitle($message)
                ->setLine($line)
                ->setSeverity($severity)
                ->setContent($description)
                ->save();
        return $this;
    }

    /**
     * Writes message to log. Message is marked as invisible(e.g. it is service message)
     * @param object $Object
     * @param object $message
     * @param object $severity [optional]
     * @return Strattas_Core_Helper_Logger
     */
    public function logInvisible($Object, $message, $severity = null)
    {
        $class_name = get_class($Object);
        if (preg_match("/Strattas_([a-z]+)+/i", $class_name, $matches)) {
            $this->_getLogger()->setModule(@$matches[1]);
        } else {
            $this->_getLogger()->setModule('');
        }
        $this->_getLogger()
                ->setTitle($message)
                ->setObject($class_name)
                ->setVisibility(0)
                ->setSeverity($severity)
                ->save();
        return $this;
    }

    /**
     * Deletes all old log records
     * @return
     */
    public function exorcise()
    {
        $Date = new Zend_Date();
        Zend_Date::setOptions(array('extend_month' => true));
        $Date->addDayOfYear((0 - (int)Mage::getStoreConfig('strattasmaster/stcore/logger_store_days')));

        foreach (Mage::getModel('stcore/logger')->getCollection()->addOlderThanFilter($Date) as $entry) {
            $entry->delete();
        }
        return $this;
    }

}