<?php


class Df_OnPay_Helper_Data extends Mage_Core_Helper_Data {




	/**
	 * @param array $params
	 * @return string
	 */
	public function generateSignature (array $params) {

		/** @var string $result  */
		$result =
			md5 (
				implode (
					self::SIGNATURE_PARTS_SEPARATOR
					,
					$params
				)
			)
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @param Df_Core_Model_Money $price
	 * @return string
	 */
	public function priceToString (Df_Core_Model_Money $price) {

		/** @var string $result  */
		$result =
				$price->getOriginal() === floatval ($price->getIntegerPart())
			?
				number_format (
					round (
						$price->getOriginal ()
						,
						1
					)
					,
					1
					,
					Df_Core_Const::T_PERIOD
					,
					Df_Core_Const::T_EMPTY
				)
			:
				$price->getAsString()
		;


		df_result_string ($result);

		return $result;

	}


	const SIGNATURE_PARTS_SEPARATOR = ';';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_OnPay_Helper_Data';
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


