<?php


class Df_Seo_Model_Template_Expression extends Df_Core_Model_Abstract {





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Model_Template_Expression';
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





	/**
	 * Результат вычисления выражения
	 *
	 * @return string
	 */
	public function getResult () {
		return
				$this->getAdapter ()
			?
				$this
					->getAdapter ()
						->getPropertyValue (
					    	$this->getPropertyName()
						)
			:
				$this->getRaw ()
		;
	}



	/**
	 * @var Df_Seo_Model_Template_Adapter
	 */
	private $_adapter;


	/**
	 * @return Df_Seo_Model_Template_Adapter
	 */
	private function getAdapter () {
		if (!isset ($this->_adapter)) {
			$this->_adapter =
				df_model (
					$this->getAdapterClass ()
					,
					array (
						Df_Seo_Model_Template_Adapter::PARAM_EXPRESSION => $this
					)
				)
			;
		}
		return $this->_adapter;
	}




	/**
	 * @return string
	 */
	private function getAdapterClass () {

		/** @var string $valueAsString  */
		$valueAsString =
			df()->config()->getNodeValueAsString (
				$this->getConfigNode ()
			)
		;

		return
				df_empty ($valueAsString)
			?
				null
			:
				$valueAsString
		;
	}


	/**
	 * @return Mage_Core_Model_Config_Element|null
	*/
	private function getConfigNode () {
		return
			df()->config()->getNodeByKey (
				sprintf (
					"df/seo/template/objects/%s/adapter"
					,
					$this->getObjectName ()
				)
			)
		;
	}


	/**
	 * @return Varien_Object
	 */
	public function getObject () {
		return $this->getProcessor()->getObject($this->getObjectName());
	}




	/**
	 * Например, «product» для выражения «product.manufacturer»
	 *
	 * @return string
	 */
	public function getObjectName () {
		return mb_strtolower (df_a ($this->getCleanParts (), 0));
	}



	/**
	 * Например, «manufacturer» для выражения «product.manufacturer»
	 *
	 * @return string
	 */
	public function getPropertyName () {
		return df_a ($this->getCleanParts (), 1);
	}




	/**
	 * @var array
	 */
	private $_cleanParts;



	/**
	 * @return array
	 */
	private function getCleanParts () {
		if (!isset ($this->_cleanParts)) {
			$this->_cleanParts =
				array_map (
					"df_trim"
					,
					explode (".", $this->getClean())
				)
			;
		}
		return $this->_cleanParts;
	}



	/**
	 * Например, «product.manufacturer»
	 *
	 * @return string
	 */
	public function getClean () {
		return $this->cfg (self::PARAM_CLEAN);
	}



	/**
	 * Например,  «{product.manufacturer}»
	 *
	 * @return string
	 */
	public function getRaw () {
		return $this->cfg (self::PARAM_RAW);
	}




	/**
	 * @return Df_Seo_Model_Template_Processor
	 */
	public function getProcessor () {
		return $this->cfg (self::PARAM_PROCESSOR);
	}


	const PARAM_PROCESSOR = 'processor';

	const PARAM_RAW = 'raw';
	const PARAM_CLEAN = 'clean';


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
			->validateClass (self::PARAM_PROCESSOR, Df_Seo_Model_Template_Processor::getClass())
	        ->addValidator (self::PARAM_RAW, new Df_Zf_Validate_String())
	        ->addValidator (self::PARAM_CLEAN, new Df_Zf_Validate_String())
		;
	}


}