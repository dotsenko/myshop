<?php


class Df_Dataflow_Helper_Settings_Products extends Df_Core_Helper_Settings {


	/**
	 * @return boolean
	 */
	public function getEnhancedCategorySupport () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_dataflow/products/enhanced_category_support'
			)
		;

		df_result_boolean ($result);

		return $result;

	}


	/**
	 * @return boolean
	 */
	public function getGallerySupport () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_dataflow/products/gallery_support'
			)
		;

		df_result_boolean ($result);

		return $result;

	}


	/**
	 * @return boolean
	 */
	public function getCustomOptionsSupport () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_dataflow/products/custom_options_support'
			)
		;

		df_result_boolean ($result);

		return $result;

	}



	/**
	 * @return boolean
	 */
	public function getDeletePreviousImages () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_dataflow/products/delete_previous_images'
			)
		;

		df_result_boolean ($result);

		return $result;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Helper_Settings_Products';
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