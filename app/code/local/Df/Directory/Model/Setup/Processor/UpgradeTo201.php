<?php


class Df_Directory_Model_Setup_Processor_UpgradeTo201 extends Df_Core_Model_Abstract {



	/**
	 * @return Df_Directory_Model_Setup_Processor_UpgradeTo201
	 */
	public function process () {

		foreach ($this->getInstaller()->getRussianRegionsFromLegacyModules () as $region) {

			/** @var Mage_Directory_Model_Region $region  */
			df_assert ($region instanceof Mage_Directory_Model_Region);


			/** @var string $oldCode  */
			$oldCode = $region->getData ('default_name');

			df_assert_string ($oldCode);


			/** @var string|null $newCode  */
			$newCode =
				df_a ($this->getMap(), $oldCode)
			;

			if (is_null ($newCode)) {
				Mage
					::log (
						sprintf (
							'Не могу найти новый код для старого кода %s'
							,
							$oldCode
						)
					)
				;
			}
			else {
				$region
					->setData (
						'code'
						,
						$newCode
					)
				;

				$region->save ();
			}


		}

	}




	
	/**
	 * @return array
	 */
	private function getMap () {
	
		if (!isset ($this->_map)) {
	
			/** @var array $result  */
			$result =
				array (
					'Алтайский' => '22'
					,
					'Амурская' => '28'
					,
					'Архангельская' => '29'
					,
					'Астраханская' => '30'
					,
					'Белгородская' => '31'
					,
					'Брянская' => '32'
					,
					'Владимирская' => '33'
					,
					'Волгоградская' => '34'
					,
					'Вологодская' => '35'
					,
					'Воронежская' => '36'
					,
					'Еврейская' => '79'
					,
					'Забайкальский' => '75'
					,
					'Ивановская' => '37'
					,
					'Иркутская' => '38'
					,
					'Кабардино-Балкарская' => '07'
					,
					'Калининградская' => '39'
					,
					'Калужская' => '40'
					,
					'Камчатский' => '41'
					,
					'Карачаево-Черкесская' => '09'
					,
					'Кемеровская' => '42'
					,
					'Кировская' => '43'
					,
					'Костромская' => '44'
					,
					'Краснодарский' => '23'
					,
					'Красноярский' => '24'
					,
					'Курганская' => '45'
					,
					'Курская' => '46'
					,
					'Ленинградская' => '47'
					,
					'Липецкая' => '48'
					,
					'Магаданская' => '49'
					,
					'Москва' => '77'
					,
					'Московская' => '50'
					,
					'Мурманская' => '51'
					,
					'Ненецкий' => '83'
					,
					'Нижегородская' => '52'
					,
					'Новгородская' => '53'
					,
					'Новосибирская' => '54'
					,
					'Омская' => '55'
					,
					'Оренбургская' => '56'
					,
					'Орловская' => '57'
					,
					'Пензенская' => '58'
					,
					'Пермский' => '59'
					,
					'Приморский' => '25'
					,
					'Псковская' => '60'
					,
					'Адыгея' => '01'
					,
					'Алтай' => '04'
					,
					'Башкортостан' => '02'
					,
					'Бурятия' => '03'
					,
					'Дагестан' => '05'
					,
					'Ингушетия' => '06'
					,
					'Калмыкия' => '08'
					,
					'Карелия' => '10'
					,
					'Коми' => '11'
					,
					'Марий Эл' => '12'
					,
					'Мордовия' => '13'
					,
					'Саха (Якутия)' => '14'
					,
					'Северная Осетия — Алания' => '15'
					,
					'Татарстан' => '16'
					,
					'Тыва (Тува)' => '17'
					,
					'Хакасия' => '19'
					,
					'Ростовская' => '61'
					,
					'Рязанская' => '62'
					,
					'Самарская' => '63'
					,
					'Санкт-Петербург' => '78'
					,
					'Саратовская' => '64'
					,
					'Сахалинская' => '65'
					,
					'Свердловская' => '66'
					,
					'Смоленская' => '67'
					,
					'Ставропольский' => '26'
					,
					'Тамбовская' => '68'
					,
					'Тверская' => '69'
					,
					'Томская' => '70'
					,
					'Тульская' => '71'
					,
					'Тюменская' => '72'
					,
					'Удмуртская' => '18'
					,
					'Ульяновская' => '73'
					,
					'Хабаровский' => '27'
					,
					'Ханты-Мансийский' => '86'
					,
					'Челябинская' => '74'
					,
					'Чеченская' => '20'
					,
					'Чувашская' => '21'
					,
					'Чукотский' => '87'
					,
					'Ямало-Ненецкий' => '89'
					,
					'Ярославская' => '76'
				)
			;
	
	
			df_assert_array ($result);
	
			$this->_map = $result;
	
		}
	
	
		df_result_array ($this->_map);
	
		return $this->_map;
	
	}
	
	
	/**
	* @var array
	*/
	private $_map;
	





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
				self::PARAM__INSTALLER, Df_Directory_Model_Resource_Setup::getClass()
			)
		;
	}



	const PARAM__INSTALLER = 'installer';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Model_Setup_Processor_UpgradeTo201';
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

