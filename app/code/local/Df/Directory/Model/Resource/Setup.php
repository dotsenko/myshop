<?php


class Df_Directory_Model_Resource_Setup extends Mage_Core_Model_Resource_Setup {



	/**
	 * @override
	 * @return Df_Directory_Model_Resource_Setup
	 */
    public function startSetup() {

		parent::startSetup();

		Mage::getModel ('df/lib')->init ();
		Mage::getModel ('df_zf/lib')->init ();

		return $this;
    }




	/**
	 * @return Df_Directory_Model_Resource_Setup
	 */
	public function install_2_0_0 () {

		try {

			df_notify_me ('Российская сборка Magento установлена');


			/**
			 * Обратите внимание, что нам не страшно, если колонки df_type и df_capital
			 * уже присутствуют в таблице directory_country_region:
			 * исключительную ситуацию мы тут же гасим.
			 */

			$this->run ("

				ALTER TABLE {$this->getTableCountryRegion ()}
					ADD COLUMN `df_type`
						INT(4)
						DEFAULT NULL
					,
					ADD COLUMN `df_capital`
						VARCHAR(255)
						CHARACTER SET utf8
						DEFAULT NULL
				;

			");

		}
		catch (Exception $e) {

			/**
			 * Думаю, никакой обработки тут не требуется.
			 */

		}

		Mage::app()->getCache()->clean();

		return $this;

	}



	/**
	 * @return Df_Directory_Model_Resource_Setup
	 */
	public function installData_2_0_0 () {

		Mage::app()->getCache()->clean();

		$this->writeRussianRegionsToDb();

		return $this;

	}




	/**
	 * @return Df_Directory_Model_Resource_Setup
	 */
	public function upgradeDataTo_2_0_1 () {

		/**
		 * У нас могли быть проблемы с первичной установкой.
		 * Поэтому сначала убеждаемся, что установка 2.0.0 прошла успешно
		 */

		/** @var bool $isInstalledCorrectly  */
		$isInstalledCorrectly = true;

		/**
		 * Очистка кеша необходима, потому что перед обновлением могла произойти первичная установка,
		 * и колеллекция прежних субъектов РФ будет инициализирована, но пуста
		 */
		unset ($this->_russianRegionsFromLegacyModules);

		if (0 === count ($this->getRussianRegionsFromLegacyModules ())) {

			df_log ('Система не нашла в базе данных субъекты РФ, поэтому она выполняет их установку заново');

			$isInstalledCorrectly = false;
		}
		else {

			/** @var Mage_Directory_Model_Region $testRegion  */
			$testRegion = df_model (Df_Directory_Const::REGION_CLASS_MF);

			df_assert ($testRegion instanceof Mage_Directory_Model_Region);


			$testRegion->loadByCode ('79', 'RU');

			if ('Адыгея' !== $testRegion->getDefaultName()) {
				$isInstalledCorrectly = false;
			}

		}


		if (!$isInstalledCorrectly) {
			$this->install_2_0_0();
			$this->installData_2_0_0();
		}


		/** @var Df_Directory_Model_Setup_Processor_UpgradeTo201 $processor  */
		$processor =
			df_model (
				Df_Directory_Model_Setup_Processor_UpgradeTo201::getNameInMagentoFormat()
				,
				array (
					Df_Directory_Model_Setup_Processor_UpgradeTo201::PARAM__INSTALLER => $this
				)
			)
		;

		df_assert ($processor instanceof Df_Directory_Model_Setup_Processor_UpgradeTo201);

		$processor->process();

		return $this;

	}







	/**
	 * @return Df_Directory_Model_Resource_Setup
	 */
	public function writeRussianRegionsToDb () {


		foreach ($this->getRussianRegions() as $russianRegion) {

			/** @var Df_Directory_Model_Setup_Entity_Region $russianRegion */
			df_assert ($russianRegion instanceof Df_Directory_Model_Setup_Entity_Region);


			/** @var Df_Directory_Model_Setup_Processor_Region $processor  */
			$processor =
				df_model (
					Df_Directory_Model_Setup_Processor_Region::getNameInMagentoFormat()
					,
					array (
						Df_Directory_Model_Setup_Processor_Region::PARAM__REGION =>	$russianRegion
						,
						Df_Directory_Model_Setup_Processor_Region::PARAM__INSTALLER => $this
					)
				)
			;

			df_assert ($processor instanceof Df_Directory_Model_Setup_Processor_Region);


			$processor->process();

		}

		return $this;

	}




    /**
     * Get connection object
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function getConnection() {

		/** @var Zend_Db_Adapter_Abstract $result */
		$result = parent::getConnection ();

        return $result;
    }





	/**
	 * @return string
	 */
	public function getTableCountryRegion () {

		/** @var string $result  */
		$result =
			$this->getTable (
				Df_Directory_Const::TABLE__COUNTRY_REGION
			)
		;

		df_result_string ($result);

		return $result;
	}




