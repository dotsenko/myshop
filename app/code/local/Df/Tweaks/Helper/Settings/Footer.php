<?php


class Df_Tweaks_Helper_Settings_Footer extends Df_Core_Helper_Settings {



	/**
	 * @return boolean
	 */
	public function getRemoveAdvancedSearchLink () {
		return
			Mage::getStoreConfig (
				'df_tweaks/footer/remove_advanced_search_link'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function getRemoveHelpUs () {
		return
			Mage::getStoreConfig (
				'df_tweaks/footer/remove_help_us'
			)
		;
	}


	/**
	 * @return boolean
	 */
	public function getRemoveSearchTermsLink () {
		return
			Mage::getStoreConfig (
				'df_tweaks/footer/remove_search_terms_link'
			)
		;
	}



	/**
	 * @return boolean
	 */
	public function getUpdateYearInCopyright () {
		return
			Mage::getStoreConfig (
				'df_tweaks/footer/update_year_in_copyright'
			)
		;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Settings_Footer';
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