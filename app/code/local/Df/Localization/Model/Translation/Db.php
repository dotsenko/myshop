<?php


class Df_Localization_Model_Translation_Db extends Df_Core_Model_Abstract {





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Localization_Model_Translation_Db';
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









	/**
	 * @var array
	 */
	private $_items;

	/**
	 * @return array
	 */
	public function getItems () {
		if (!isset ($this->_items)) {
			$this->_items =
				$this
					->getCoreTranslateResource ()
						->getTranslationArray (
							null
							,
							$this->getCoreTranslate ()->getLocale()
						)
			;
		}
		return $this->_items;
	}




	/**
	 * @return Mage_Core_Model_Resource_Translate|Mage_Core_Model_Mysql4_Translate
	 */
	private function getCoreTranslateResource () {
		return Mage::getSingleton('core/translate')->getResource();
	}


	/**
	 * @return Mage_Core_Model_Translate
	 */
	private function getCoreTranslate () {
		return Mage::getSingleton('core/translate');
	}


}