	/**
	 * @return string
	 */
	public function getTableCountryRegionName () {

		/** @var string $result  */
		$result =
			$this->getTable (
				Df_Directory_Const::TABLE__COUNTRY_REGION_NAME
			)
		;

		df_result_string ($result);

		return $result;
	}




	/**
	 * @param string $namePart
	 * @return Mage_Directory_Model_Region|null
	 */
	public function getLegacyRussianRegionByNamePart ($namePart) {

		/** @var Mage_Directory_Model_Region $result  */
		$result =
			null
		;


		$mapFromMyToCifrum =
			array (
				'Северная Осетия — Алания' => 'Сев.Осетия-Алания'
				,
				'Тыва (Тува)' => 'Тыва'
				,
				'Алтай' => 'Республика Алтай'
				,
				'Алтайский' => 'Алтайский край'
				,
				'Омская' => 'Омская область'
			)
		;

		if (!is_null (df_a ($mapFromMyToCifrum, $namePart))) {
			$namePart = df_a ($mapFromMyToCifrum, $namePart);
		}

		foreach ($this->getRussianRegionsFromLegacyModules () as $region) {

			/** @var Mage_Directory_Model_Region $region */
			df_assert ($region instanceof Mage_Directory_Model_Region);


			if (false !== mb_stripos ($region->getDefaultName(), $namePart)) {
				$result = $region;
				break;
			}

			if ($result) {
				break;
			}
		}


		if (!is_null ($result)) {
			df_assert ($result instanceof Mage_Directory_Model_Region);
		}

		return $result;

	}


	
	
	/**
	 * @return Mage_Directory_Model_Resource_Region_Collection|Mage_Directory_Model_Mysql4_Region_Collection
	 */
	public function getRussianRegionsFromLegacyModules () {
	
		if (!isset ($this->_russianRegionsFromLegacyModules)) {

			/** @var Mage_Directory_Model_Resource_Region_Collection|Mage_Directory_Model_Mysql4_Region_Collection $result  */
			$result = 
				df_helper()->directory()->country()->getRussia()->getRegions()
			;

			df_helper()->directory()->assert()->regionCollection ($result);
	
			$this->_russianRegionsFromLegacyModules = $result;
	
		}


		df_helper()->directory()->assert()->regionCollection ($this->_russianRegionsFromLegacyModules);

	
		return $this->_russianRegionsFromLegacyModules;
	
	}
	
	
	/**
	* @var Mage_Directory_Model_Resource_Region_Collection|Mage_Directory_Model_Mysql4_Region_Collection
	*/
	private $_russianRegionsFromLegacyModules;	
	
	
	



