<?php



/**
 * Допускает только относящиеся к данному правилу подарки
 */
class Df_PromoGift_Model_Validate_Gift_RelatedToRuleGiven
	extends Df_Core_Model_Abstract
	implements Zend_Validate_Interface {




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Model_Validate_Gift_RelatedToRuleGiven';
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












    /**
     * Returns an array of message codes that explain why a previous isValid() call
     * returned false.
     *
     * If isValid() was never called or if the most recent isValid() call
     * returned true, then this method returns an empty array.
     *
     * This is now the same as calling array_keys() on the return value from getMessages().
     *
     * @return array
     * @deprecated Since 1.5.0
     */
    public function getErrors() {
		return array ();
	}



	/**
	 * @param  mixed $value
	 * @return boolean
	 * @throws Zend_Validate_Exception If validation of $value is impossible
	 */
	public function isValid ($value) {
		return
				($value instanceof Df_PromoGift_Model_Gift)
			&&
				(
					/** @var Df_PromoGift_Model_Gift $value */

					$value->getRuleId () == $this->getRuleId ()
				)
		;
	}



	/**
	 * @return array
	 */
	public function getMessages() {
		return array ();
	}



	/**
	 * @return int
	 */
	private function getRuleId () {
		return $this->cfg (self::PARAM_RULE_ID);
	}



	const PARAM_RULE_ID = 'ruleId';


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM_RULE_ID, new Df_Zf_Validate_Int ()
			)
		;
	}

}