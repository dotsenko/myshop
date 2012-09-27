<?php

class Df_Checkout_Helper_Settings extends Df_Core_Helper_Settings {




	/**
	 * @return Df_Checkout_Helper_Settings_Field
	 */
	public function field () {

		/** @var Df_Checkout_Helper_Settings_Field $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Checkout_Helper_Settings_Field $result  */
			$result = Mage::helper (Df_Checkout_Helper_Settings_Field::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Checkout_Helper_Settings_Field);

		}

		return $result;

	}





	/**
	 * @return Df_Checkout_Helper_Settings_Interface
	 */
	public function _interface () {

		/** @var Df_Checkout_Helper_Settings_Interface $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Checkout_Helper_Settings_Interface $result  */
			$result = Mage::helper (Df_Checkout_Helper_Settings_Interface::getNameInMagentoFormat());

			df_assert ($result instanceof Df_Checkout_Helper_Settings_Interface);

		}

		return $result;

	}






	/**
	 * @return Df_Checkout_Helper_Settings_OrderComments
	 */
	public function orderComments () {

		/** @var Df_Checkout_Helper_Settings_OrderComments $result  */
		$result = Mage::helper (Df_Checkout_Helper_Settings_OrderComments::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Checkout_Helper_Settings_OrderComments);

		return $result;

	}





	/**
	 * @return boolean
	 */
	public function getTocEnabled () {


		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_checkout/terms_and_conditions/enabled'
			)
		;

		df_result_boolean ($result);


		return $result;

	}





	/**
	 * @return string
	 */
	public function getTocContent () {

		df_assert ($this->getTocEnabled());

		if (!isset ($this->_tocContent)) {

			/** @var string $result  */
			$result =
				$this->getTocContentPage()->getData ('content')
			;


			df_assert_string ($result);

			$this->_tocContent = $result;

		}


		df_result_string ($this->_tocContent);

		return $this->_tocContent;

	}


	/**
	* @var string
	*/
	private $_tocContent;
	
	




	/**
	 * @return Df_Checkout_Helper_Settings_Patches
	 */
	public function patches () {

		/** @var Df_Checkout_Helper_Settings_Patches $result  */
		$result = Mage::helper (Df_Checkout_Helper_Settings_Patches::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Checkout_Helper_Settings_Patches);

		return $result;

	}








	/**
	 * @return Mage_Cms_Model_Page
	 */
	private function getTocContentPage () {

		df_assert ($this->getTocEnabled());

		if (!isset ($this->_tocContentPage)) {

			/** @var Mage_Cms_Model_Page $result  */
			$result =
				Mage::getModel ('cms/page')
			;

			df_assert ($result instanceof Mage_Cms_Model_Page);


			/**
			 * Обратите внимание, что в данном случае метод load загружает страницу
			 * по её текстовому идентификатору, а не по стандартному числовому
			 */
			$result
				->load (
					$this->getTocContentIdentifier()
				)
			;

			$this->_tocContentPage = $result;

		}


		df_assert ($this->_tocContentPage instanceof Mage_Cms_Model_Page);

		return $this->_tocContentPage;

	}


	/**
	* @var Mage_Cms_Model_Page
	*/
	private $_tocContentPage;








	/**
	 * Возвращает значение поля «identifier» материала.
	 * В административном интерфейсе поле называется «URL Key»
	 *
	 * @return string
	 */
	private function getTocContentIdentifier () {


		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				'df_checkout/terms_and_conditions/content'
			)
		;

		df_result_string ($result);


		return $result;

	}






	/**
	 * 1 - optional
	 */
	const DEFAULT_APPLICABILITY_CODE = '1';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Helper_Settings';
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