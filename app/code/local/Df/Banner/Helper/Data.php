<?php

class Df_Banner_Helper_Data extends Mage_Core_Helper_Abstract {



	/**
	 * @return Df_Banner_Helper_Image
	 */
	public function image () {

		/** @var Df_Banner_Helper_Image $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Banner_Helper_Image $result  */
			$result = Mage::helper (Df_Banner_Helper_Image::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Banner_Helper_Image);

		}

		return $result;

	}




	/**
	 * @return Df_Banner_Helper_Image2
	 */
	public function image2 () {

		/** @var Df_Banner_Helper_Image2 $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Banner_Helper_Image2 $result  */
			$result = Mage::helper (Df_Banner_Helper_Image2::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Banner_Helper_Image2);

		}

		return $result;

	}







	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Banner_Helper_Data';
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