<?php


class Df_Directory_Model_Setup_Processor_Region extends Df_Core_Model_Abstract {



	/**
	 * @return Df_Directory_Model_Setup_Processor_Region
	 */
	public function process () {


		/** @var Mage_Directory_Model_Region $region */
		$region =
			$this->getInstaller()->getLegacyRussianRegionByNamePart (
				$this->getRegion()->getName()
			)
		;

		if (is_null ($region)) {

			$region =
				df_model (
					Df_Directory_Const::REGION_CLASS_MF
				)
			;

			df_assert ($region instanceof Mage_Directory_Model_Region);

		}



		$region
			->addData (
				array (
					Df_Directory_Const::FIELD__COUNTRY_REGION__COUNTRY_ID =>
						Df_Directory_Helper_Country::ISO_2_CODE__RUSSIA
					,
					Df_Directory_Const::FIELD__COUNTRY_REGION__CODE => $this->getRegion()->getCode()
					,
					Df_Directory_Const::FIELD__COUNTRY_REGION__DEFAULT_NAME => $this->getRegion()->getName()
					,
					Df_Directory_Const::FIELD__COUNTRY_REGION__DF_CAPITAL => $this->getRegion()->getCapital()
					,
					Df_Directory_Const::FIELD__COUNTRY_REGION__DF_TYPE => $this->getRegion()->getType()
				)
			)
		;


		$region->save ();


		df_assert_between (intval ($region->getId ()), 1);


		$this
			//->addRegionLocaleNameToDb ($region, Mage_Core_Model_Locale::DEFAULT_LOCALE)
			->addRegionLocaleNameToDb ($region, Df_Core_Const::LOCALE__RUSSIAN)
		;


		return $this;

	}





	/**
	 * @param Mage_Directory_Model_Region $region
	 * @param string $localeCode
	 * @return Df_Directory_Model_Setup_Processor_Region
	 */
	private function addRegionLocaleNameToDb (Mage_Directory_Model_Region $region, $localeCode) {

		df_param_string ($localeCode, 1);


		/** @var Mage_Core_Model_Resource $resource */
		$resource = Mage::getModel ('core/resource');

		df_assert ($resource instanceof Mage_Core_Model_Resource);



		/** @var Varien_Db_Adapter_Pdo_Mysql $readConnection  */
		$readConnection = $resource->getConnection ('read');




		/**
		 * В Magento ранее версии 1.6 отсутствует интерфейс Varien_Db_Adapter_Interface,
		 * поэтому проводим грубую проверку на класс Varien_Db_Adapter_Pdo_Mysql
		 */
		df_assert ($readConnection instanceof Varien_Db_Adapter_Pdo_Mysql);




		/** @var Zend_Db_Select $select  */
		$select =
			$readConnection->select()
		;

		df_assert ($select instanceof Zend_Db_Select);


		$select
			->from (
				array (
					'maintable' => $this->getInstaller()->getTableCountryRegionName()
				)
			)
			->where (
				'(? = maintable.locale)'
				,
				$localeCode
			)
			->where (
				'(? = maintable.region_id)'
				,
				$region->getId ()
			)
		;



		/** @var Zend_Db_Statement_Pdo $query */
		$query =
			$readConnection
				->query (
					$select
				)
		;


		df_assert ($query instanceof Zend_Db_Statement_Pdo);


		/** @var bool $result  */
		$isNew =
			(false === $query->fetch ());
		;

		df_assert_boolean ($isNew);



		/** @var array $dto */
		$dto =
			array (
				Df_Directory_Const::FIELD__COUNTRY_REGION_NAME__LOCALE =>
					$localeCode
				,
				Df_Directory_Const::FIELD__COUNTRY_REGION_NAME__REGION_ID =>
					$region->getId ()
				,
				Df_Directory_Const::FIELD__COUNTRY_REGION_NAME__NAME =>
					$this->getRegion()->getName()
			)
		;


		if ($isNew) {
			$this->getInstaller()->getConnection()
				->insert(
					$this->getInstaller()->getTableCountryRegionName ()
					,
					$dto
				)
			;
		}
		else {
			$this->getInstaller()->getConnection()
				->update (
					$this->getInstaller()->getTableCountryRegionName ()
					,
					array (
						Df_Directory_Const::FIELD__COUNTRY_REGION_NAME__NAME =>
							$this->getRegion()->getName()
					)
					,
					array (
						'? = locale' => $localeCode
						,
						'? = region_id' => $region->getId ()
					)
				)
			;
		}




		return $this;

	}

	
	
	
	
	
	/**
	 * @return Df_Directory_Model_Setup_Entity_Region
	 */
	private function getRegion () {

		/** @var Df_Directory_Model_Setup_Entity_Region $result  */
		$result =
			$this->cfg (self::PARAM__REGION)
		;

		df_assert ($result instanceof Df_Directory_Model_Setup_Entity_Region);

		return $result;

	}





	/**
	 * @return Df_Directory_Model_Resource_Setup
	 */
	private function getInstaller () {

		/** @var Df_Directory_Model_Resource_Setup $result  */
		$result =
			$this->cfg (self::PARAM__INSTALLER)
		;

		df_assert ($result instanceof Df_Directory_Model_Resource_Setup);

		return $result;

	}







	
	
	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__REGION, Df_Directory_Model_Setup_Entity_Region::getClass()
			)
			->validateClass (
				self::PARAM__INSTALLER, Df_Directory_Model_Resource_Setup::getClass()
			)
		;
	}	
	
	
	
	const PARAM__REGION = 'region';
	const PARAM__INSTALLER = 'installer';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Model_Setup_Processor_Region';
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

