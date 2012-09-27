<?php


/**
 * Для данного множества подарков возвращает подмножество, относящееся к заданному промо-правилу
 */
class Df_PromoGift_Model_Filter_Gift_Collection_ByRuleGiven
	extends Df_Core_Model_Filter_Collection {



	/**
	 * @return Zend_Validate_Interface
	 */
	protected function createValidator () {

		$result =
			df_model (
				Df_PromoGift_Model_Validate_Gift_RelatedToRuleGiven
					::getNameInMagentoFormat()
				,
				array (
					Df_PromoGift_Model_Validate_Gift_RelatedToRuleGiven::PARAM_RULE_ID =>
						$this->getRuleId()
				)
			)
		;

		df_assert ($result instanceof Df_PromoGift_Model_Validate_Gift_RelatedToRuleGiven);

		return $result;
	}




	/**
	 * Должна возвращать класс элементов коллекции
	 *
	 * @return string
	 */
	protected function getItemClass () {
		return Df_PromoGift_Model_Gift::getClass ();
	}



	/**
	 * @return int
	 */
	private function getRuleId () {
		return $this->cfg (Df_PromoGift_Model_Validate_Gift_RelatedToRuleGiven::PARAM_RULE_ID);
	}



	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				Df_PromoGift_Model_Validate_Gift_RelatedToRuleGiven::PARAM_RULE_ID
				,
				new Df_Zf_Validate_Int ()
			)
		;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Model_Filter_Gift_Collection_ByRuleGiven';
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


