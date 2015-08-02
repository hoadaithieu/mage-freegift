<?php
class VC_FreeGift_Model_Observer
{
	public function saveActions($observer) {
		if (!Mage::getStoreConfig('vc_freegift/general/enable')) return;
        //$quote = $observer->getEvent()->getQuote();
		$object = $observer->getEvent()->getDataObject();
		$session = Mage::getSingleton('adminhtml/session');
		$data = $session->getPageData();
		if ((isset($data['rule_id']) && $data['rule_id'] > 0) ||  $object->getId() > 0) {
			if (!(isset($data['rule_id']) && $data['rule_id'] > 0)) {
				$data['rule_id'] = $object->getId();
			}
			$model = Mage::getModel('vc_freegift/salesrule_actions')->load($data['rule_id'],'rule_id');
			if (!$model && isset($data['promo_items']) && strlen(trim($data['promo_items'])) > 0) {
				$model = Mage::getModel('vc_freegift/salesrule_actions');
			} 
			
			$data['promo_items'] = ','.trim(str_replace(' ','', $data['promo_items'])).',';
			
			if ($model) {
				$model->setRuleId($data['rule_id'])
				->setPromoItems($data['promo_items'])
				->setPromoQty($data['promo_qty']);
				$model->save();
			}
		}
	}
	
	public function deleteActions($observer) {
		if (!Mage::getStoreConfig('vc_freegift/general/enable')) return;
		$data = $observer->getEvent()->getDataObject();
		$model = Mage::getModel('vc_freegift/salesrule_actions')->load($data->getRuleId(), 'rule_id');
		if ($model && $model->getId() > 0) {
			$model->delete();
		}
	}
	
	public function loadAfter($observer) {
		if (!Mage::getStoreConfig('vc_freegift/general/enable')) return;	
		$model = $observer->getEvent()->getDataObject();
		if ($model && $model->getRuleId() > 0) {
			$item = Mage::getModel('vc_freegift/salesrule_actions')->load($model->getRuleId(), 'rule_id');
			if ($item) {
				$model->setPromoItems(trim($item->getPromoItems(),','))
				->setPromoQty($item->getPromoQty());
			}
		}
	}
	
	protected function _updateItem($rule, $item, $quote, $code) {
		if (!Mage::getStoreConfig('vc_freegift/general/enable')) return;	
		$collection = Mage::getModel('sales/quote_item')->getCollection()
		//->addFieldToFilter('applied_rule_ids', $rule->getRuleId())
		->addFieldToFilter('additional_data', $item->getItemId().'-'.$rule->getRuleId().'-'.$code)
		->setQuote($quote);
		
		$it = $collection->getFirstItem();
		if ($it && $it->getItemId() > 0) return;
		
		
		$model = Mage::getModel('vc_freegift/salesrule_actions')->load($rule->getRuleId(),'rule_id');
		if ($model && $model->getId() > 0 && strlen(trim($model->getPromoItems())) > 0) {
			$skuAr = explode(',', str_replace(' ', '', $model->getPromoItems()));
			$collection = Mage::getModel('catalog/product')->getCollection()
			->addFieldToFilter('sku', array('in' => $skuAr));
			/*
			$content = file_get_contents('test.log');
			$content .= '['.$item->getSku().';'.implode('-',$skuAr).';'.in_array($item->getSku(), $skuAr).';'.$item->getItemId().']';
			file_put_contents('test.log', $content);
			*/
			//if ($collection && $collection->getSize() > 0 && !in_array($item->getSku(), $skuAr)) {
			if ($collection && $collection->getSize() > 0 && !Mage::helper('vc_freegift')->isFreeGift($item)) {
				foreach ($collection as $product) {
					
					$product->load($product->getId());
					//$product->setName('FREE - '.$product->getName());
					$quoteItem = Mage::getModel('sales/quote_item');
					$quoteItem->setProduct($product)->setQty($model->getPromoQty())
					//->setStoreId($quote->getStoreId())
					//->setAppliedRuleIds($rule->getRuleId())
					->setAdditionalData($item->getItemId().'-'.$rule->getRuleId().'-'.$code)
					;
					$quote->addItem($quoteItem);
				}
			}
		}	
	}
	
