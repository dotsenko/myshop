<?php



class Df_Localization_Model_Translation_Db_Source_Key extends Df_Core_Model_Abstract {




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Localization_Model_Translation_Db_Source_Key';
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








	public function getModule () {
		return df_a ($this->getSplittedKey(), 0);
	}


	public function getString () {
		return df_a ($this->getSplittedKey(), 1);
	}



	/**
	 * @var array
	 */
	private $_splittedKey;


	/**
	 * @return array
	 */
	protected function getSplittedKey () {
		if (!isset ($this->_splittedKey)) {
			$this->_splittedKey = explode ("::", $this->getKey ());
			df_assert (2 == count ($this->_splittedKey), "Invalid key");
		}
		return $this->_splittedKey;
	}



	/**
	 * @return string
	 */
	protected function getKey () {
		return $this->cfg (self::PARAM_KEY);
	}


	const PARAM_KEY = 'key';


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
	        ->addValidator (self::PARAM_KEY, new Zend_Validate_NotEmpty ())
	        ->addValidator (self::PARAM_KEY, new Df_Zf_Validate_String ())
		;
	}

}