<?php


class Df_WalletOne_Helper_Data extends Mage_Core_Helper_Data {


	/**
	 * @param array $params
	 * @return array
	 */
	public function preprocessSignatureParams (array $params) {

		/** @var array $result  */
		$result = array ();

		foreach ($params as $key => $value) {

			/** @var string $key */
			/** @var mixed $value */

			$result []=
				implode (
					self::SIGNATURE_KEY_VALUE_SEPARATOR
					,
					array (
						$key
						,
						df_string ($value)
					)
				)
			;
		}


		df_result_array ($result);

		return $result;

	}


	const SIGNATURE_KEY_VALUE_SEPARATOR = '=';



	const SIGNATURE_PARTS_SEPARATOR = '&';


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_WalletOne_Helper_Data';
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