	/*
	SalesRule_Model_Validator
	'rule'    => $rule,
	'item'    => $item,
	'address' => $address,
	'quote'   => $quote,
	'qty'     => $qty,
	'result'  => $result,
	
	*/
	public function validatorProcess($observer) {
		if (!Mage::getStoreConfig('vc_freegift/general/enable')) return;	
		$rule = $observer->getEvent()->getRule();
		$item = $observer->getEvent()->getItem();
		$quote = $observer->getEvent()->getQuote();
		
		switch ($rule->getSimpleAction()) {
			case VC_FreeGift_Model_SalesRule_Rule::PRODUCT_AUTO_ADD://return;
				if ($item->getParentItemId() > 0 || !$item->getItemId()) return;
				$this->_updateItem($rule, $item, $quote, VC_FreeGift_Model_SalesRule_Rule::PRODUCT_AUTO_ADD);
				
				
			break;
			case VC_FreeGift_Model_SalesRule_Rule::CART_AUTO_ADD:
				if ($item->getParentItemId() > 0 || !$item->getItemId()) return;
				$this->_updateItem($rule, $item, $quote, VC_FreeGift_Model_SalesRule_Rule::CART_AUTO_ADD);
				
							
			break;
			case VC_FreeGift_Model_SalesRule_Rule::PRODUCT_SAME_AUTO_ADD:
				if ($item->getParentItemId() > 0 || !$item->getItemId() || strpos($item->getAdditionalData(), VC_FreeGift_Model_SalesRule_Rule::PRODUCT_SAME_AUTO_ADD) > 0) break;
				
				$collection = Mage::getModel('sales/quote_item')->getCollection()
				//->addFieldToFilter('applied_rule_ids', $rule->getRuleId())
				->addFieldToFilter('additional_data', $item->getItemId().'-'.$rule->getRuleId().'-'.VC_FreeGift_Model_SalesRule_Rule::PRODUCT_SAME_AUTO_ADD)
				->setQuote($quote);
				
				$it = $collection->getFirstItem();
				if ($it && $it->getItemId() > 0) break;
				//file_put_contents('test.log', VC_FreeGift_Model_SalesRule_Rule::PRODUCT_SAME_AUTO_ADD.$item->getSku());
				
				/*
				$option = Mage::getModel('sales/quote_item_option')->load($item->getItemId(),'item_id');
				$params = unserialize($option->getValue());
				$params['uenc'] = '';
				$params['form_key'] = '';
				//print_r($params); die();
				$product = Mage::getModel('catalog/product')->load($option->getProductId());
				$nItem = $quote->addProduct($product, new Varien_Object($params));
				*/
				
				$cPrice = 0.00;
				$cItem = clone $item;
				//$cItem = $nItem;
				$cItem ->setPrice($cPrice)
					->setBasePrice($cPrice)
					->setCustomPrice($cPrice)
					->setTaxPercent($cPrice)
					->setTaxAmount($cPrice)
					->setBaseTaxAmount($cPrice)
					->setRowTax($cPrice)
					->setBaseRowTax($cPrice)
					->setRowTotal($cPrice)
					->setBaseRowTotal($cPrice)
					->setPriceInclTax($cPrice)
					->setBasePriceInclTax($cPrice)
					->setRowTotalInclTax($cPrice)
					->setBaseRowTotalInclTax($cPrice)
					->setAdditionalData($item->getItemId().'-'.$rule->getRuleId().'-'.VC_FreeGift_Model_SalesRule_Rule::PRODUCT_SAME_AUTO_ADD)
					//->save()
				;
				
				
				$pItem = $quote->addItem($cItem);
				
				
			break;
		}
		
		//$session->setProcessQuoteItemIdAr($quoteItemIdAr);
	}
	
	public function removeItem($observer) {
		if (!Mage::getStoreConfig('vc_freegift/general/enable')) return;	
		$item = $observer->getEvent()->getQuoteItem();
		
		$collection = Mage::getModel('sales/quote_item')->getCollection()
		->addFieldToFilter('additional_data', array('like' => $item->getId().'-%'))
		->setQuote($item->getQuote());
		if ($collection && $collection->getSize() > 0) {
			foreach ($collection as $qItem) {
				$qItem->delete();
			}
		}
	}
}
