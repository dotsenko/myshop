<?php


class Df_Customer_Model_Form extends Mage_Customer_Model_Form {





	/**
	 * @override
	 * @return Mage_Core_Model_Abstract|false
	 */
	protected function _getFormAttributeCollection () {

		/** @var Mage_Core_Model_Abstract|false $result  */
		$result = parent::_getFormAttributeCollection();

		if (
				df_helper()->customer()->check()->formAttributeCollection($result)
			&&
				!is_null ($this->getAddress ())
		) {

			/** @var Mage_Customer_Model_Resource_Form_Attribute_Collection|Mage_Customer_Model_Entity_Form_Attribute_Collection $result */

			$result
				->setFlag (
					Df_Customer_Const_Form_Attribute_Collection::PARAM__ADDRESS
					,
					$this->getAddress ()
				)
			;

		}

		return $result;

	}
	
	
	
	
	
	/**
	 * @return Mage_Customer_Model_Address_Abstract|null
	 */
	private function getAddress () {
	
		if (!isset ($this->_address) && !$this->_addressIsNull) {
	
			/** @var Mage_Customer_Model_Address_Abstract|null $result  */
			$result = null;

			if ($this->getEntity() instanceof Mage_Customer_Model_Address_Abstract) {
				$result = $this->getEntity();
			}
			else if (false !== strpos (Mage::app()->getRequest()->getRequestUri(), 'saveBilling')) {
				$result = df_mage()->checkout()->sessionSingleton()->getQuote()->getBillingAddress();
			}
			else if (false !== strpos (Mage::app()->getRequest()->getRequestUri(), 'saveShipping')) {
				df_mage()->checkout()->sessionSingleton()->getQuote()->getShippingAddress();
			}
	
			if (!is_null ($result)) {
				df_assert ($result instanceof Mage_Customer_Model_Address_Abstract);
			}
			else {
				$this->_addressIsNull = true;
			}
	
			$this->_address = $result;
	
		}
	
	
		if (!is_null ($this->_address)) {
			df_assert ($this->_address instanceof Mage_Customer_Model_Address_Abstract);
		}		
		
	
		return $this->_address;
	
	}
	
	
	/**
	* @var Mage_Customer_Model_Address_Abstract|null
	*/
	private $_address;	
	
	/**
	 * @var bool
	 */
	private $_addressIsNull = false;	
	
	



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Customer_Model_Form';
	}


	/**
	 * Например, для класса Df_SalesRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {

		/** @var string $result */
		static $result;

		if (!isset ($result)) {
			$result = df()->reflection()->getModelNameInMagentoFormat (self::getClass());
		}

		return $result;
	}


}


