<?php
class VC_FreeGift_Helper_Data extends Mage_Core_Helper_Abstract {
	public function showFreeGiftLabel($item) {
		$freeGift = '';
		if ((strpos($item->getAdditionalData(), VC_FreeGift_Model_SalesRule_Rule::PRODUCT_AUTO_ADD) > 0 ||
				strpos($item->getAdditionalData(), VC_FreeGift_Model_SalesRule_Rule::CART_AUTO_ADD) > 0 ||
				strpos($item->getAdditionalData(), VC_FreeGift_Model_SalesRule_Rule::PRODUCT_SAME_AUTO_ADD) > 0)) {
			$freeGift = '<span style="color:#ff0000">'.Mage::getStoreConfig('vc_freegift/general/label').'</span>';
		}
	
		return $freeGift;
	}	
	
	public function isFreeGift($item) {
		return (strpos($item->getAdditionalData(), VC_FreeGift_Model_SalesRule_Rule::PRODUCT_AUTO_ADD) > 0 ||
				strpos($item->getAdditionalData(), VC_FreeGift_Model_SalesRule_Rule::CART_AUTO_ADD) > 0 ||
				strpos($item->getAdditionalData(), VC_FreeGift_Model_SalesRule_Rule::PRODUCT_SAME_AUTO_ADD) > 0
			);	
	}
}