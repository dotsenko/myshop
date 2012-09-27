<?php


class Df_PromoGift_Model_Gift extends Df_Core_Model_Abstract {



	/**
	 * @return Mage_Catalog_Model_Product
	 */
	public function getProduct () {
		return
			$this->getParamAsModel (
				self::PARAM_PRODUCT
				,
				Df_Catalog_Const::PRODUCT_CLASS_MF
				,
				Df_Catalog_Const::PRODUCT_CLASS
			)
		;
	}


	/**
	 * @return int
	 */
	public function getProductId () {
		return $this->getModelId (self::PARAM_PRODUCT);
	}


	/**
	 * @param Mage_Catalog_Model_Product $product
	 * @return Df_PromoGift_Model_Gift
	 */
	public function setProduct (Mage_Catalog_Model_Product $product) {
		$this->setData (self::PARAM_PRODUCT, $product);
		return $this;
	}





	/**
	 * @return Mage_SalesRule_Model_Rule
	 */
	public function getRule () {
		return
			$this->getParamAsModel (
				self::PARAM_RULE
				,
				Df_SalesRule_Const::RULE_CLASS_MF
				,
				Df_SalesRule_Const::RULE_CLASS
			)
		;
	}


	/**
	 * @return int
	 */
	public function getRuleId () {
		return $this->getModelId (self::PARAM_RULE);
	}


	/**
	 * @param Mage_SalesRule_Model_Rule $rule
	 * @return Df_PromoGift_Model_Gift
	 */
	public function setRule (Mage_SalesRule_Model_Rule $rule) {
		$this->setData (self::PARAM_RULE, $rule);
		return $this;
	}





	/**
	 * @return Mage_Core_Model_Website
	 */
	public function getWebsite () {
		return
			$this->getParamAsModel (
				self::PARAM_WEBSITE
				,
				Df_Core_Const::WEBSITE_CLASS_MF
				,
				Df_Core_Const::WEBSITE_CLASS
			)
		;
	}


	/**
	 * @return int
	 */
	public function getWebsiteId () {
		return $this->getModelId (self::PARAM_WEBSITE);
	}


	/**
	 * @param Mage_Core_Model_Website $website
	 * @return Df_PromoGift_Model_Gift
	 */
	public function setWebsite (Mage_Core_Model_Website $website) {
		$this->setData (self::PARAM_WEBSITE, $website);
		return $this;
	}



	/**
	 * @return array
	 */
	protected function getParamsForSave () {
		return
			array (
				self::PARAM_PRODUCT
				,
				self::PARAM_RULE
				,
				self::PARAM_WEBSITE
			)
		;
	}





	/**
	 * @override
	 * @return void
	 */
    protected function _construct() {
		parent::_construct();
        $this->_init (self::RESOURCE_MODEL);
		$this
			->validateClass (self::PARAM_PRODUCT, Df_Catalog_Const::PRODUCT_CLASS , false)
			->validateClass (self::PARAM_RULE, Df_SalesRule_Const::RULE_CLASS , false)
			->validateClass (self::PARAM_WEBSITE, Df_Core_Const::WEBSITE_CLASS , false)
		;
    }



	/**
	 * Prefix of model events names
	 *
	 * @var string
	 */
	protected $_eventPrefix = 'df_promo_gift';



	/**
	 * Parameter name in event
	 *
	 * In observe method you can use $observer->getEvent()->getGift() in this case
	 *
	 * @var string
	 */
	protected $_eventObject = 'gift';




	const PARAM_PRODUCT = 'product';
	const PARAM_RULE = 'rule';
	const PARAM_WEBSITE = 'website';



	const RESOURCE_MODEL = 'df_promo_gift/gift';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Model_Gift';
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


