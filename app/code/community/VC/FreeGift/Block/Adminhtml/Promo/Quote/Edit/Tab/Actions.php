<?php
/*
class Mage_Adminhtml_Block_Promo_Quote_Edit_Tab_Actions
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
*/
class VC_FreeGift_Block_Adminhtml_Promo_Quote_Edit_Tab_Actions
    extends Mage_Adminhtml_Block_Promo_Quote_Edit_Tab_Actions
{


    protected function _prepareForm()
    {
        $model = Mage::registry('current_promo_quote_rule');

        //$form = new Varien_Data_Form(array('id' => 'edit_form1', 'action' => $this->getData('action'), 'method' => 'post'));
        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('rule_');

        $fieldset = $form->addFieldset('action_fieldset', array('legend'=>Mage::helper('salesrule')->__('Update prices using the following information')));

        $fieldset->addField('simple_action', 'select', array(
            'label'     => Mage::helper('salesrule')->__('Apply'),
            'name'      => 'simple_action',
            'options'    => array(
                Mage_SalesRule_Model_Rule::BY_PERCENT_ACTION => Mage::helper('salesrule')->__('Percent of product price discount'),
                Mage_SalesRule_Model_Rule::BY_FIXED_ACTION => Mage::helper('salesrule')->__('Fixed amount discount'),
                Mage_SalesRule_Model_Rule::CART_FIXED_ACTION => Mage::helper('salesrule')->__('Fixed amount discount for whole cart'),
                Mage_SalesRule_Model_Rule::BUY_X_GET_Y_ACTION => Mage::helper('salesrule')->__('Buy X get Y free (discount amount is Y)'),
				
				VC_FreeGift_Model_Salesrule_Rule::PRODUCT_AUTO_ADD => Mage::helper('vc_freegift')->__('Auto add promo items with products'),
				VC_FreeGift_Model_Salesrule_Rule::CART_AUTO_ADD => Mage::helper('vc_freegift')->__('Auto add promo items for the whole cart'),
				VC_FreeGift_Model_Salesrule_Rule::PRODUCT_SAME_AUTO_ADD => Mage::helper('vc_freegift')->__('Auto add the same product'),
            ),
        ));
        $fieldset->addField('promo_items', 'text', array(
            'name' => 'promo_items',
            'required' => true,
            'class' => 'required-entry',
            'label' => Mage::helper('salesrule')->__('Promo Items'),
        ));
		
        $fieldset->addField('promo_qty', 'text', array(
            'name' => 'promo_qty',
            'required' => true,
            'class' => 'validate-not-negative-number',
            'label' => Mage::helper('salesrule')->__('Promo Qty'),
        ));
		
		
        $fieldset->addField('discount_amount', 'text', array(
            'name' => 'discount_amount',
            'required' => true,
            'class' => 'validate-not-negative-number',
            'label' => Mage::helper('salesrule')->__('Discount Amount'),
        ));
        $model->setDiscountAmount($model->getDiscountAmount()*1);

        $fieldset->addField('discount_qty', 'text', array(
            'name' => 'discount_qty',
            'label' => Mage::helper('salesrule')->__('Maximum Qty Discount is Applied To'),
        ));
        $model->setDiscountQty($model->getDiscountQty()*1);

        $fieldset->addField('discount_step', 'text', array(
            'name' => 'discount_step',
            'label' => Mage::helper('salesrule')->__('Discount Qty Step (Buy X)'),
        ));

        $fieldset->addField('apply_to_shipping', 'select', array(
            'label'     => Mage::helper('salesrule')->__('Apply to Shipping Amount'),
            'title'     => Mage::helper('salesrule')->__('Apply to Shipping Amount'),
            'name'      => 'apply_to_shipping',
            'values'    => Mage::getSingleton('adminhtml/system_config_source_yesno')->toOptionArray(),
        ));

        $fieldset->addField('simple_free_shipping', 'select', array(
            'label'     => Mage::helper('salesrule')->__('Free Shipping'),
            'title'     => Mage::helper('salesrule')->__('Free Shipping'),
            'name'      => 'simple_free_shipping',
            'options'    => array(
                0 => Mage::helper('salesrule')->__('No'),
                Mage_SalesRule_Model_Rule::FREE_SHIPPING_ITEM => Mage::helper('salesrule')->__('For matching items only'),
                Mage_SalesRule_Model_Rule::FREE_SHIPPING_ADDRESS => Mage::helper('salesrule')->__('For shipment with matching items'),
            ),
        ));

        $fieldset->addField('stop_rules_processing', 'select', array(
            'label'     => Mage::helper('salesrule')->__('Stop Further Rules Processing'),
            'title'     => Mage::helper('salesrule')->__('Stop Further Rules Processing'),
            'name'      => 'stop_rules_processing',
            'options'    => array(
                '1' => Mage::helper('salesrule')->__('Yes'),
                '0' => Mage::helper('salesrule')->__('No'),
            ),
        ));

        $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
            ->setTemplate('promo/fieldset.phtml')
            ->setNewChildUrl($this->getUrl('*/promo_quote/newActionHtml/form/rule_actions_fieldset'));

        $fieldset = $form->addFieldset('actions_fieldset', array(
            'legend'=>Mage::helper('salesrule')->__('Apply the rule only to cart items matching the following conditions (leave blank for all items)')
        ))->setRenderer($renderer);

        $fieldset->addField('actions', 'text', array(
            'name' => 'actions',
            'label' => Mage::helper('salesrule')->__('Apply To'),
            'title' => Mage::helper('salesrule')->__('Apply To'),
            'required' => true,
        ))->setRule($model)->setRenderer(Mage::getBlockSingleton('rule/actions'));

        Mage::dispatchEvent('adminhtml_block_salesrule_actions_prepareform', array('form' => $form));
		//$model->setPromoItems('test');
        $form->setValues($model->getData());

        if ($model->isReadonly()) {
            foreach ($fieldset->getElements() as $element) {
                $element->setReadonly(true, true);
            }
        }
        //$form->setUseContainer(true);

        $this->setForm($form);

        return $this;
    }

}
