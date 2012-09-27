<?php

class Df_PromoGift_Model_Handler_Adminhtml_Block_Actions_PrepareForm_AddInputMaxUsagesPerQuote
	extends Df_Core_Model_Handler {


	/**
	 * @return void
	 */
	public function handle () {

		/**
		 * Добавляем поле
		 */

		$this->getEvent ()->getActionsFieldset()
			->addField(
				Df_PromoGift_Const::DB__SALES_RULE__MAX_USAGES_PER_QUOTE
				,
				'text'
				,
				array (
					'name' => Df_PromoGift_Const::DB__SALES_RULE__MAX_USAGES_PER_QUOTE
					,
					'label' => df_helper()->promoGift() ->__ (
						Df_PromoGift_Const::T_MAX_USAGES_PER_QUOTE
					)
				)
				,
				/**
				 * Поле, после которого система разместит наше поле
				 */
				"discount_qty"
			)
		;
	}



	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип результата
	 *
	 * @return Df_Adminhtml_Model_Event_Block_SalesRule_Actions_PrepareForm
	 */
	protected function getEvent () {
		return parent::getEvent();
	}



	/**
	 * Класс события (для валидации события)
	 *
	 * @return string
	 */
	protected function getEventClass () {
		return Df_Adminhtml_Model_Event_Block_SalesRule_Actions_PrepareForm::getClass();
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Model_Handler_Adminhtml_Block_Actions_PrepareForm_AddInputMaxUsagesPerQuote';
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


