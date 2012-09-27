<?php

class Df_1C_Model_Cml2_Import_Processor_Category extends Df_1C_Model_Cml2_Import_Processor {



	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Import_Processor
	 */
	public function process () {

		/** @var Mage_Catalog_Model_Category $category  */
		$category =
			df_helper()->dataflow()->registry()->categories()->findByExternalId (
				$this->getEntity()->getExternalId()
			)
		;

		if (!is_null ($category)) {

			df_assert ($category instanceof Mage_Catalog_Model_Category);

		}

		else {

			$entityTypeId  =
				df_helper()->catalog()->getSetup()->getEntityTypeId(
					Mage_Catalog_Model_Category::ENTITY
				)
			;

			$attributeSetId =
				df_helper()->catalog()->getSetup()->getDefaultAttributeSetId(
					$entityTypeId
				)
			;

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
			 */
			$category =
				df_helper()->catalog()->category()->createAndSave (
					array (
						Df_Catalog_Const::CATEGORY_PARAM_PATH =>
							$this->getParent()->getDataUsingMethod (
								Df_Catalog_Const::CATEGORY_PARAM_PATH
							)
						,
						Df_Catalog_Const::CATEGORY_PARAM_NAME => $this->getEntity()->getName()
						,
						Df_Catalog_Const::CATEGORY_PARAM_IS_ACTIVE => 1
						,
						Df_Catalog_Const::CATEGORY_PARAM_IS_ANCHOR => 1
						,
						Df_Catalog_Const::CATEGORY_PARAM_DISPLAY_MODE =>
							Mage_Catalog_Model_Category::DM_MIXED
						,
						Df_1C_Const::ENTITY_1C_ID => $this->getEntity()->getExternalId()
						,
						'attribute_set_id' => $attributeSetId
						,
						'include_in_menu' => 1

					)
					,
					df_helper()->_1c()->cml2()->getStoreProcessed()->getId()
				)
			;

			df_assert ($category instanceof Mage_Catalog_Model_Category);


			df_helper()->dataflow()->registry()->categories()
				->addEntity (
					$category
				)
			;

			df_helper()->_1c()
				->log (
					sprintf (
						'Создан товарный раздел «%s».'
						,
						$category->getName()
					)
				)
			;

		}



		foreach ($this->getEntity()->getChildren() as $child) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity_Category $child */
			df_assert ($child instanceof Df_1C_Model_Cml2_Import_Data_Entity_Category);


			/** @var Df_1C_Model_Cml2_Import_Processor_Category $processor  */
			$processor =
				df_model (
					Df_1C_Model_Cml2_Import_Processor_Category::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Processor_Category::PARAM__PARENT => $category
						,
						Df_1C_Model_Cml2_Import_Processor_Category::PARAM__ENTITY => $child
					)
				)
			;

			df_assert ($processor instanceof Df_1C_Model_Cml2_Import_Processor_Category);


			$processor->process();

		}


		return $this;

	}





	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Import_Data_Entity_Category|Df_1C_Model_Cml2_Import_Data_Entity
	 */
	protected function getEntity () {

		/** @var Df_1C_Model_Cml2_Import_Data_Entity_Category $result */
		$result = parent::getEntity();

		df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Entity_Category);

		return $result;
	}





	/**
	 * @return Mage_Catalog_Model_Category
	 */
	private function getParent () {
		return $this->cfg (self::PARAM__PARENT);
	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__PARENT, 'Mage_Catalog_Model_Category'
			)
		;
	}



	const PARAM__PARENT = 'parent';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Processor_Category';
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


