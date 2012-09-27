<?php


class Df_Chronopay_Block_Gate_Response extends Df_Core_Block_Template {





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Chronopay_Block_Gate_Response';
	}


	/**
	 * Например, для класса Df_SalesRule_Block_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return
			df()->reflection()

				/**
				 * Для блоков тоже работает
				 */
				->getModelNameInMagentoFormat (
					self::getClass()
				)
		;
	}










	/**
	 * @var array
	 */
	private $_items;

	/**
	 * @return array
	 */
	public function getItems () {
		if (!isset ($this->_items)) {
			$this->_items =
				array (
					"ChronoPay error code" => $this->getModel ()->getCode ()
				    ,
					"ChronoPay error message"  => $this->getModel ()->getMessage ()
				    ,
					"Transaction ID"  => $this->getModel ()->getTransactionId ()
				    ,
					"ChronoPay extended error code"  => $this->getModel ()->getExtendedCode ()
				    ,
					"ChronoPay extended error message"  => $this->getModel ()->getExtendedMessage ()
				)
			;
		}
	    return $this->_items;
	}



	/**
	 * @return Df_Chronopay_Model_Gate_Response
	 */
	public function getModel () {
		return $this->getData ("model");
	}


}


