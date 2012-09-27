<?php

class Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_PriceTypes
	extends Df_1C_Model_Cml2_Import_Data_Collection {

	
	
	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_PriceType
	 */
	public function getMain () {

		if (!isset ($this->_main)) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_PriceType $result  */
			$result =
				$this->findByName (
					df_cfg()->_1c()->products()->getMainPriceName()
				)
			;

			if (is_null ($result)) {
				df_error (
					sprintf (
						'Тип цен «%s», указанный администратором как основной,
						отсутствует в 1С:Управление торговлей.'
						,
						df_cfg()->_1c()->products()->getMainPriceName()
					)
				);
			}


			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_PriceType);

			$this->_main = $result;

		}


		df_assert ($this->_main instanceof Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_PriceType);

		return $this->_main;

	}


	/**
	* @var Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_PriceType
	*/
	private $_main;




	/**
	 * @override
	 * @return Varien_Simplexml_Element
	 */
	public function getSimpleXmlElement () {

		$result =
			$this->getRegistry()->import()->files('catalog')->getByRelativePath (
				Df_1C_Model_Cml2_Registry_Import_Files::FILE__OFFERS
			)
		;

		df_assert ($result instanceof Varien_Simplexml_Element);

		return $result;
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getItemClassMf () {
		return Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_PriceType::getNameInMagentoFormat();
	}




	/**
	 * @override
	 * @return array
	 */
	protected function getXmlPathAsArray () {
		return
			array (
				Df_Core_Const::T_EMPTY
				,
				'КоммерческаяИнформация'
				,
				'ПакетПредложений'
				,
				'ТипыЦен'
				,
				'ТипЦены'
			)
		;
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_PriceTypes';
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
