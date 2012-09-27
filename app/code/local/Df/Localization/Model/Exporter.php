<?php


class Df_Localization_Model_Exporter extends Df_Core_Model_Abstract {



	/**
	 * @return Df_Localization_Model_Exporter
	 */
	public function process () {
		$this
			->writeTranslationToFiles (
				$this->getTranslationByModules ()
			)
		;
		return $this;
	}


	/**
	 * @param array $translation
	 * @return Df_Localization_Model_Exporter
	 */
	private function writeTranslationToFiles (array $translation) {
		array_map (
			array ($this, "writeTranslationToFile")
			,
			array_keys ($translation)
			,
			array_values ($translation)
		)
		;
		return $this;
	}



	/**
	 * @param string $moduleName
	 * @param array $translation
	 * @return Df_Localization_Model_Exporter
	 */
	public function writeTranslationToFile ($moduleName, array $translation) {

		$translation =
			$this->correctTranslationWithCsvFileData ($moduleName, $translation)
		;

		$targetDir = Mage::getBaseDir('var') . '/translations/' . $this->getCoreTranslate()->getLocale();
		if (!file_exists($targetDir)) {
			if (!mkdir($targetDir, null, true)) {
				throw new Exception("Cannot create $targetDir");
			}
		}
		$targetFile = $targetDir .'/'. $moduleName . ".csv";
		$parser = new Varien_File_Csv();
		$csvdata = array();
		foreach ($translation as $key => $val)
			$csvdata[] = array($key, $val)
		;
		$parser->saveData($targetFile, $csvdata);

		return $this;
	}



	/**
	 * @param string $module
	 * @param array $translation
	 * @return array
	 */
	private function correctTranslationWithCsvFileData ($module, array $translation) {
		return
			array_merge (
				$translation
				,
				$this->getTranslationFromCsv ($module)
			)
		;
	}



	/**
	 * @param  string $module
	 * @return array
	 */
	private function getTranslationFromCsv ($module) {
		$result = array ();
		$filePath = Mage::getBaseDir('locale') . DS . $this->getCoreTranslate ()->getLocale() . DS . $module . ".csv";
		if (file_exists($filePath)) {
			$parser = new Varien_File_Csv();
			$parser->setDelimiter(Mage_Core_Model_Translate::CSV_SEPARATOR);
			$result = $parser->getDataPairs($filePath);
		}
		return $result;
	}




	/**
	 * @var array
	 */
	private $_translationByModules;


	/**
	 * @return array
	 */
	private function getTranslationByModules () {
		if (!isset ($this->_translationByModules)) {
			$this->_translationByModules =
				array_map (
					array ($this, "addTranslationStringToModule")
					,
					array_map (
						array ($this, "convertSourceKeyToObject")
						,
						array_keys ($this->getDb ()->getItems())
					)
					,
					array_values ($this->getDb ()->getItems())
				)
			;
		}
		return $this->_translationByModules;
	}



	/**
	 * @param  string $sourceKey
	 * @return Df_Localization_Model_Translation_Db_Source_Key
	 */
	public function convertSourceKeyToObject ($sourceKey) {
		return
			df_model (
				Df_Localization_Model_Translation_Db_Source_Key::getNameInMagentoFormat()
				,
				array (Df_Localization_Model_Translation_Db_Source_Key::PARAM_KEY => $sourceKey
				)
			)
		;
	}



	/**
	 * @param  Df_Localization_Model_Translation_Db_Source_Key $sourceKey
	 * @param  string $stringInTargetLanguage
	 * @return Df_Localization_Model_Exporter
	 */
	public function addTranslationStringToModule (
		Df_Localization_Model_Translation_Db_Source_Key $sourceKey, $stringInTargetLanguage
	) {
		if (!isset ($this->_translationByModules[$sourceKey->getModule()])) {
			$this->_translationByModules[$sourceKey->getModule()] = array ();
		}
		$this->_translationByModules
				[$sourceKey->getModule()]
				[$sourceKey->getString()]
			=
				$stringInTargetLanguage
		;
		return $this;
	}


	/**
	 * @return Df_Localization_Model_Translation_Db
	 */
	private function getDb () {
		return Mage::getSingleton('df_localization/translation_db');
	}


	/**
	 * @return Mage_Core_Model_Translate
	 */
	private function getCoreTranslate () {
		return Mage::getSingleton('core/translate');
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Localization_Model_Exporter';
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