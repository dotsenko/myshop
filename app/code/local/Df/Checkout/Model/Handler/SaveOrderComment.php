<?php

class Df_Checkout_Model_Handler_SaveOrderComment extends Df_Core_Model_Handler {



	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {


		if (
				!df_empty ($this->getOrderComment())
			&&
				df_cfg()->checkout()->orderComments()->isEnabled()
			&&
				df_enabled (Df_Core_Feature::CHECKOUT)
		) {

			$this->getEvent()->getOrder()
				->addData (
					array (
						/**
						 * Устанавка customer note
						 * приводит в числе прочего к вызову addStatusHistoryComment
						 */
						Df_Sales_Const::ORDER_PARAM__CUSTOMER_NOTE => $this->getOrderComment()
						,
						Df_Sales_Const::ORDER_PARAM__CUSTOMER_NOTE_NOTIFY =>
							df_cfg()->checkout()->orderComments()->needShowInOrderEmail()
						,
						Df_Sales_Const::ORDER_PARAM__DF_COMMENT_IS_VISIBLE_ON_FRONT =>
							df_cfg()->checkout()->orderComments()->needShowInCustomerAccount()
					)
				)
			;

		}

	}





	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @override
	 * @return Df_Checkout_Model_Event_SaveOrder_Abstract
	 */
	protected function getEvent () {

		/** @var Df_Checkout_Model_Event_SaveOrder_Abstract $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Checkout_Model_Event_SaveOrder_Abstract);

		return $result;
	}





	/**
	 * Класс события (для валидации события)
	 *
	 * @override
	 * @return string
	 */
	protected function getEventClass () {

		/** @var string $result  */
		$result = Df_Checkout_Model_Event_SaveOrder_Abstract::getClass();

		df_result_string ($result);

		return $result;

	}



	/**
	 * @return string
	 */
	private function getOrderComment () {

		/** @var string $result  */
		$result =
			df_trim (
				df_request (
					self::REQUEST_PARAM__ORDER_COMMENT
					,
					Df_Core_Const::T_EMPTY
				)
			)
		;

		df_result_string ($result);

		return $result;

	}




	const REQUEST_PARAM__ORDER_COMMENT = 'df_order_comment';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Model_Handler_SaveOrderComment';
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


