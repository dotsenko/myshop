<?php

class Df_Sales_Block_Order_Email_Comments extends Df_Core_Block_Template {


	/**
	 * @override
	 * @return string
	 */
	public function getArea() {

		/** @var string $result  */
		$result = Df_Core_Const_Design_Area::FRONTEND;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getComments () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__COMMENTS);

		if (is_null ($result)) {
			$result = Df_Core_Const::T_EMPTY;
		}

		return $result;

	}



	/**
	 * @override
	 * @return string
	 */
	protected function getDefaultTemplate () {

		/** @var string $result  */
		$result = self::DEFAULT_TEMPLATE;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @override
	 * @return bool
	 */
	protected function needToShow () {

		/** @var bool $result  */
		$result = !df_empty ($this->getComments());

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__COMMENTS, new Df_Zf_Validate_String(), false
			)
		;
	}



	const PARAM__COMMENTS = 'comments';
	const DEFAULT_TEMPLATE = 'df/sales/order/email/comments.phtml';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Block_Order_Email_Comments';
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


