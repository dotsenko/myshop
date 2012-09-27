<?php


class Df_Tweaks_Model_Handler_AdjustNewsletterSubscription extends Df_Core_Model_Handler {


	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		if (
				df_cfg()->tweaks()->newsletter()->subscription()->getRemoveFromAll()
			||
				(
						df_cfg()->tweaks()->newsletter()->subscription()->getRemoveFromFrontpage ()
					&&
						df_handle_presents(Df_Cms_Const::LAYOUT_HANDLE__INDEX_INDEX)
				)
			||
				(
						df_cfg()->tweaks()->newsletter()->subscription()->getRemoveFromCatalogProductList ()
					&&
						df_handle_presents(Df_Catalog_Const::LAYOUT_HANDLE__CATEGORY_VIEW)
				)
			||
				(
						df_cfg()->tweaks()->newsletter()->subscription()->getRemoveFromCatalogProductView ()
					&&
						df_handle_presents(Df_Catalog_Const::LAYOUT_HANDLE__PRODUCT_VIEW)
				)
			||
				(
						df_cfg()->tweaks()->newsletter()->subscription()->getRemoveFromAccount()
					&&
						df_handle_presents(Df_Customer_Const::LAYOUT_HANDLE_ACCOUNT)
				)
		) {
			df()->layout()
				->removeBlock ('left.newsletter')
				->removeBlock ('newsletter')
			;
		}

	}





	
	


	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @override
	 * @return Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
	 */
	protected function getEvent () {

		/** @var Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter);

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
		$result = Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter::getClass();

		df_result_string ($result);

		return $result;

	}
	
	




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Model_Handler_AdjustNewsletterSubscription';
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


