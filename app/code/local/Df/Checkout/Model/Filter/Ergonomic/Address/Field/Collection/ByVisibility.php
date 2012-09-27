<?php

class Df_Checkout_Model_Filter_Ergonomic_Address_Field_Collection_ByVisibility
	extends Df_Core_Model_Filter_Collection {






	/**
	 * Создает коллецию - результат фильтрации.
	 * Потомки могут перекрытием этого метода создать коллекцию своего класса.
	 * Метод должен возвращать объект класса Df_Varien_Data_Collection или его потомков
	 *
	 * @return Df_Checkout_Model_Collection_Ergonomic_Address_Field
	 */
	protected function createResultCollection () {

		/** @var Df_Checkout_Model_Collection_Ergonomic_Address_Field $result */
		$result =
			df_model (
				Df_Checkout_Model_Collection_Ergonomic_Address_Field::getNameInMagentoFormat()
			)
		;


		df_assert ($result instanceof Df_Checkout_Model_Collection_Ergonomic_Address_Field);

		return $result;
	}






	/**
	 * @return Zend_Validate_Interface
	 */
	protected function createValidator () {

		$result =
			df_model (
				Df_Checkout_Model_Validator_Ergonomic_Address_Field_Visible
					::getNameInMagentoFormat()
			)
		;

		df_assert ($result instanceof Df_Checkout_Model_Validator_Ergonomic_Address_Field_Visible);

		return $result;
	}




	/**
	 * Должна возвращать класс элементов коллекции
	 *
	 * @return string
	 */
	protected function getItemClass () {
		return Df_Checkout_Block_Frontend_Ergonomic_Address_Field::getClass ();
	}



	
	
	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Model_Filter_Ergonomic_Address_Field_Collection_ByVisibility';
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
