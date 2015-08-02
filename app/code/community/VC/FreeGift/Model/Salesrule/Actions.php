<?php
class VC_FreeGift_Model_Salesrule_Actions extends Mage_Core_Model_Abstract
{
    public function _construct() {
        parent::_construct();
        $this->_init('vc_freegift/salesrule_actions', 'id');
    }
	
	public function validIdentifier($str = '') {
		return $this->_getResource()->validIdentifier($this, $str, $this->getId());
	}
	
}
