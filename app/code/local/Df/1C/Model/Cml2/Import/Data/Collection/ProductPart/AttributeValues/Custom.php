<?php

class Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_Custom
	extends Df_1C_Model_Cml2_Import_Data_Collection {



	/**
	 * @override
	 * @param Varien_Simplexml_Element $entityAsSimpleXMLElement
	 * @return Df_1C_Model_Cml2_Import_Data_Entity
	 */
	protected function createItemFromSimpleXmlElement (Varien_Simplexml_Element $entityAsSimpleXMLElement) {

		/** @var string|null $valueId  */
		$valueId = df_a ($entityAsSimpleXMLElement->asCanonicalArray(), 'ИдЗначения');

		/** @var string $itemClassMf  */
		$itemClassMf =
				is_null ($valueId)
			?
				Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_Custom
					::getNameInMagentoFormat()
			:
				Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_Custom_Option
					::getNameInMagentoFormat()
		;


		/** @var Df_1C_Model_Cml2_Import_Data_Entity $result  */
		$result =
			df_model (
				$itemClassMf
				,
				array (
					Df_1C_Model_Cml2_Import_Data_Entity::PARAM__SIMPLE_XML => $entityAsSimpleXMLElement
					,
					Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_Custom
						::PARAM__PRODUCT => $this->getProduct()
				)
			)
		;

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getItemClassMf () {
		return Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_AttributeValue_Custom::getNameInMagentoFormat();
	}




	/**
	 * @override
	 * @return array
	 */
	protected function getXmlPathAsArray () {
		return
			array (
				'ЗначенияСвойств'
				,
				'ЗначенияСвойства'
			)
		;
	}




	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Product
	 */
	private function getProduct () {
		return $this->cfg (self::PARAM__PRODUCT);
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__PRODUCT, Df_1C_Model_Cml2_Import_Data_Entity_Product::getClass()
			)
		;
	}



	const PARAM__PRODUCT = 'product';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Collection_ProductPart_AttributeValues_Custom';
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
