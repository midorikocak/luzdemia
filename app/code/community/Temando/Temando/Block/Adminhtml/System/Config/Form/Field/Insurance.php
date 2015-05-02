<?php

class Temando_Temando_Block_Adminhtml_System_Config_Form_Field_Insurance extends Temando_Temando_Block_Adminhtml_System_Config_Form_Field_Required
{

    protected $_confirm_text = array(
        "optional" => "<table><tr><td class='c1'>%optional_input%</td><td class='c2'>I confirm that I have read &amp; understood the terms &amp; conditions outlined in the <a href='http://www.temando.com/downloads/Product%20Disclosure%20Statement%20and%20Wording.pdf' target='_blank'>Product Disclosure Statement and wording</a>, the <a href='http://www.temando.com/downloads/Financial%20Services%20Guide.pdf' target='_blank'>Financial Services Guide</a> and the <a href='http://www.temando.com/downloads/Privacy%20Statement.pdf' target='_blank'>Privacy Statement</a> and will take advantage of this offer.</td></tr></table>",
        "mandatory" => "<textarea style='height: 100px; width: 100%'>Athough every care is taken by the transport company whilst transporting and storing the item being transacted, it may still become lost or damaged.

In some instances, the transport company may be required to compensate you for the loss of or damage to the item. However, there are many circumstances in which they may not be required to compensate you, such as when the loss or damage is caused by an act of God, or other events beyond their control. This insurance assists to fill this gap.

Whilst every effort has been made to insure your item, you should understand that the following items are not insured (this is a summary of the exclusions and you should refer to the Terms and Conditions for a complete understanding):

- Money, deeds, securities, treasury notes, tickets, vouchers, stamps, duty stamps, any other cash equivalents,

- Designs and/or patterns and/or plans and/or manuscripts and/or any other documents,

- Electronic data or computer software stored on computers or hardware

- Any living creature

- Precious Metals, Precious Stones or Semi-precious Stones that are sent by themselves, i.e. this does not mean fixed items, e.g. rings, earrings),

- Motor Vehicles and boats (note, this does not mean car parts, which can be covered).

Please also note that the insurance only covers items where the origin address and the delivery address are both located in Australia. In other words, if you are sending your item to a country that is not Australia, then there is no insurance. Conversely, if you are resident in a country other than Australia, but sending the item to Australia the insurance will not apply either.</textarea>
<table style='padding-top: 10px;'><tr><td class='c1'>%mandatory_input%</td><td class='c2'>I confirm that I have read &amp; understood the terms &amp; conditions outlined in the <a href='http://www.temando.com/downloads/Product%20Disclosure%20Statement%20and%20Wording.pdf' target='_blank'>Product Disclosure Statement and wording</a>, the <a href='http://www.temando.com/downloads/Financial%20Services%20Guide.pdf' target='_blank'>Financial Services Guide</a> and the <a href='http://www.temando.com/downloads/Privacy%20Statement.pdf' target='_blank'>Privacy Statement</a> and will take advantage of this offer.</td></tr></table>",
        "disabled" => "<div class='error-msg' style='height: 35px; border:  0 !important; background-color: transparent !important; padding-left: 33px; padding-top: 9px; color: #2F2F2F  !important;'>Even though Insurance will be unavailable to your buyers, you may still insure shipments as a seller.</div>
<textarea style='height: 100px; width: 100%'>Athough every care is taken by the transport company whilst transporting and storing the item being transacted, it may still become lost or damaged.

In some instances, the transport company may be required to compensate you for the loss of or damage to the item. However, there are many circumstances in which they may not be required to compensate you, such as when the loss or damage is caused by an act of God, or other events beyond their control. This insurance assists to fill this gap.

Whilst every effort has been made to insure your item, you should understand that the following items are not insured (this is a summary of the exclusions and you should refer to the Terms and Conditions for a complete understanding):

- Money, deeds, securities, treasury notes, tickets, vouchers, stamps, duty stamps, any other cash equivalents,

- Designs and/or patterns and/or plans and/or manuscripts and/or any other documents,

- Electronic data or computer software stored on computers or hardware

- Any living creature

- Precious Metals, Precious Stones or Semi-precious Stones that are sent by themselves, i.e. this does not mean fixed items, e.g. rings, earrings),

- Motor Vehicles and boats (note, this does not mean car parts, which can be covered).

Please also note that the insurance only covers items where the origin address and the delivery address are both located in Australia. In other words, if you are sending your item to a country that is not Australia, then there is no insurance. Conversely, if you are resident in a country other than Australia, but sending the item to Australia the insurance will not apply either.</textarea>
<table style='padding-top: 10px;'><tr><td class='c1'>%disabled_input%</td><td class='c2'>I confirm that I have read &amp; understood the terms &amp; conditions outlined in the <a href='http://www.temando.com/downloads/Product%20Disclosure%20Statement%20and%20Wording.pdf' target='_blank'>Product Disclosure Statement and wording</a>, the <a href='http://www.temando.com/downloads/Financial%20Services%20Guide.pdf' target='_blank'>Financial Services Guide</a> and the <a href='http://www.temando.com/downloads/Privacy%20Statement.pdf' target='_blank'>Privacy Statement</a> and will take advantage of this offer.</td></tr></table>",
    );


    /**
     * @see Mage_Adminhtml_Block_System_Config_Form_Field::render()
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $comment_confirm = $this->_getConfirmHtml($element->getValue());
        $element->setOnchange("$$('.confirm-insurance').each(function(control){control.style.display = 'none';}); $('insurance_'+this.value).style.display = '';");
        $element->setComment($element->getComment());
        $return = parent::render($element);
        $return .= "<tr><td>&nbsp;</td><td colspan='3'>" . $comment_confirm . "</td></tr>";
        return $return;
    }

    protected function _getConfirmHtml($value)
    {
        $return = '';
        foreach ($this->_confirm_text as $k => $v) {
            $checked = (Mage::getStoreConfig('temando/insurance/confirm_' . $k)=='Y')?true:false;
            if ($k != $value) {
                $checked = false;
            }

            $field  = "<input type='hidden' name='groups[insurance][fields][confirm_" . $k . "][value]' " . ($checked?'disabled="disabled""':'') . " value='N' id='optional_confirm_no' />";
            $field .= "<input type='checkbox' name='groups[insurance][fields][confirm_" . $k ."][value]' value='Y' " . ($checked?'checked="checked"':'') . ' onclick="if($(\'' . $k .'_confirm_no\')){$(\'' . $k .'_confirm_no\').disabled=this.checked;}" />';
            $html = "<div class='confirm-insurance' id='insurance_" . $k . "'" .  (($value != $k)?" style='display: none;'":"") . ">" .  $v . "</div>";
            $html = str_replace('%' . $k . '_input%', $field, $html);
            $return .= $html;
        }

        return $return;
    }

}
