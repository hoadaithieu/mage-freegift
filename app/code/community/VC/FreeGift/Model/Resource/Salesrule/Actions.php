<?php
class VC_FreeGift_Model_Resource_Salesrule_Actions extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('vc_freegift/salesrule_actions', 'id');
    }
	
	public function validIdentifier(Mage_Core_Model_Abstract $object, $value = '', $id = 0) {

        $read = $this->_getReadAdapter();
        if ($read && strlen($value) > 0) {
            
			
			$field  = $this->_getReadAdapter()->quoteIdentifier(sprintf('%s.%s', $this->getMainTable(), 'identifier'));
			$select = $this->_getReadAdapter()->select()
				->from($this->getMainTable())
				->where($field . '=?', $value);
			
			if ($id > 0) {
				$select->where($this->getIdFieldName().' !=?', $id);
			}
            $data = $read->fetchRow($select);

            if ($data) {
                return false;
            }
        }
		return true;
	}
	
}
