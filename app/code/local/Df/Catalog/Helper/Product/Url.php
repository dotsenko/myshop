<?php

class Df_Catalog_Helper_Product_Url extends Mage_Catalog_Helper_Product_Url
{

	/**
	 * @override
	 */
	public function __construct() {
	    if (
				df_enabled (Df_Core_Feature::SEO)
			&&
				df_cfg()->seo()->common()->getEnhancedRussianTransliteration()
	    ) {
			$this->_convertTable =
				array_merge (
					$this->_convertTable
					,
					$this->getRussianUpdatesLc ()
				)
			;
	    }

        parent::__construct();
    }


	/**
	 * @var array
	 */
	private $_russianUpdatesLc;

	/**
	 * @return array
	 */
	protected function getRussianUpdatesLc () {
 	    if (!isset ($this->_russianUpdatesLc)) {

		    $values =
				$this->lowercaseArray (
					array_values (
						$this->_russianUpdatesRaw
					)
				)
			;
		    $keys =
				array_keys (
					$this->_russianUpdatesRaw
				)
			;

			$this->_russianUpdatesLc =
				array_merge (
					df_array_combine (
						$keys
						,
						$values
					)
					,
					df_array_combine (
						$this->lowercaseArray ($keys)
						,
						$values
					)
				)
			;
	    }
		return $this->_russianUpdatesLc;
	}



	/**
	 * @var array
	 */
	private $_russianUpdates;

	/**
	 * @return array
	 */
	protected function getRussianUpdates () {
 	    if (!isset ($this->_russianUpdates)) {

		    $values =
				array_values (
					$this->_russianUpdatesRaw
				)
			;
		    $keys =
				array_keys (
					$this->_russianUpdatesRaw
				)
			;

			$this->_russianUpdates =
				array_merge (
					df_array_combine (
						$keys
						,
						$values
					)
					,
					df_array_combine (
						$this->lowercaseArray ($keys)
						,
						$this->lowercaseArray ($values)
					)
				)
			;
	    }
		return $this->_russianUpdates;
	}





	/**
	 * @param array $items
	 * @return array
	 */
	private function lowercaseArray (array $items) {
		return
			array_map (
				self::MB_STRTOLOWER
				,
				$items
			)
		;
	}

	const MB_STRTOLOWER = 'mb_strtolower';


	/**
	 * @var array
	 */
	private $_conversionTablePreservingCase;

	/**
	 * @return array
	 */
	protected function getConversionTablePreservingCase () {
 	    if (!isset ($this->_conversionTablePreservingCase)) {
			$this->_conversionTablePreservingCase =
				array_merge (
					$this->_convertTable
					,
					$this->getRussianUpdates ()
				)
			;
	    }
		return $this->_conversionTablePreservingCase;
	}



	/**
	 * @param  string $string
	 * @return string
	 */
    public function formatPreservingCase ($string) {
        return strtr($string, $this->getConversionTablePreservingCase());
    }


	/**
	 * @param  string $string
	 * @return string
	 */
	public function extendedFormat ($string) {
		return
				df_cfg()->seo()->urls()->getPreserveCyrillic()
			?
				df_output ()->formatUrlKeyPreservingCyrillic ($string)
			:
				df_output ()->transliterate ($string)
		;
	}



	/**
	 * @var array
	 */
	private $_russianUpdatesRaw =
		array (
			  'А' => 'A'
			, 'Б' => 'B'
			, 'В' => 'V'
			, 'Г' => 'G'
			, 'Д' => 'D'
			, 'Е' => 'E'
			, 'Ё' => 'YO'
			, 'Ж' => 'ZH'
			, 'З' => 'Z'
			, 'И' => 'I'
			, 'Й' => 'J'
			, 'К' => 'K'
			, 'Л' => 'L'
			, 'М' => 'M'
			, 'Н' => 'N'
			, 'О' => 'O'
			, 'П' => 'P'
			, 'Р' => 'R'
			, 'С' => 'S'
			, 'Т' => 'T'
			, 'У' => 'U'
			, 'Ф' => 'F'
			, 'Х' => 'H'
			, 'Ц' => 'C'
			, 'Ч' => 'CH'
			, 'Ш' => 'SH'
			, 'Щ' => 'SCH'
			, 'Ъ' => ''
			, 'Ы' => 'Y'
			, 'Ь' => ''
			, 'Э' => 'E'
			, "Ю" => "JU"
			, 'Я' => 'YA'

			, "ЬЕ" => "JE"
			, "ЬЯ" => "YA"
			, "ЬЁ" => "JO"
			, "ЬЮ" => "JU"
			, "ЪЕ" => "JE"
			, "ЪЯ" => "JA"
			, "ЪЁ" => "JO"
			, "ЪЮ" => "JU"
		)
	;



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Helper_Product_Url';
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
