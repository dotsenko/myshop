<?php


class Df_Tweaks_Helper_Settings_Catalog_Product_View_Sku extends Df_Core_Helper_Settings {
	
	
	
	/**
	 * @return Df_Admin_Model_Config_Extractor_Font
	 */
	public function getLabelFont () {

		if (!isset ($this->_labelFont)) {

			/** @var Df_Admin_Model_Config_Extractor_Font $result  */
			$result =
				df_model (
					Df_Admin_Model_Config_Extractor_Font::getNameInMagentoFormat()
					,
					array (
						Df_Admin_Model_Config_Extractor_Font::PARAM__CONFIG_GROUP_PATH =>
							self::CONFIG_GROUP_PATH

						,
						Df_Admin_Model_Config_Extractor_Font::PARAM__CONFIG_KEY_PREFIX =>
							self::CONFIG_KEY_PREFIX__LABEL
					)
				)
			;


			df_assert ($result instanceof Df_Admin_Model_Config_Extractor_Font);

			$this->_labelFont = $result;

		}


		df_assert ($this->_labelFont instanceof Df_Admin_Model_Config_Extractor_Font);

		return $this->_labelFont;

	}


	/**
	* @var Df_Admin_Model_Config_Extractor_Font
	*/
	private $_labelFont;
	
	
	


	/**
	 * @return Df_Admin_Model_Config_Extractor_Font
	 */
	public function getSkuFont () {

		if (!isset ($this->_skuFont)) {

			/** @var Df_Admin_Model_Config_Extractor_Font $result  */
			$result =
				df_model (
					Df_Admin_Model_Config_Extractor_Font::getNameInMagentoFormat()
					,
					array (
						Df_Admin_Model_Config_Extractor_Font::PARAM__CONFIG_GROUP_PATH =>
							self::CONFIG_GROUP_PATH

						,
						Df_Admin_Model_Config_Extractor_Font::PARAM__CONFIG_KEY_PREFIX =>
							self::CONFIG_KEY_PREFIX__SKU
					)
				)
			;


			df_assert ($result instanceof Df_Admin_Model_Config_Extractor_Font);

			$this->_skuFont = $result;

		}


		df_assert ($this->_skuFont instanceof Df_Admin_Model_Config_Extractor_Font);

		return $this->_skuFont;

	}


	/**
	* @var Df_Admin_Model_Config_Extractor_Font
	*/
	private $_skuFont;




	/**
	 * @return boolean
	 */
	public function isEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_tweaks/catalog_product_view_sku/enabled'
			)
		;

		df_result_boolean ($result);

		return $result;
	}



	/**
	 * @return boolean
	 */
	public function isLabelEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_tweaks/catalog_product_view_sku/show_label'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	const CONFIG_GROUP_PATH = 'df_tweaks/catalog_product_view_sku';
	const CONFIG_KEY_PREFIX__LABEL = 'label';
	const CONFIG_KEY_PREFIX__SKU = 'sku';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings_Catalog_Product_View_Sku';
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