	/**
	 * @return array
	 */
	public function getRussianRegions () {
	
		if (!isset ($this->_russianRegions)) {
	
			/** @var $dtoRegions $result  */
			$dtoRegions =
				array (
					array ('Адыгея','Майкоп',79,1)
					,
					array ('Алтай','Горно-Алтайск',84,1)
					,
					array ('Алтайский','Барнаул','01',2)
					,
					array ('Амурская','Благовещенск',10,3)
					,
					array ('Архангельская','Архангельск',11,3)
					,
					array ('Астраханская','Астрахань',12,3)
					,
					array ('Башкортостан','Уфа',80,1)
					,
					array ('Белгородская','Белгород',14,3)
					,
					array ('Брянская','Брянск',15,3)
					,
					array ('Бурятия','Улан-Удэ',81,1)
					,
					array ('Владимирская','Владимир',17,3)
					,
					array ('Волгоградская','Волгоград',18,3)
					,
					array ('Вологодская','Вологда',19,3)
					,
					array ('Воронежская','Воронеж',20,3)
					,
					array ('Дагестан','Махачкала',82,1)
					,
					array ('Еврейская','Биробиджан',99,5)
					,
					array ('Забайкальский','Чита',76,2)
					,
					array ('Ивановская','Иваново',24,3)
					,
					array ('Ингушетия','Магас',26,1)
					,
					array ('Иркутская','Иркутск',25,3)
					,
					array ('Кабардино-Балкарская','Нальчик',83,1)
					,
					array ('Калининградская','Калининград',27,3)
					,
					array ('Калмыкия','Элиста',85,1)
					,
					array ('Калужская','Калуга',29,3)
					,
					array ('Камчатский','Петропавловск-Камчатский',30,2)
					,
					array ('Карачаево-Черкесская','Черкесск',91,1)
					,
					array ('Карелия','Петрозаводск',86,1)
					,
					array ('Кемеровская','Кемерово',32,3)
					,
					array ('Кировская','Киров',33,3)
					,
					array ('Коми','Сыктывкар',87,1)
					,
					array ('Костромская','Кострома',34,3)
					,
					array ('Краснодарский','Краснодар','03',2)
					,
					array ('Красноярский','Красноярск','04',2)
					,
					array ('Курганская','Курган',37,3)
					,
					array ('Курская','Курск',38,3)
					,
					array ('Ленинградская',null,41,3)
					,
					array ('Липецкая','Липецк',42,3)
					,
					array ('Магаданская','Магадан',44,3)
					,
					array ('Марий Эл','Йошкар-Ола',88,1)
					,
					array ('Мордовия','Саранск',89,1)
					,
					array ('Москва','Москва',45,4)
					,
					array ('Московская',null,46,3)
					,
					array ('Мурманская','Мурманск',47,3)
					,
					array ('Ненецкий','Нарьян-Мар',11,6)
					,
					array ('Нижегородская','Нижний Новгород',22,3)
					,
					array ('Новгородская','Великий Новгород',49,3)
					,
					array ('Новосибирская','Новосибирск',50,3)
					,
					array ('Омская','Омск',52,3)
					,
					array ('Оренбургская','Оренбург',53,3)
					,
					array ('Орловская','Орёл',54,3)
					,
					array ('Пензенская','Пенза',56,3)
					,
					array ('Пермский','Пермь',57,2)
					,
					array ('Приморский','Владивосток','05',2)
					,
					array ('Псковская','Псков',58,3)
					,
					array ('Ростовская','Ростов-на-Дону',60,3)
					,
					array ('Рязанская','Рязань',61,3)
					,
					array ('Самарская','Самара',36,3)
					,
					array ('Санкт-Петербург','Санкт-Петербург',40,4)
					,
					array ('Саратовская','Саратов',63,3)
					,
					array ('Саха (Якутия)','Якутск',98,1)
					,
					array ('Сахалинская','Южно-Сахалинск',64,3)
					,
					array ('Свердловская','Екатеринбург',65,3)
					,
					array ('Северная Осетия — Алания','Владикавказ',90,1)
					,
					array ('Смоленская','Смоленск',66,3)
					,
					array ('Ставропольский','Ставрополь','07',2)
					,
					array ('Тамбовская','Тамбов',68,3)
					,
					array ('Татарстан','Казань',92,1)
					,
					array ('Тверская','Тверь',28,3)
					,
					array ('Томская','Томск',69,3)
					,
					array ('Тульская','Тула',70,3)
					,
					array ('Тыва (Тува)','Кызыл',93,1)
					,
					array ('Тюменская','Тюмень',71,3)
					,
					array ('Удмуртская','Ижевск',94,1)
					,
					array ('Ульяновская','Ульяновск',73,3)
					,
					array ('Хабаровский','Хабаровск','08',2)
					,
					array ('Хакасия','Абакан',95,1)
					,
					array ('Ханты-Мансийский','Ханты-Мансийск',71,6)
					,
					array ('Челябинская','Челябинск',75,3)
					,
					array ('Чеченская','Грозный',96,1)
					,
					array ('Чувашская','Чебоксары',97,1)
					,
					array ('Чукотский','Анадырь',77,6)
					,
					array ('Ямало-Ненецкий','Салехард',71,6)
					,
					array ('Ярославская','Ярославль',78,3)
				)
			;


			$result = array ();

			foreach ($dtoRegions as $dtoRegion) {

				/** @var array $dtoRegion */
				df_assert_array ($dtoRegion);

				/** @var Df_Directory_Model_Setup_Entity_Region $region  */
				$region =
					df_model (
						Df_Directory_Model_Setup_Entity_Region::getNameInMagentoFormat()
						,
						array (
							Df_Directory_Model_Setup_Entity_Region::PARAM__CAPITAL =>
								df_string (df_a ($dtoRegion, self::DTO_INDEX__CAPITAL))
							,
							Df_Directory_Model_Setup_Entity_Region::PARAM__NAME =>
								df_a ($dtoRegion, self::DTO_INDEX__NAME)
							,
							Df_Directory_Model_Setup_Entity_Region::PARAM__CODE =>
								df_string (df_a ($dtoRegion, self::DTO_INDEX__CODE))
							,
							Df_Directory_Model_Setup_Entity_Region::PARAM__TYPE =>
								df_a ($dtoRegion, self::DTO_INDEX__TYPE)
						)
					)
				;

				df_assert ($region instanceof Df_Directory_Model_Setup_Entity_Region);


				$result []= $region;
			}
	
	
			df_assert_array ($result);
	
			$this->_russianRegions = $result;
	
		}
	
	
		df_result_array ($this->_russianRegions);
	
		return $this->_russianRegions;
	
	}
	
	
	/**
	* @var array
	*/
	private $_russianRegions;	
	



	const DTO_INDEX__NAME = 0;
	const DTO_INDEX__CAPITAL = 1;
	const DTO_INDEX__CODE = 2;
	const DTO_INDEX__TYPE = 3;




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Model_Resource_Setup';
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


