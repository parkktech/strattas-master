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



class Strattas_Master_Helper_Config extends Mage_Core_Helper_Abstract

{

    /** Extensions feed path */

    const EXTENSIONS_FEED_URL = 'http://www.strattasecomm.com/productextension.xml';
	//const EXTENSIONS_FEED_URL = 'http://www.strattasecomm.com/extension.xml';

    /** Updates Feed path */

    const UPDATES_FEED_URL = 'http://www.strattasecomm.com/updates/updates.xml';

    /** Estore URL */

    const STORE_URL = 'http://www.strattasecomm.com/adminloading/';

    /** Licence Check URL */
    const LICENCE_CHECK_URL = 'http://www.strattasecomm.com/license_unique.php';
	
	/** Backlink Manager Data */
    const ANCHOR_WS_URL = 'http://www.strattasecomm.com/api/soap/?wsdl';
    const ANCHOR_WS_USER = 'adminws';
    const ANCHOR_WS_KEY = 'adminws';
	

    /** Renew Licence URL */
    const RENEW_LICENCE = 'http://www.strattasecomm.com/customer/account/login/';
    

    /** EStore response cache key*/

    const STORE_RESPONSE_CACHE_KEY = 'strattas_master_store_response_cache_key';





}

