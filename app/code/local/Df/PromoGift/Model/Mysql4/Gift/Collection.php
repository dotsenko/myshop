<?php

class Df_PromoGift_Model_Mysql4_Gift_Collection
	extends Mage_Core_Model_Mysql4_Collection_Abstract {



	/**
	 * Отбраковываем неотносящиеся к магазину правила
	 *
	 * @param  int $websiteId
	 * @return Df_PromoGift_Model_Mysql4_Gift_Collection
	 */
	public function addWebsiteFilter ($websiteId) {
		$this
			->addFieldToFilter (
				$this->getSelect()->getAdapter()
					->quoteIdentifier (Df_PromoGift_Const::DB__PROMO_GIFT__WEBSITE_ID)
				,
				array (Df_Varien_Const::EQ => $websiteId)
			)
		;

		return $this;
	}



	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct();
		$this->_init (Df_PromoGift_Model_Gift::getNameInMagentoFormat());
	}



    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'df_promo_gift_gift_collection';


    /**
     * Event object name
     *
     * @var string
     */
    protected $_eventObject = 'gift_collection';



	const CLASS_NAME_MF = 'df_promo_gift/gift_collection';


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Model_Mysql4_Gift_Collection';
	}


	/**
	 * Например, для класса Df_SalesRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return
			self::CLASS_NAME_MF
		;
	}

}


