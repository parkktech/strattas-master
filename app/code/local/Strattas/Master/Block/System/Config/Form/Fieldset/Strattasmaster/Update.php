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


class Strattas_Master_Block_System_Config_Form_Fieldset_Strattasmaster_Update extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $url = $this->getUrl('strattasmaster/adminhtml_update/runUpdate',array('supplier_index'=>$this->supplier_index));
        return <<<HDOC
 <div>Update Extension: </div><input type="button" value="Update" onclick="javascript:window.open('{$url}','_blank');">
HDOC;
    }
}
