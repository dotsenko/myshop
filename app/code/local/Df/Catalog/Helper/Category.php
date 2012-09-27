<?php

class Df_Catalog_Helper_Category extends Mage_Core_Helper_Abstract {


	/**
	 * Перед созданием и сохранением товарного раздела
	 * надо обязательно надо установить текущим магазином административный,
	 * иначе возникают неприятные проблемы.
	 *
	 * В частности, для успешного сохранения товарного раздела
	 * надо отключить на время сохранения режим денормализации.
	 * Так вот, в стандартном программном коде Magento автоматически отключает
	 * режим денормализации при создании товарного раздела из административного магазина
	 * (в конструкторе товарного раздела).
	 *
	 * А если сохранять раздел, чей конструктор вызван при включенном режиме денормализации —
	 * то произойдёт сбой:
	 *
	 * SQLSTATE[23000]: Integrity constraint violation:
	 * 1452 Cannot add or update a child row:
	 * a foreign key constraint fails
	 * (`catalog_category_flat_store_1`,
	 * CONSTRAINT `FK_CAT_CTGR_FLAT_STORE_1_ENTT_ID_CAT_CTGR_ENTT_ENTT_ID`
	 * FOREIGN KEY (`entity_id`) REFERENCES `catalog_category_entity` (`en)
	 *
	 *
	 * @param array $data
	 * @param int $storeId
	 * @return Mage_Catalog_Model_Category
	 * @throws Exception
	 */
	public function createAndSave (array $data, $storeId) {

		/** @var Mage_Catalog_Model_Category $result  */
		$result = null;

		/** @var Mage_Core_Model_Store $currentStore */
		$currentStore = Mage::app()->getStore();

		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

		try {

			$result =
				df_model (
					Df_Catalog_Const::CATEGORY_CLASS_MF
					,
					$data
				)
			;

			df_assert ($result instanceof Mage_Catalog_Model_Category);

			$result->setStoreId ($storeId);

			$result->save ();
		}
		catch (Exception $e) {

			Mage::app()->setCurrentStore($currentStore);

			throw $e;
		}

		Mage::app()->setCurrentStore($currentStore);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Helper_Category';
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


