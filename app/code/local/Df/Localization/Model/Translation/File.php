<?php


class Df_Localization_Model_Translation_File extends Df_Core_Model_Abstract {
	
	
	
	
	/**
	 * @return bool
	 */
	public function isFullyTranslated () {
	
		if (!isset ($this->_fullyTranslated)) {
	
			/** @var bool $result  */
			$result =
					0
				===
					(
							$this->getNumAbsentEntries()
						+
							$this->getNumUntranslatedEntries()
					)
			;
	
	
			df_assert_boolean ($result);
	
			$this->_fullyTranslated = $result;
	
		}
	
	
		df_result_boolean ($this->_fullyTranslated);
	
		return $this->_fullyTranslated;
	
	}
	
	
	/**
	* @var bool
	*/
	private $_fullyTranslated;		
	
	
	
	
	
	
	/**
	 * @return string
	 */
	public function getName () {
	
		if (!isset ($this->_name)) {
	
			/** @var string $result  */
			$result = 
				basename ($this->getPath ())
			;
	
	
			df_assert_string ($result);
	
			$this->_name = $result;
	
		}
	
	
		df_result_string ($this->_name);
	
		return $this->_name;
	
	}
	
	
	/**
	* @var string
	*/
	private $_name;






	/**
	 * Для коллекций
	 *
	 * @return string
	 */
	public function getId () {

		/** @var string $result */
		$result = $this->getName ();

		df_result_string ($result);

		return $result;
	}





	/**
	 * @return int
	 */
	public function getNumEntries () {

		if (!isset ($this->_numEntries)) {

			/** @var int $result  */
			$result =
				count ($this->getEntries())
			;


			df_assert_integer ($result);

			$this->_numEntries = $result;

		}


		df_result_integer ($this->_numEntries);

		return $this->_numEntries;

	}


	/**
	* @var int
	*/
	private $_numEntries;
	
	
	
	
	
	/**
	 * @return array
	 */
	public function getUntranslatedEntries () {
	
		if (!isset ($this->_untranslatedEntries)) {
	
			/** @var array $result  */
			$result = array ();


			foreach ($this->getEntries() as $entryKey => $entryValue) {

				/** @var string $entryKey */
				df_assert_string ($entryKey);

				/**
				 * $entryValue будет равно null,
				 * если в языковом файле присутствует ключ,
				 * но отсутствует запятая-разделитель и значение.
				 *
				 * @var string|null $entryValue
				 */
				if (is_null($entryValue)) {
					$result []= $entryKey;
				}
				else {
					df_assert_string ($entryValue);

					/** @var string|null $translatedValue  */
					$translatedValue =
							is_null ($this->getTranslatedFile())
						?
							null
						:
							df_a ($this->getTranslatedFile()->getEntries(), $entryKey)
					;

					if (!is_null ($translatedValue)) {
						df_assert_string ($translatedValue);
					}

					if ($translatedValue === $entryValue) {
						$result []= $entryKey;
					}
				}

			}
	
	
			df_assert_array ($result);
	
			$this->_untranslatedEntries = $result;
	
		}
	
	
		df_result_array ($this->_untranslatedEntries);
	
		return $this->_untranslatedEntries;
	
	}
	
	
	/**
	* @var array
	*/
	private $_untranslatedEntries;	






	/**
	 * @return int
	 */
	public function getNumUntranslatedEntries () {

		if (!isset ($this->_numUntranslatedEntries)) {

			/** @var int $result  */
			$result =
				count ($this->getUntranslatedEntries ())
			;

			df_assert_integer ($result);

			$this->_numUntranslatedEntries = $result;

		}


		df_result_integer ($this->_numUntranslatedEntries);

		return $this->_numUntranslatedEntries;

	}


	/**
	* @var int
	*/
	private $_numUntranslatedEntries;







	/**
	 * @return array
	 */
	public function getAbsentEntries () {

		if (!isset ($this->_absentEntries)) {

			/** @var array $result  */
			$result =
				array_keys (
						is_null ($this->getTranslatedFile())
					?
						$this->getEntries()
					:
						array_diff_key (
							$this->getEntries()
							,
							$this->getTranslatedFile()->getEntries()
						)
				)
			;


			df_assert_array ($result);

			$this->_absentEntries = $result;

		}


		df_result_array ($this->_absentEntries);

		return $this->_absentEntries;

	}


	/**
	* @var array
	*/
	private $_absentEntries;
	
	
	
	
	
	
	/**
	 * @return int
	 */
	public function getNumAbsentEntries () {
	
		if (!isset ($this->_numAbsentEntries)) {
	
			/** @var int $result  */
			$result =
				count ($this->getAbsentEntries())
			;
	
	
			df_assert_integer ($result);
	
			$this->_numAbsentEntries = $result;
	
		}
	
	
		df_result_integer ($this->_numAbsentEntries);
	
		return $this->_numAbsentEntries;
	
	}
	
	
	/**
	* @var int
	*/
	private $_numAbsentEntries;
	






	/**
	 * @return array
	 */
	public function getEntries () {

		if (!isset ($this->_entries)) {

			df_assert (file_exists ($this->getPath()));


			/** @var Varien_File_Csv $parser */
            $parser = new Varien_File_Csv ();

			df_assert ($parser instanceof Varien_File_Csv);


            $parser->setDelimiter(Mage_Core_Model_Translate::CSV_SEPARATOR);


			/** @var array $result  */
            $result = $parser->getDataPairs ($this->getPath());


			df_assert_array ($result);

			$this->_entries = $result;

		}


		df_result_array ($this->_entries);

		return $this->_entries;

	}


	/**
	* @var array
	*/
	private $_entries;
	
	
	
	
	
	/**
	 * @return Df_Localization_Model_Translation_File|null
	 */
	private function getTranslatedFile () {
	
		if (!isset ($this->_translatedFile) && !$this->_translatedFileIsNull) {
	
			/** @var Df_Localization_Model_Translation_File|null $result  */
			$result = 
				$this->getTranslatedFiles ()->getItemById (
					$this->getName ()
				)
			;
	
	
			if (!is_null ($result)) {
				df_assert ($result instanceof Df_Localization_Model_Translation_File);				
			}
			else {
				$this->_translatedFileIsNull = true;
			}
	
			$this->_translatedFile = $result;
	
		}
	
	
		if (!is_null ($this->_translatedFile)) {
			df_assert ($this->_translatedFile instanceof Df_Localization_Model_Translation_File);				
		}		
		
	
		return $this->_translatedFile;
	
	}
	
	
	/**
	* @var Df_Localization_Model_Translation_File|null
	*/
	private $_translatedFile;	
	
	/**
	 * @var bool
	 */
	private $_translatedFileIsNull = false;
	






	/**
	 * @return Df_Localization_Model_Translation_File_Collection
	 */
	private function getTranslatedFiles () {

		/** @var Df_Localization_Model_Translation_File_Collection $result  */
		$result =
			df_helper()->localization()->translation()->getRussianFileStorage()->getFiles()
		;

		df_assert ($result instanceof Df_Localization_Model_Translation_File_Collection);

		return $result;

	}
	
	




	/**
	 * @return string
	 */
	private function getPath () {

		/** @var string $result  */
		$result =
			$this->cfg (self::PARAM__PATH)
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__PATH, new Df_Zf_Validate_String ()
			)
		;
	}



	const PARAM__PATH = 'path';


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Localization_Model_Translation_File';
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


