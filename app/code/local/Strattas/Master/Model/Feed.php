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
class Strattas_Master_Model_Feed extends Mage_AdminNotification_Model_Feed {
    const XML_USE_HTTPS_PATH = 'strattasmaster/feed/use_https';
    const XML_FEED_URL_PATH = 'strattasmaster/feed/url';
    const XML_FREQUENCY_PATH = 'strattasmaster/feed/check_frequency';
    const XML_FREQUENCY_ENABLE = 'strattasmaster/feed/enabled';
    const XML_LAST_UPDATE_PATH = 'strattasmaster/feed/last_update';

    public static function check() {
        if (!Mage::getStoreConfig(self::XML_FREQUENCY_ENABLE)) {
            return;
        }
        return Mage::getModel('strattasmaster/feed')->checkUpdate();
    }

    public function getFrequency() {
        return Mage::getStoreConfig(self::XML_FREQUENCY_PATH) * 3600;
    }

    public function getLastUpdate() {
        //return 100;
        return Mage::app()->loadCache('strattasmaster_notifications_lastcheck');
    }

    public function setLastUpdate() {
        Mage::app()->saveCache(time(), 'strattasmaster_notifications_lastcheck');
        return $this;
    }

    public function getFeedUrl() {
        if (is_null($this->_feedUrl)) {
            $this->_feedUrl = (Mage::getStoreConfigFlag(self::XML_USE_HTTPS_PATH) ? 'https://' : 'http://')
                    . Mage::getStoreConfig(self::XML_FEED_URL_PATH);
        }
        return $this->_feedUrl;
    }

    public function checkUpdate() {
        if (($this->getFrequency() + $this->getLastUpdate()) > time()) {
            return $this;
        }

        $feedData = array();

        $feedXml = $this->getFeedData();

        if ($feedXml && $feedXml->channel && $feedXml->channel->item) {
            foreach ($feedXml->channel->item as $item) {
                $feedData[] = array(
                    'severity' => (int) $item->severity ? (int) $item->severity : 3,
                    'date_added' => $this->getDate((string) $item->pubDate),
                    'title' => (string) $item->title,
                    'description' => (string) $item->description,
                    'url' => (string) $item->link,
                );
            }
            if ($feedData) {
                Mage::getModel('adminnotification/inbox')->parse(array_reverse($feedData));
            }
        }
        $this->setLastUpdate();

        return $this;
    }

}