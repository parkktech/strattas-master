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
class Strattas_Core_Helper_Extension extends Varien_Object
{

    /**
     * Detect if extension installed
     * @param string $code
     * @return bool
     */
    public function isExtensionInstalled($code)
    {
        $exts = $this->getInstalledExtensions();
        return (isset($exts[$code]));
    }

    /**
     * Detect if extension is installed and active
     * @param string $code
     * @return bool
     */
    public function isExtensionActive($code)
    {
        if ($this->isExtensionInstalled($code)) {
            $exts = $this->getInstalledExtensions();
            return (bool)$exts[$code]['active'];
        }
    }

    /**
     * Return all installed extensions
     * This way is based on
     * @return array
     */
    public function getInstalledExtensions()
    {
        if (!$this->getData('installed_extensions')) {
            $exts = array();
            $modules = ((array)Mage::getConfig()->getNode('modules')->children());
            foreach ($modules as $k => $Module) {
                $exts[$k] = (array)$Module;
            }
            $this->setData('installed_extensions', $exts);
        }
        return $this->getData('installed_extensions');
    }
}