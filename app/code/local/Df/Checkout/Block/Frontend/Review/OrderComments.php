<?php

class Df_Checkout_Block_Frontend_Review_OrderComments extends Df_Core_Block_Template {
	
	
	
	/**
	 * @return string
	 */
	public function getFloatRule () {
	
		if (!isset ($this->_floatRule)) {
	
			/** @var string $result  */
			$result = Df_Core_Const::T_EMPTY;

			if (
					df_cfg ()->checkout()->orderComments()->specifyTextareaPosition()
				&&
					(
							Df_Admin_Model_Config_Source_Layout_Float::NONE
						!==
							df_cfg ()->checkout()->orderComments()->getTextareaFloat()
					)
			) {

				$result =
					Df_Core_Model_Output_Css_Rule::compose (
						'float'
						,
						df_cfg ()->checkout()->orderComments()->getTextareaFloat()
						,
						null
						,
						true
					)
				;

			}
	
	
			df_assert_string ($result);
	
			$this->_floatRule = $result;
	
		}
	
	
		df_result_string ($this->_floatRule);
	
		return $this->_floatRule;
	
	}
	
	
	/**
	* @var string
	*/
	private $_floatRule;



	
	
	
	/**
	 * @return string
	 */
	public function getMarginRule () {
	
		if (!isset ($this->_marginRule)) {
	
			/** @var string $result  */
			$result = Df_Core_Const::T_EMPTY;

			if (
					df_cfg ()->checkout()->orderComments()->specifyTextareaPosition()
				&&
					df_cfg ()->checkout()->orderComments()->specifyTextareaHoriziontalShift()
				&&
					(0 < df_cfg ()->checkout()->orderComments()->getTextareaHoriziontalShiftLength())
			) {

				$result =
					Df_Core_Model_Output_Css_Rule::compose (
						array (
							'margin'
							,
							df_cfg ()->checkout()->orderComments()
								->getTextareaHoriziontalShiftDirection()							
						)
						,
						-1 * df_cfg ()->checkout()->orderComments()->getTextareaHoriziontalShiftLength()
						,
						'px'
						,
						true
					)
				;

			}
	
	
			df_assert_string ($result);
	
			$this->_marginRule = $result;
	
		}
	
	
		df_result_string ($this->_marginRule);
	
		return $this->_marginRule;
	
	}
	
	
	/**
	* @var string
	*/
	private $_marginRule;	
	
	
	
	


	/**
	 * @return bool
	 */
	public function getNeedInsertFormTag () {

		/** @var bool $result  */
		$result = $this->_needInsertFormTag;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	public function getTextareaWidth () {

		/** @var int $result  */
		$result = df_cfg()->checkout()->orderComments()->getTextareaWidth();

		df_result_integer ($result);

		return $result;
	}




	/**
	 * @return int
	 */
	public function getTextareaVisibleRows () {

		/** @var int $result  */
		$result = df_cfg()->checkout()->orderComments()->getTextareaVisibleRows();

		df_result_integer ($result);

		return $result;
	}



	
	/**
	 * @param bool $needInsertFormTag
	 * @return Df_Checkout_Block_Frontend_Review_OrderComments
	 */
	public function setNeedInsertFormTag ($needInsertFormTag) {

		df_param_boolean ($needInsertFormTag, 0);

		$this->_needInsertFormTag = $needInsertFormTag;

		return $this;

	}



	/**
	* @var bool
	*/
	private $_needInsertFormTag = false;



	


	/**
	 * @return string|null
	 */
	protected function getDefaultTemplate () {

		/** @var string $result  */
		$result = self::DEFAULT_TEMPLATE;

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;
	}



	/**
	 * @override
	 * @return bool
	 */
	protected function needToShow () {

		/** @var bool $result  */
		$result = df_cfg()->checkout()->orderComments()->isEnabled();

		df_result_boolean ($result);

		return $result;
	}



	const DEFAULT_TEMPLATE = 'df/checkout/review/orderComments.phtml';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Block_Frontend_Review_OrderComments';
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


