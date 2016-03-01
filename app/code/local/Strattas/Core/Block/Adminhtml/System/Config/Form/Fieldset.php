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


class Strattas_Core_Block_Adminhtml_System_Config_Form_Fieldset extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{

    /**
     * Render fieldset html
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = $this->_getHeaderHtml($element);

        foreach ($element->getElements() as $field) {
            $html .= $field->toHtml();
        }
        $html .= "<tr>
            <td class=\"label\"></td>
            <td class=\"value\">
            <button class=\"scalable\" onclick=\"window.location='" . Mage::getSingleton('adminhtml/url')->getUrl('strattascore_admin/viewlog/index') . "'\" type=\"button\">
                <span>View log</span>
            </button
            </td>
         </tr>
         ";
        $html .= $this->_getFooterHtml($element);

        return $html;
    }
}