<?php


class Df_Sales_Model_Handler_OrderStatusHistory_SetVisibleOnFrontParam extends Df_Core_Model_Handler {


	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		/**
		 * Проверка обязательна, иначе некорректно работает добавление комментариев администратором
		 * @link http://magento-forum.ru/topic/2394/
		 */
		if (!df_is_admin()) {

			$this->getEvent()->getOrderStatusHistory()
				->setData (
					Df_Sales_Const::ORDER_STATUS_HISTORY_PARAM__IS_VISIBLE_ON_FRONT
					,
					$this->getEvent()->getOrder()->getData (
						Df_Sales_Const::ORDER_PARAM__DF_COMMENT_IS_VISIBLE_ON_FRONT
					)
				)
			;
		}

		else {

			/**
			 * Обратите внимание,
			 * что если заказ только что создан из административной части,
			 * то комментарий к нему не будет виден клиентом —
			 * это стандартное поведение Magento.
			 *
			 * Можно опционально его изменить...
			 */

			if (
					df_cfg()->sales()->orderComments()->adminOrderCreate_commentIsVisibleOnFront()
				&&
					df_enabled (Df_Core_Feature::SALES)
			) {
				/** @var Mage_Sales_Model_Order $order  */
				$order = $this->getEvent()->getOrderStatusHistory()->getOrder();

				/**
				 * $this->getEvent()->getOrderStatusHistory()->getOrder() вернёт null
				 * в сценарии "отправить письмо-оповещение"
				 */
				if (is_null ($order)) {
					$order = Mage::registry ('current_order');
				}

				df_assert ($order instanceof Mage_Sales_Model_Order);


				if (('new' === $order->getState()) && ('pending' === $order->getStatus())) {
					$this->getEvent()->getOrderStatusHistory()
						->setData (
							Df_Sales_Const::ORDER_STATUS_HISTORY_PARAM__IS_VISIBLE_ON_FRONT
							,
							true
						)
					;
				}
			}

		}

	}











	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @return Df_Sales_Model_Event_OrderStatusHistory_SaveBefore
	 */
	protected function getEvent () {

		/** @var Df_Sales_Model_Event_OrderStatusHistory_SaveBefore $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Sales_Model_Event_OrderStatusHistory_SaveBefore);

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
		$result = Df_Sales_Model_Event_OrderStatusHistory_SaveBefore::getClass();

		df_result_string ($result);

		return $result;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Model_Handler_OrderStatusHistory_SetVisibleOnFrontParam';
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


