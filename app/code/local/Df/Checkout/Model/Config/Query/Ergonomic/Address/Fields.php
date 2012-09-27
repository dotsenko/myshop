<?php

class Df_Checkout_Model_Config_Query_Ergonomic_Address_Fields extends Df_Core_Model_Abstract {

	

	/**
	 * @return Mage_Core_Model_Config_Element
	 */
	public function getNode () {
	
		if (!isset ($this->_node)) {


			/** @var Mage_Core_Model_Config_Element $result  */
			$result =
				/**
				 * Обязательно клонируем объект,
				 * потому что Magento кэширует настроечные узлы
				 */
				clone
					df()->config()->getNodeByKey (
						$this->getPathByAddressType (
							'default'
						)
					)
			;

			$result
				->extend (
					df()->config()->getNodeByKey (
						$this->getPathByAddressType (
							$this->getAddressType ()
						)
					)
					,
					true
				)

			;
	
	
			df_assert ($result instanceof Mage_Core_Model_Config_Element);
	
			$this->_node = $result;
	
		}
	
	
		df_assert ($this->_node instanceof Mage_Core_Model_Config_Element);
	
		return $this->_node;
	
	}
	
	
	/**
	* @var Mage_Core_Model_Config_Element
	*/
	private $_node;	






	/**
	 * @param string $addressType
	 * @return string
	 */
	private function getPathByAddressType ($addressType) {

		/** @var string $result  */
		$result =
			df()->config()->implodeKey (
				array (
					'df/checkout/address'
					,
					$addressType
					,
					'fields'
				)
			)
		;

		df_result_string ($result);

		return $result;

	}






	/**
	 * @return string
	 */
	private function getAddressType () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__ADDRESS_TYPE);

		df_result_string ($result);

		return $result;

	}






	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__ADDRESS_TYPE, new Df_Zf_Validate_String()
			)
		;
	}



	const PARAM__ADDRESS_TYPE = 'address_type';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Model_Config_Query_Ergonomic_Address_Fields';
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


