<?php

class Df_Sales_Model_Quote_Item_Extended extends Df_Core_Model_Abstract {

	/**
	 * @return float
	 */
	public function getBaseCost() {
		return floatval ($this->getUsingParent ('base_cost'));
	}



	/**
	 * @return float
	 */
	public function getBaseDiscountAmount () {
		return floatval ($this->getUsingParent ('base_discount_amount'));
	}



	/**
	 * @return float
	 */
	public function getBaseHiddenTaxAmount () {
		return floatval ($this->getUsingParent ('base_hidden_tax_amount'));
	}



	/**
	 * @return float
	 */
	public function getBasePrice () {
		return floatval ($this->getUsingParent ('base_price'));
	}



	/**
	 * @return float
	 */
	public function getBasePriceInclTax () {
		return floatval ($this->getUsingParent ('base_price_incl_tax'));
	}




	/**
	 * @return float
	 */
	public function getBaseRowTotal () {
		return floatval ($this->getUsingParent ('base_row_total'));
	}



	/**
	 * @return float
	 */
	public function getBaseRowTotalInclTax () {
		return floatval ($this->getUsingParent ('base_row_total_incl_tax'));
	}



	/**
	 * @return float
	 */
	public function getBaseTaxAmount () {
		return floatval ($this->getUsingParent ('base_tax_amount'));
	}



	/**
	 * @return float
	 */
	public function getBaseTaxBeforeDiscount () {
		return floatval ($this->getUsingParent ('base_tax_before_discount'));
	}



	/**
	 * @return float
	 */
	public function getBaseWeeeTaxAppliedAmount() {
		return floatval ($this->getUsingParent ('base_weee_tax_applied_amount'));
	}



	/**
	 * @return float
	 */
	public function getBaseWeeeTaxAppliedRowAmount() {
		return floatval ($this->getUsingParent ('base_weee_tax_applied_row_amnt'));
	}



	/**
	 * @return float
	 */
	public function getBaseWeeeTaxDisposition() {
		return floatval ($this->getUsingParent ('base_weee_tax_disposition'));
	}



	/**
	 * @return float
	 */
	public function getBaseWeeeTaxRowDisposition() {
		return floatval ($this->getUsingParent ('base_weee_tax_row_disposition'));
	}



	/**
	 * @return float
	 */
	public function getCustomPrice () {
		return floatval ($this->getUsingParent ('custom_price'));
	}



	/**
	 * @return float
	 */
	public function getDiscountAmount () {
		return floatval ($this->getUsingParent ('discount_amount'));
	}



	/**
	 * @return float
	 */
	public function getDiscountPercent () {
		return floatval ($this->getUsingParent ('discount_percent'));
	}



	/**
	 * @return float
	 */
	public function getHiddenTaxAmount () {
		return floatval ($this->getUsingParent ('hidden_tax_amount'));
	}



	/**
	 * @return float
	 */
	public function getOriginalCustomPrice () {
		return floatval ($this->getUsingParent ('original_custom_price'));
	}



	/**
	 * @return float
	 */
	public function getPrice () {
		return floatval ($this->getUsingParent ('price'));
	}



	/**
	 * @return float
	 */
	public function getPriceInclTax () {
		return floatval ($this->getUsingParent ('price_incl_tax'));
	}



	/**
	 * @return float
	 */
	public function getQty() {
		return floatval ($this->getUsingParent ('qty'));
	}



	/**
	 * @return float
	 */
	public function getRowTotal () {
		return floatval ($this->getUsingParent ('row_total'));
	}



	/**
	 * @return float
	 */
	public function getRowTotalInclTax () {
		return floatval ($this->getUsingParent ('row_total_incl_tax'));
	}



	/**
	 * @return float
	 */
	public function getRowTotalWithDiscount() {
		return floatval ($this->getUsingParent ('row_total_with_discount'));
	}



	/**
	 * @return float
	 */
	public function getRowWeight() {
		return floatval ($this->getUsingParent ('row_weight'));
	}



	/**
	 * @return float
	 */
	public function getTaxAmount () {
		return floatval ($this->getUsingParent ('tax_amount'));
	}



	/**
	 * @return float
	 */
	public function getTaxBeforeDiscount () {
		return floatval ($this->getUsingParent ('tax_before_discount'));
	}



	/**
	 * @return float
	 */
	public function getTaxPercent () {
		return floatval ($this->getUsingParent ('tax_percent'));
	}



	/**
	 * @return Mage_Sales_Model_Quote_Item
	 */
	private function getQuoteItem () {
		return $this->cfg (self::PARAM__QUOTE_ITEM);
	}



	/**
	 * @return Mage_Sales_Model_Quote_Item|null
	 */
	private function getParent () {
		return $this->getQuoteItem()->getParentItem();
	}




	/**
	 * @param string $key
	 * @return mixed
	 */
	private function getUsingParent ($key) {

		/** @var string $result  */
		$result =
				$this->getParent ()
			?
				$this->getParent()->getDataUsingMethod ($key)
			:
				$this->getQuoteItem()->getDataUsingMethod ($key)
		;

		/**
		 * Не знаем тип результата, поэтому валидацию результата не проводим
		 */

		return $result;
	}



	/**
	 * @return float
	 */
	public function getWeeeTaxApplied () {
		return floatval ($this->getUsingParent ('weee_tax_applied'));
	}



	/**
	 * @return float
	 */
	public function getWeeeTaxAppliedAmount () {
		return floatval ($this->getUsingParent ('weee_tax_applied_amount'));
	}



	/**
	 * @return float
	 */
	public function getWeeeTaxAppliedRowAmount () {
		return floatval ($this->getUsingParent ('weee_tax_applied_row_amount'));
	}



	/**
	 * @return float
	 */
	public function getWeeeTaxRowDisposition () {
		return floatval ($this->getUsingParent ('weee_tax_row_disposition'));
	}



	/**
	 * @return float
	 */
	public function getWeight () {
		return floatval ($this->getUsingParent ('weight'));
	}



	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__QUOTE_ITEM, 'Mage_Sales_Model_Quote_Item'
			)
		;
	}



	const PARAM__QUOTE_ITEM = 'quote_item';




	/**
	 * @static
	 * @param Mage_Sales_Model_Quote_Item $quoteItem
	 * @return Df_Sales_Model_Quote_Item_Extended
	 */
	public static function create (Mage_Sales_Model_Quote_Item $quoteItem) {

		/** @var Df_Sales_Model_Quote_Item_Extended $result */
		$result =
			df_model (
				self::getNameInMagentoFormat()
				,
				array (
					self::PARAM__QUOTE_ITEM => $quoteItem
				)
			)
		;

		df_assert ($result instanceof Df_Sales_Model_Quote_Item_Extended);

		return $result;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Model_Quote_Item_Extended';
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


