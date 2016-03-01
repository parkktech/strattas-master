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
class Strattas_Master_Model_Feed_Extensions extends Strattas_Master_Model_Feed_Abstract {

    /**
     * Retrieve feed url
     *
     * @return string
     */
    public function getFeedUrl() {
        return Strattas_Master_Helper_Config::EXTENSIONS_FEED_URL;
    }

    public function prepareFeedField($productObj,$isObserv=null) {
        
		if(is_null($isObserv)){
			$product = Mage::getModel('catalog/product');
			$product->load($productObj->getId());
        }
		else{
			$product = $productObj;
		}
        /*if(!$product->getData('is_imported')){
            return;
        }*/
		if($product->getData('display_name') == '' || $product->getData('ext_version') == '' || $product->getData('strattas_lk') == ''){
			return false;
		}
        $xml = "<extension>" .
                '<display_name><![CDATA[' . $product->getData('display_name') . ']]></display_name>' .
                '<version><![CDATA[' . $product->getData('ext_version') . ']]></version>' .
                '<download_url><![CDATA[' . $product->getData('download_url') . ']]></download_url>' .
                '<name><![CDATA[' . $product->getData('strattas_lk') . ']]></name>' .
                '<url><![CDATA[' . Mage::getUrl('catalog/product/view', array('id' => $productObj->getId())) . ']]></url>' .
                "</extension>";
        return $xml;
    }

    public function doPrepare($observer) {

        $products = Mage::getModel("catalog/product")->getCollection();
        $extensionDetails = array();
        foreach ($products as $eachProduct) {
            $content = $this->prepareFeedField($eachProduct);
			if($content){
				$extensionDetails[$eachProduct->getId()] = $content;
			}
        }
        // update currently updating product
        $content = $this->prepareFeedField($observer->getProduct(),true);
		if($content){
			$extensionDetails[$observer->getProduct()->getId()] = $content;
		}else{
			unset($extensionDetails[$observer->getProduct()->getId()]);
		}

        $xmlPath = Mage::getBaseDir() . DS . 'productextension.xml';
        if (is_writable($xmlPath) && !empty($extensionDetails) )  {
            $extensionStr = implode("", $extensionDetails);
            @file_put_contents($xmlPath, "<?xml version=\"1.0\" encoding=\"UTF-8\"?><extensions>{$extensionStr}</extensions>");
        }

        $this->refresh();
    }

    /**
     * Checks feed
     * @return
     */
    public function check() {

        if (!(Mage::app()->loadCache('strattas_master_extensions_feed')) || (time() - Mage::app()->loadCache('strattas_master_extensions_feed_lastcheck')) > Mage::getStoreConfig('strattasmaster/feed/check_frequency')) {
            $this->refresh();
        }
        //TODO remove override KD
        //$this->refresh();

    }

    public function refresh() {
        $exts = array();
        try {
            $Node = $this->getFeedData();
            if (!$Node)
                return false;
            foreach ($Node->children() as $ext) {
				//var_dump($ext);
                $exts[(string) $ext->name] = array(
                    'display_name' => (string) $ext->display_name,
                    'version' => (string) $ext->version,
                    'download_url' => (string) $ext->download_url,
                    'name' => (string) $ext->name,
                    'url' => (string) $ext->url
                );
            }
            //print "<pre>";print_r($exts);print "</pre>";
            Mage::app()->saveCache(serialize($exts), 'strattas_master_extensions_feed');
            Mage::app()->saveCache(time(), 'strattas_master_extensions_feed_lastcheck');
            return true;
        } catch (Exception $E) {
            return false;
        }
    }

    public function checkExtensions() {
        $modules = array_keys((array) Mage::getConfig()->getNode('modules')->children());
        sort($modules);

        $magentoPlatform = Strattas_Master_Helper_Versions::getPlatform();
        foreach ($modules as $extensionName) {
            if (strstr($extensionName, 'Strattas_') === false) {
                continue;
            }
            if ($extensionName == 'Strattas_Core' || $extensionName == 'Strattas_Master' || $extensionName == 'Strattas_master') {
                continue;
            }
            if ($platformNode = $this->getExtensionPlatform($extensionName)) {
                $extensionPlatform = Strattas_Master_Helper_Versions::convertPlatform($platformNode);
                if ($extensionPlatform < $magentoPlatform) {
                    $this->disableExtensionOutput($extensionName);
                }
            }
        }
        return $this;
    }

    public function getExtensionPlatform($extensionName) {
        try {
            if ($platform = Mage::getConfig()->getNode("modules/$extensionName/platform")) {
                $platform = strtolower($platform);
                return $platform;
            } else {
                throw new Exception();
            }
        } catch (Exception $e) {
            return false;
        }
    }

    public function disableExtensionOutput($extensionName) {
        $coll = Mage::getModel('core/config_data')->getCollection();
        $coll->getSelect()->where("path='advanced/modules_disable_output/$extensionName'");
        $i = 0;
        foreach ($coll as $cd) {
            $i++;
            $cd->setValue(1)->save();
        }
        if ($i == 0) {
            Mage::getModel('core/config_data')
                    ->setPath("advanced/modules_disable_output/$extensionName")
                    ->setValue(1)
                    ->save();
        }
        return $this;
    }

}
