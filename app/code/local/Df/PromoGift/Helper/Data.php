<?php

class Df_PromoGift_Helper_Data extends Mage_Core_Helper_Abstract {



	/**
	 * @return Df_PromoGift_Model_Customer_Rule_Counter
	 */
	public function getCustomerRuleCounter () {

		if (!isset ($this->_customerRuleCounter)) {

			$this->_customerRuleCounter =
				df_model (
					Df_PromoGift_Model_Customer_Rule_Counter::getNameInMagentoFormat()
				)
			;

		}

		return $this->_customerRuleCounter;
	}


	/**
	 * @var Df_PromoGift_Model_Customer_Rule_Counter
	 */
	private $_customerRuleCounter;





	/**
	 * @return Df_PromoGift_Model_PromoAction_Collection
	 */
	public function getApplicablePromoActions () {

		if (!isset ($this->_applicablePromoActions))  {

			/** @var $result Df_PromoGift_Model_PromoAction_Collection */
			$result =
				df_model (
					Df_PromoGift_Model_PromoAction_Collection::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_PromoGift_Model_PromoAction_Collection);

			/**
			 * Иначе ioncube работает некорректно
			 */
			$result->loadData ();

			df_result_collection ($result, Df_PromoGift_Model_PromoAction::getClass());
			/*************************************/


			$this->_applicablePromoActions = $result;
		}


		return $this->_applicablePromoActions;
	}


	private $_applicablePromoActions;




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Helper_Data';
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