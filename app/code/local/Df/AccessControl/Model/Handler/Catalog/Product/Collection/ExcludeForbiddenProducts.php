<?php


class Df_AccessControl_Model_Handler_Catalog_Product_Collection_ExcludeForbiddenProducts
	extends Df_Core_Model_Handler {




	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		if (
				df_enabled (Df_Core_Feature::ACCESS_CONTROL)
			&&
				df_cfg()->admin()->access_control()->getEnabled ()
			&&
				!is_null (df_helper()->accessControl()->getCurrentRole())
		) {

			if (df_helper()->accessControl()->getCurrentRole()->isModuleEnabled()) {
				/**
				 * Добавляем фильтр по разрешённым товарным разделам.
				 */

				/** @var Df_Catalog_Model_Resource_Product_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $collection  */
				$collection = $this->getEvent()->getCollection();

				df_helper()->catalog()->assert()->productCollection ($collection);


				if ($collection instanceof Df_Catalog_Model_Resource_Product_Collection) {

					/** @var Df_Catalog_Model_Resource_Product_Collection $collection  */
					$collection
						->addCategoriesFilter (
						df_helper()->accessControl()->getCurrentRole()->getCategoryIds()
						)
					;

				}
			}
		}
	}







	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @return Df_Catalog_Model_Event_Product_Collection_Load_Before
	 */
	protected function getEvent () {

		/** @var Df_Catalog_Model_Event_Product_Collection_Load_Before $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Catalog_Model_Event_Product_Collection_Load_Before);

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
		$result = Df_Catalog_Model_Event_Product_Collection_Load_Before::getClass();

		df_result_string ($result);

		return $result;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_AccessControl_Model_Handler_Catalog_Product_Collection_ExcludeForbiddenProducts';
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


