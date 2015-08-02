<?php
class VC_FreeGift_Model_Quote_Discount extends Mage_Sales_Model_Quote_Address_Total_Abstract
{
 	public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        $quote = $address->getQuote();
        $eventArgs = array(
            'website_id'=>Mage::app()->getStore($quote->getStoreId())->getWebsiteId(),
            'customer_group_id'=>$quote->getCustomerGroupId(),
            'coupon_code'=>$quote->getCouponCode(),
        );
		
		$rules = Mage::getResourceModel('salesrule/rule_collection')
			->setValidationFilter($eventArgs['website_id'], $eventArgs['customer_group_id'], $eventArgs['coupon_code'])
			->load();

		foreach ($quote->getAllItems() as $item) {
			foreach ($rules as $rule) {
			    if (!$rule->getActions()->validate($item)) {
					continue;
				}
				switch ($rule->getSimpleAction()) {
					case VC_FreeGift_Model_SalesRule_Rule::PRODUCT_AUTO_ADD:
					break;
					case VC_FreeGift_Model_SalesRule_Rule::CART_AUTO_ADD:
					
					break;
					case VC_FreeGift_Model_SalesRule_Rule::PRODUCT_SAME_AUTO_ADD:
					break;
				}	
			}
		}
        return $this;
    }

    public function fetch(Mage_Sales_Model_Quote_Address $address)
    {	/*
        $amount = $address->getDiscountAmount();
        if ($amount!=0) {
            $title = Mage::helper('sales')->__('Discount');
            $code = $address->getCouponCode();
            if (strlen($code)) {
                $title = Mage::helper('sales')->__('Discount (%s)', $code);
            }
            $address->addTotal(array(
                'code'=>$this->getCode(),
                'title'=>$title,
                'value'=>-$amount
            ));
        }
		*/
        return $this;
    }
}
