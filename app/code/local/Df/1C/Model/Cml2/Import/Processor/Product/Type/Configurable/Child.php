<?php

class Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable_Child
	extends Df_1C_Model_Cml2_Import_Processor_Product_Type_Simple_Abstract {



	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Import_Processor
	 */
	public function process () {

		if ($this->getEntityOffer()->isTypeConfigurableChild()) {

			$this->getImporter()->import();


			df_helper()->_1c()
				->log (
					sprintf (
						'%s товар «%s».'
						,
						!is_null ($this->getExistingMagentoProduct()) ? 'Обновлён' : 'Создан'
						,
						$this->getImporter()->getProduct()->getName()
					)
				)
			;


			df_helper()->dataflow()->registry()->products()
				->addEntity (
					$this->getImporter()->getProduct()
				)
			;

		}

		return $this;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getSku () {

		if (!isset ($this->_sku)) {

			/** @var string $result  */
			$result =
					!is_null ($this->getExistingMagentoProduct())
				?
					$this->getExistingMagentoProduct()->getSku()
				:
					$this->getEntityOffer()->getExternalId()
			;


			df_assert_string ($result);

			$this->_sku = $result;

		}


		df_result_string ($this->_sku);

		return $this->_sku;

	}


	/**
	* @var string
	*/
	private $_sku;





	/**
	 * @override
	 * @return int
	 */
	protected function getVisibility () {
		return Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE;
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable_Child';
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


