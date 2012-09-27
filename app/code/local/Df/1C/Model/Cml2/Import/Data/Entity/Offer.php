<?php

class Df_1C_Model_Cml2_Import_Data_Entity_Offer extends Df_1C_Model_Cml2_Import_Data_Entity {



	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Offer[]
	 */
	public function getConfigurableChildren () {
	
		if (!isset ($this->_configurableChildren)) {
	
			/** @var Df_1C_Model_Cml2_Import_Data_Entity_Offer[] $result  */
			$result = array ();


			if (!$this->isTypeConfigurableChild()) {

				foreach ($this->getRegistryOffers() as $offer) {

					/** @var Df_1C_Model_Cml2_Import_Data_Entity_Offer $offer */
					df_assert ($offer instanceof Df_1C_Model_Cml2_Import_Data_Entity_Offer);


					if ($offer->isTypeConfigurableChild()) {

						if (
								$this->getExternalId()
							===
								$offer->getExternalIdForConfigurableParent()
						) {

							$result []= $offer;

						}

					}

				}
			}

	
			df_assert_array ($result);
	
			$this->_configurableChildren = $result;
	
		}
	
	
		df_result_array ($this->_configurableChildren);
	
		return $this->_configurableChildren;
	
	}
	
	
	/**
	* @var Df_1C_Model_Cml2_Import_Data_Entity_Offer[]
	*/
	private $_configurableChildren;





	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Offer
	 */
	public function getConfigurableParent () {

		/** @var Df_1C_Model_Cml2_Import_Data_Entity_Offer $result  */
		$result =
				!$this->isTypeConfigurableChild()
			?
				null
			:
				$this->getRegistryOffers()->findByExternalId (
					$this->getExternalIdForConfigurableParent()
				)
		;

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity_Offer);

		return $result;

	}




	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Product
	 */
	public function getEntityProduct () {

		/** @var Df_1C_Model_Cml2_Import_Data_Entity_Product $result  */
		$result =
			$this->getRegistryProductEntities()->findByExternalId (
				$this->getExternalIdForConfigurableParent()
			)
		;

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity_Product);

		return $result;

	}

	
	
	
	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_OptionValues
	 */
	public function getOptionValues () {

		if (!isset ($this->_optionValues)) {

			/** @var Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_OptionValues $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_OptionValues::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_OptionValues
							::PARAM__SIMPLE_XML => $this->getSimpleXmlElement()
					)
				)
			;


			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_OptionValues);

			$this->_optionValues = $result;

		}


		df_assert ($this->_optionValues instanceof Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_OptionValues);

		return $this->_optionValues;

	}


	/**
	* @var Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_OptionValues
	*/
	private $_optionValues;	
	
	



	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_Prices
	 */
	public function getPrices () {

		if (!isset ($this->_prices)) {

			/** @var Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_Prices $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_Prices::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_Prices
							::PARAM__SIMPLE_XML => $this->getSimpleXmlElement()

						,
						Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_Prices
							::PARAM__OFFER => $this
					)
				)
			;

			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_Prices);

			$this->_prices = $result;

		}


		df_assert ($this->_prices instanceof Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_Prices);

		return $this->_prices;

	}


	/**
	* @var Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_Prices
	*/
	private $_prices;





	/**
	 * @return Mage_Catalog_Model_Product
	 */
	public function getProduct () {

		/** @var Mage_Catalog_Model_Product $result  */
		$result =
			df_helper()->dataflow()->registry()->products()->findByExternalId (
				$this->getExternalId()
			)
		;

		if (is_null ($result)) {
			df_error (
				sprintf (
					'Товар не найден в реестре: «%s»'
					,
					$this->getExternalId()
				)
			);
		}

		df_assert ($result instanceof Mage_Catalog_Model_Product);

		return $result;

	}





	/**
	 * @return int
	 */
	public function getQuantity () {

		/** @var int $result  */
		$result =
			intval (
				floatval (
					$this->getEntityParam ('Количество')
				)
			)
		;

		df_result_integer ($result);

		return $result;

	}





	/**
	 * @return bool
	 */
	public function isTypeConfigurableChild () {

		/** @var bool $result  */
		$result =
			(1 < count ($this->getExternalIdExploded()))
		;

		df_result_boolean ($result);

		return $result;

	}





	/**
	 * @return bool
	 */
	public function isTypeConfigurableParent () {

		/** @var bool $result  */
		$result =
			(0 < count ($this->getConfigurableChildren()))
		;

		df_result_boolean ($result);

		return $result;

	}






	/**
	 * @return bool
	 */
	public function isTypeSimple () {

		/** @var bool $result  */
		$result =
			!(
					$this->isTypeConfigurableChild()
				||
					$this->isTypeConfigurableParent()
			)
		;

		df_result_boolean ($result);

		return $result;

	}







	/**
	 * @return array
	 */
	private function getExternalIdExploded () {

		/** @var array $result  */
		$result =
			explode (
				'#'
				,
				parent::getExternalId()
			)
		;

		df_result_array ($result);

		return $result;

	}
	
	
	
	
	
	/**
	 * @return string
	 */
	private function getExternalIdForConfigurableParent () {

		/** @var string $result  */
		$result = df_array_first ($this->getExternalIdExploded());

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_Offers
	 */
	private function getRegistryOffers () {

		/** @var Df_1C_Model_Cml2_Import_Data_Collection_Offers $result  */
		$result = $this->getRegistry()->import()->collections()->getOffers();

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_Offers);

		return $result;

	}




	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_Products
	 */
	private function getRegistryProductEntities () {

		/** @var Df_1C_Model_Cml2_Import_Data_Collection_Products $result  */
		$result = $this->getRegistry()->import()->collections()->getProducts();

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_Products);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_Offer';
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

