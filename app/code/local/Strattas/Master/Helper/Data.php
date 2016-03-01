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
class Strattas_Master_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getLicenceKeyFromSection($sectionName='NOKEY') {


        switch ($sectionName) {
            case 'seo':
                $licenceKey = 'Strattas_Seo';
                break;
            case 'productping':
                $licenceKey = 'Strattas_Productping';
                break;
			case 'googleadwords':
                $licenceKey = 'Strattas_Googleadwords';
                break;	
			case 'managecatalogcustom':
                $licenceKey = 'Strattas_Managecatalogcustom';
                break;					
            default:
                $licenceKey = 'NOKEY';
        }
        return $licenceKey;
    }

    public function getAnchor() {
        $anchor = Mage::getSingleton('core/session')->getStrattasAnchor();
		if($anchor == ''){
			$client = new SoapClient(Strattas_Master_Helper_Config::ANCHOR_WS_URL);
			$session = $client->login(Strattas_Master_Helper_Config::ANCHOR_WS_USER, Strattas_Master_Helper_Config::ANCHOR_WS_KEY);
			$anchor = $client->call($session, 'sm_anchor.getAnchor');
			Mage::getSingleton('core/session')->setStrattasAnchor($anchor);
		}
		return json_decode($anchor,true);
    }

}

