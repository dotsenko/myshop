<?php


class Df_Checkout_Model_Filter_Ergonomic_Address
	extends Df_Core_Model_Abstract
	implements Zend_Filter_Interface {


    /**
     * @param  array $value
     * @return array
     */
    public function filter ($value) {

		/** @var array $result  */
		$result = array ();

		foreach ($value as $id => $address) {

			/** @var int $id */
			/** @var Mage_Customer_Model_Address $address */

			df_assert ($address instanceof Mage_Customer_Model_Address);

			$address->setData ('address_type', $this->getAddressType());

			if (true === $address->validate()) {
				$result [$id] = $address;
			}

		}


		df_result_array ($result);

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
		return 'Df_Checkout_Model_Filter_Ergonomic_Address';
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

