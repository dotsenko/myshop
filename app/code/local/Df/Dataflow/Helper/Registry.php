<?php


/**
 * Реестры нам нужны для ускорения доступа к одним и тем же объектам и коллекциям объектов.
 * Эти реестры должны использоваться всеми модулями Российской сборки Magento.
 */
class Df_Dataflow_Helper_Registry extends Mage_Core_Helper_Abstract {



	/**
	 * @return Df_Dataflow_Model_Registry_Collection_Attributes
	 */
	public function attributes () {

		/** @var Df_Dataflow_Model_Registry_Collection_Attributes $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Dataflow_Model_Registry_Collection_Attributes $result  */
			$result =
				df_model (
					Df_Dataflow_Model_Registry_Collection_Attributes::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_Dataflow_Model_Registry_Collection_Attributes);

		}

		return $result;

	}





	/**
	 * @return Df_Dataflow_Model_Registry_Collection_AttributeSets
	 */
	public function attributeSets () {

		/** @var Df_Dataflow_Model_Registry_Collection_AttributeSets $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Dataflow_Model_Registry_Collection_AttributeSets $result  */
			$result =
				df_model (
					Df_Dataflow_Model_Registry_Collection_AttributeSets::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_Dataflow_Model_Registry_Collection_AttributeSets);

		}

		return $result;

	}






	/**
	 * @return Df_Dataflow_Model_Registry_Collection_Categories
	 */
	public function categories () {

		/** @var Df_Dataflow_Model_Registry_Collection_Categories $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Dataflow_Model_Registry_Collection_Categories $result  */
			$result =
				df_model (
					Df_Dataflow_Model_Registry_Collection_Categories::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_Dataflow_Model_Registry_Collection_Categories);

		}

		return $result;

	}
	





	/**
	 * @return Df_Dataflow_Model_Registry_Collection_Products
	 */
	public function products () {

		/** @var Df_Dataflow_Model_Registry_Collection_Products $result */
		static $result;

		if (!isset ($result)) {

			/** @var Df_Dataflow_Model_Registry_Collection_Products $result  */
			$result =
				df_model (
					Df_Dataflow_Model_Registry_Collection_Products::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_Dataflow_Model_Registry_Collection_Products);

		}

		return $result;

	}







	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Helper_Registry';
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