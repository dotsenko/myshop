<?php

class Df_1C_Model_Resource_Setup extends Mage_Catalog_Model_Resource_Eav_Mysql4_Setup {


	/**
	 * @return Df_1C_Model_Resource_Setup
	 */
	public function install_1_0_0 () {

		$this->add1CIdToEntity ('catalog_category', 'General Information');

		$this->add1CIdToEntity ('catalog_product');


		$this->add1CIdColumnToTable ('eav/attribute_option');

		$this->add1CIdColumnToTable ('catalog/eav_attribute');


		Mage::app()->getCache()->clean();

		return $this;

	}




	/**
	 * @return Df_1C_Model_Resource_Setup
	 */
	public function install_1_0_2 () {

		$this->add1CIdToEntity ('catalog_category', 'General Information');

		$this->add1CIdColumnToTable ('eav/attribute_option');

		$this->add1CIdColumnToTable ('catalog/eav_attribute');


		Mage::app()->getCache()->clean();

		return $this;

	}





	/**
	 * @override
	 * @return Mage_Core_Model_Resource_Setup
	 */
    public function startSetup() {

		parent::startSetup();

		Mage::getModel ('df/lib')->init ();
		Mage::getModel ('df_zf/lib')->init ();

		return $this;
    }




	/**
	 * Область действия внешнего идентификатора 1C сменена с витрины на глобальную
	 * (при витринной области действия модуль будет работать некорректно с неосновными витринами)
	 *
	 * @return Df_1C_Model_Resource_Setup
	 */
	public function upgrade_1_0_1 () {

		$this
			->addAttribute (
				'catalog_category'
				,
				Df_1C_Const::ENTITY_1C_ID
				,
				self::get1CIdProperties()
			)
		;


		$this
			->addAttribute (
				'catalog_product'
				,
				Df_1C_Const::ENTITY_1C_ID
				,
				self::get1CIdProperties()
			)
		;



		Mage::app()->getCache()->clean();

		return $this;

	}





	/**
	 * @param string $tablePlaceholder
	 * @return Df_1C_Model_Resource_Setup
	 */
	private function add1CIdColumnToTable ($tablePlaceholder) {

		df_param_string ($tablePlaceholder, 0);

		/** @var string $tableName  */
		$tableName = $this->getTable ($tablePlaceholder);


		/**
		 * Обратите внимание, что напрямую писать {Df_1C_Const::ENTITY_1C_ID} нельзя:
		 * интерпретатор PHP не разбирает константы внутри {}.
		 * Поэтому заводим переменную.
		 *
		 * @var string $columnName
		 */
		$columnName = Df_1C_Const::ENTITY_1C_ID;


		/**
		 * @var string $columnNameOld
		 */
		$columnNameOld = Df_1C_Const::ENTITY_1C_ID_OLD;


		$this->runSilent("
			ALTER TABLE {$tableName}
				DROP COLUMN `{$columnNameOld}`
			;
		");



		$this->runSilent("
			ALTER TABLE {$tableName}
				DROP COLUMN `{$columnName}`
			;
		");


		$this->runSilent("
			ALTER TABLE {$tableName}
				ADD COLUMN `{$columnName}`
					VARCHAR(255)
					DEFAULT NULL
			;
		");


		return $this;

	}





	/**
	 * @param string $entityType
	 * @param string|null $groupName [optional]
	 * @param int $ordering [optional]
	 * @return Df_1C_Model_Resource_Setup
	 */
	private function add1CIdToEntity (
		$entityType
		,
		$groupName = null
		,
		$ordering = 10
	) {

		df_param_string ($entityType, 0);


		if (is_null ($groupName)) {
			$groupName = $this->_generalGroupName;
		}

		df_param_string ($groupName, 1);


		df_param_integer ($ordering, 2);


		$this->cleanCache();


		if ($this->getAttributeId ($entityType, Df_1C_Const::ENTITY_1C_ID_OLD)) {
			$this->removeAttribute ($entityType, Df_1C_Const::ENTITY_1C_ID_OLD);
		}

		if ($this->getAttributeId ($entityType, Df_1C_Const::ENTITY_1C_ID)) {
			$this->removeAttribute ($entityType, Df_1C_Const::ENTITY_1C_ID);
		}

		if (!$this->getAttributeId ($entityType, Df_1C_Const::ENTITY_1C_ID)) {

			/** @var int $entityTypeId */
			$entityTypeId = $this->getEntityTypeId ($entityType);

			/** @var int $attributeSetId */
			$attributeSetId = $this->getDefaultAttributeSetId ($entityTypeId);


			$this
				->addAttribute (
					$entityType
					,
					Df_1C_Const::ENTITY_1C_ID
					,
					self::get1CIdProperties()
				)
			;


			$this
				->addAttributeToGroup (
					$entityTypeId

					,
					$attributeSetId

					/**
					 * Не используем синтаксис
					 * $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId)
					 *
					 * потому что он при предварительно включенной русификации
					 * может приводить к созданию дополнительной вкладки ("Основное")
					 * вместо размещения свойства на главной вкладке ("Главное").
					 */
					,
					$groupName

					,
					Df_1C_Const::ENTITY_1C_ID

					,
					$ordering
				)
			;

		}

		return $this;

	}





	/**
	 * @param string $tablePlaceholder
	 * @return Df_1C_Model_Resource_Setup
	 */
	private function remove1CIdColumnFromTable ($tablePlaceholder) {

		df_param_string ($tablePlaceholder, 0);

		/** @var string $tableName  */
		$tableName = $this->getTable ($tablePlaceholder);


		/**
		 * Обратите внимание, что напрямую писать {Df_1C_Const::ENTITY_1C_ID} нельзя:
		 * интерпретатор PHP не разбирает константы внутри {}.
		 * Поэтому заводим переменную.
		 *
		 * @var string $columnName
		 */
		$columnName = Df_1C_Const::ENTITY_1C_ID;


		$this->runSilent("
			ALTER TABLE {$tableName}
				DROP COLUMN `{$columnName}`
			;
		");


		return $this;

	}








	/**
	 * @param string $sql
	 * @return Df_1C_Model_Resource_Setup
	 */
	private function runSilent ($sql) {

		df_param_string ($sql, 0);

		try {
			$this->run ($sql);
		}
		catch (Exception $e) {
			/**
			 * Думаю, никакой обработки тут не требуется.
			 */
		}

		return $this;

	}





	/**
	 * @static
	 * @return array
	 */
	public static function get1CIdProperties () {

		/** @var array $result  */
		$result =
			array (
				'type'              => 'varchar',
				'backend'           => '',
				'frontend'          => '',
				'label'             => '1С ID',
				'input'             => 'text',
				'class'             => '',
				'source'            => '',
				'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
				'visible'           => true,
				'required'          => false,
				'user_defined'      => false,
				'default'           => '',
				'searchable'        => false,
				'filterable'        => false,
				'comparable'        => false,
				'visible_on_front'  => false,
				'unique'            => false
			)
		;

		df_result_array ($result);

		return $result;
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Resource_Setup';
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


