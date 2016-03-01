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
class Strattas_Master_Model_LicenceCheck extends Strattas_Master_Model_Feed {

    public $_licenceKey = 'NOKEY';
    public $_domain = 'NODOMAIN';
    public $_customerEmail = 'NOEMAIL';
    public static $_licenceCheckResponse = 'NORESPONSE';

    public function isExtensionOn($extensionName) {
        if ($extensionName == '') {
            return true;
        }

        
        $this->_licenceKey = $extensionName;
        $this->_domain = preg_replace("/www\./i", "", $_SERVER['HTTP_HOST']);
        $this->_domain = preg_replace("/http:\/\//i", "", $this->_domain);
        $this->_domain = trim($this->_domain);

        $coll = Mage::getModel('core/config_data')->getCollection();
        $coll->getSelect()->where("path='strattasmaster/st_account_info/st_account_email'");
        if(count($coll)) {
            $this->_customerEmail = $coll->getFirstItem()->getValue();
        }


        return $this->doLicenceCheck();
    }

    protected function doLicenceCheck() {

        if ($this->_licenceKey == 'Strattas_Licencemanager') {
            return;
        }

        $connection = $this->_getZendCurl(array(
            'email' => $this->_customerEmail,
            'domain' => $this->_domain,
            'lk' => strtolower($this->_licenceKey)
                ));


        $licenceCheck = Mage::getSingleton('core/session')->getLicenceCheck();
        $headersArray = array();

        if (!isset($licenceCheck[$this->_licenceKey])) {
            $response = $connection->read();
            $responseArray = preg_split('/^\r?$/m', $response, 2);
            $responseLK = trim($responseArray[1]);
            $licenceCheck[$this->_licenceKey] = $responseLK;

            $responseHeadersArray = preg_split("/\r\n/", $response);
                foreach($responseHeadersArray as $responseHeaderLine) {
                    $parts = preg_split("/:/",$responseHeaderLine,2);
                    if(count($parts) > 1) {
                        $headersArray[$parts[0]] = trim($parts[1]);
                    }
                }

            if($headersArray['optionsArray'] != '') {
                $optionsArray = json_decode(base64_decode($headersArray['optionsArray']),true);
                $licenceCheck['optionsArray'] = $optionsArray;
            }

            Mage::getSingleton('core/session')->setLicenceCheck($licenceCheck);
        }

        if ($licenceCheck[$this->_licenceKey] != md5($this->_customerEmail . $this->_domain)) {

            $link = Strattas_Master_Helper_Config::RENEW_LICENCE;
            $linkHtml = "<a href='{$link}'>click here</a>";

            switch ($licenceCheck[$this->_licenceKey]) {
                case 'Error: Customer Not Found';
                    $errorMessage = 'Customer not found please verify your email and password below.';
                    break;
                case 'Error: No Domain Specified':
                    $errorMessage = "Domain is incorrect {$linkHtml} to make sure you have the proper domain configured.";
                    break;
                case 'Invalid Domain':
                    $errorMessage = "Please {$linkHtml} to update your Domain configuration.";
                    break;
                default:
                    $errorMessage = "Subscription Expired {$linkHtml} to update payment details.";
                    break;
            }

            self::$_licenceCheckResponse = "" . $this->_licenceKey . ": ";
            self::$_licenceCheckResponse .= $errorMessage;

            $this->_doSetExtensionOutput(1);
            return false;
        } else {
            $this->_doSetExtensionOutput(0);
            return true;
        }
    }

    public static function showErrorMessage() {
        return self::$_licenceCheckResponse;
    }

    protected function _getZendCurl($params) {

        $url = array();
        foreach ($params as $k => $v) {
            $url[] = urlencode($k) . "=" . ($v);
        }
        
        $url = rtrim(Strattas_Master_Helper_Config::LICENCE_CHECK_URL) . (sizeof($url) ? ("?" . implode("&", $url)) : "");
        
        

        $curl = new Varien_Http_Adapter_Curl();
        $curl->setConfig(array(
            'timeout' => 10
        ));
        $curl->write(Zend_Http_Client::GET, $url, '1.0');

        return $curl;
    }

    public function _doSetExtensionOutput($action) {

        
        if ($this->_licenceKey == 'Strattas_Licencemanager') {
            return;
        }

        Mage::app()->getConfig()
            ->saveConfig("strattasmaster/modules_disable_output/{$this->_licenceKey}", $action)
            ->reinit();

        /*
        $coll = Mage::getModel('core/config_data')->getCollection();
        //$coll->getSelect()->where("path='advanced/modules_disable_output/{$this->_licenceKey}'");
        $coll->getSelect()->where("path='strattasmaster/modules_disable_output/{$this->_licenceKey}'");
        $i = 0;

        //$action = 0;
        foreach ($coll as $cd) {
            $i++;
            $cd->setValue($action)->save();
        }
        if ($i == 0) {
            
            Mage::getModel('core/config_data')
                    //->setPath("advanced/modules_disable_output/{$this->_licenceKey}")
                    ->setPath("strattasmaster/modules_disable_output/{$this->_licenceKey}")
                    ->setValue($action)
                    ->save();
        }
        */
    }

}
