<?php

class Df_Sales_Model_Order_Item_Extended extends Df_Core_Model_Abstract {

	/**
	 * @return float
	 */
	public function getAmountRefunded () {
		return floatval ($this->getUsingParent ('amount_refunded'));
	}



	/**
	 * @return float
	 */
	public function getBaseAmountRefunded () {
		return floatval ($this->getUsingParent ('base_amount_refunded'));
	}



	/**
	 * @return float
	 */
	public function getBaseDiscountInvoiced () {
		return floatval ($this->getUsingParent ('base_discount_invoiced'));
	}



	/**
	 * @return float
	 */
	public function getBaseDiscountRefunded () {
		return floatval ($this->getUsingParent ('base_discount_refunded'));
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
	public function getBaseHiddenTaxInvoiced () {
		return floatval ($this->getUsingParent ('base_hidden_tax_invoiced'));
	}



	/**
	 * @return float
	 */
	public function getBaseHiddenTaxRefunded () {
		return floatval ($this->getUsingParent ('base_hidden_tax_refunded'));
	}



	/**
	 * @return float
	 */
	public function getBaseOriginalPrice () {
		return floatval ($this->getUsingParent ('base_original_price'));
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
	public function getBaseRowInvoiced () {
		return floatval ($this->getUsingParent ('base_row_invoiced'));
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
	public function getBaseTaxInvoiced () {
		return floatval ($this->getUsingParent ('base_tax_invoiced'));
	}



	/**
	 * @return float
	 */
	public function getBaseTaxRefunded () {
		return floatval ($this->getUsingParent ('base_tax_refunded'));
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
	public function getDiscountRefunded () {
		return floatval ($this->getUsingParent ('discount_refunded'));
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
	public function getHiddenTaxCanceled () {
		return floatval ($this->getUsingParent ('hidden_tax_canceled'));
	}



	/**
	 * @return float
	 */
	public function getHiddenTaxInvoiced () {
		return floatval ($this->getUsingParent ('hidden_tax_invoiced'));
	}



	/**
	 * @return float
	 */
	public function getHiddenTaxRefunded () {
		return floatval ($this->getUsingParent ('hidden_tax_refunded'));
	}



	/**
	 * @return float
	 */
	public function getOriginalPrice () {
		return floatval ($this->getUsingParent ('original_price'));
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
	public function getQtyCanceled () {
		return floatval ($this->getUsingParent ('qty_canceled'));
	}



	/**
	 * @return float
	 */
	public function getQtyInvoiced () {
		return floatval ($this->getUsingParent ('qty_invoiced'));
	}



	/**
	 * @return float
	 */
	public function getQtyOrdered () {
		return floatval ($this->getUsingParent ('qty_ordered'));
	}



	/**
	 * @return float
	 */
	public function getQtyRefunded () {
		return floatval ($this->getUsingParent ('qty_refunded'));
	}



	/**
	 * @return float
	 */
	public function getQtyShipped () {
		return floatval ($this->getUsingParent ('qty_shipped'));
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
	public function getRowInvoiced () {
		return floatval ($this->getUsingParent ('row_invoiced'));
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
	public function getTaxCanceled () {
		return floatval ($this->getUsingParent ('tax_canceled'));
	}



	/**
	 * @return float
	 */
	public function getTaxInvoiced () {
		return floatval ($this->getUsingParent ('tax_invoiced'));
	}



	/**
	 * @return float
	 */
	public function getTaxPercent () {
		return floatval ($this->getUsingParent ('tax_percent'));
	}



	/**
	 * @return float
	 */
	public function getTaxRefunded () {
		return floatval ($this->getUsingParent ('tax_refunded'));
	}



	/**
	 * @return Mage_Sales_Model_Order_Item
	 */
	private function getOrderItem () {
		return $this->cfg (self::PARAM__ORDER_ITEM);
	}



	/**
	 * @return Mage_Sales_Model_Order_Item|null
	 */
	private function getParent () {
		return $this->getOrderItem()->getParentItem();
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
				$this->getOrderItem()->getDataUsingMethod ($key)
		;

		/**
		 * Не знаем тип результата, поэтому валидацию результата не проводим
		 */

		return $result;
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
				self::PARAM__ORDER_ITEM, 'Mage_Sales_Model_Order_Item'
			)
		;
	}



	const PARAM__ORDER_ITEM = 'order_item';




	/**
	 * @static
	 * @param Mage_Sales_Model_Order_Item $orderItem
	 * @return Df_Sales_Model_Order_Item_Extended
	 */
	public static function create (Mage_Sales_Model_Order_Item $orderItem) {

		/** @var Df_Sales_Model_Order_Item_Extended $result */
		$result =
			df_model (
				self::getNameInMagentoFormat()
				,
				array (
					self::PARAM__ORDER_ITEM => $orderItem
				)
			)
		;

		df_assert ($result instanceof Df_Sales_Model_Order_Item_Extended);

		return $result;
	}
	
	



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Model_Order_Item_Extended';
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


