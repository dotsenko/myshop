<?php

class Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_Prices
	extends Df_1C_Model_Cml2_Import_Data_Collection {



	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_Price|null
	 */
	public function getMain () {

		/** @var Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_Price|null $result  */
		$result =
			$this->findByExternalId (
				$this->getRegistry()->getPriceTypes()->getMain()->getExternalId()
			)
		;

		if (!is_null ($result)) {
			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_Price);
		}


		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getItemClassMf () {
		return Df_1C_Model_Cml2_Import_Data_Entity_OfferPart_Price::getNameInMagentoFormat();
	}




	/**
	 * @override
	 * @return array
	 */
	protected function getXmlPathAsArray () {
		return
			array (
				'Цены'
				,
				'Цена'
			)
		;
	}



	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Offer
	 */
	private function getOffer () {
		return $this->cfg (self::PARAM__OFFER);
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__OFFER, Df_1C_Model_Cml2_Import_Data_Entity_Offer::getClass()
			)
		;
	}



	const PARAM__OFFER = 'offer';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Collection_OfferPart_Prices';
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
