<?php


class Df_Tweaks_Helper_Settings_Labels extends Df_Core_Helper_Settings {
	
	
	/**
	 * @return Df_Admin_Model_Config_Extractor_Font
	 */
	public function getButtonFont () {

		if (!isset ($this->_buttonFont)) {

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
							self::CONFIG_KEY_PREFIX__BUTTON
					)
				)
			;


			df_assert ($result instanceof Df_Admin_Model_Config_Extractor_Font);

			$this->_buttonFont = $result;

		}


		df_assert ($this->_buttonFont instanceof Df_Admin_Model_Config_Extractor_Font);

		return $this->_buttonFont;

	}


	/**
	* @var Df_Admin_Model_Config_Extractor_Font
	*/
	private $_buttonFont;
	
	

	
	
	
	const CONFIG_GROUP_PATH = 'df_tweaks/labels';
	const CONFIG_KEY_PREFIX__BUTTON = 'button';


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings_Labels';
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