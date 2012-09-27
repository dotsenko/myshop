<?php


class Df_Seo_Model_Template_Processor extends Df_Core_Model_Abstract {







	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Model_Template_Processor';
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
	 * @return string
	 */
	public function process () {
		return
			strtr (
				$this->getText()
				,
				$this->getMappings ()
			)
		;
	}



	/**
	 * Возвращает доступный в выражениях объект по имени.
	 * Выражении «product.manufacturer» имя объекта — «product»,
	 * и мы можем получить сам объект путём вызова
	 * $processor->getObject ("product");
	 *
	 * @param string $name
	 * @return Varien_Object
	 */
	public function getObject ($name) {
		return df_a ($this->getObjects (), $name);
	}



	/**
	 * @return array
	 */
	private function getMappings () {
		$result = array ();
		foreach ($this->getExpressions () as $expression) {
			/** @var Df_Seo_Model_Template_Expression $expression */
			$result [$expression->getRaw()] = $expression->getResult();
		}
		return $result;
	}




	/**
	 * @return array
	 */
	private function getExpressions () {
		$result =
			array_map (
				array ($this, "createExpression")
				,
					preg_match_all (
						$this->getPattern ()
						,
						$this->getText()
						,
						$matches
						,
						PREG_SET_ORDER
					)
				?
					$matches
				:
					array ()
			)
		;

		return $result;
	}



	/**
	 * @return Df_Seo_Model_Template_Expression
	 */
	public function createExpression (array $params) {
		return
			df_model (
				Df_Seo_Model_Template_Expression::getNameInMagentoFormat()
				,
				array (
					"processor" => $this
					,
					"raw" => df_a ($params, 0)
					,
					"clean" => df_a ($params, 1)
				)
			)
		;
	}





	/**
	 * @return string
	 */
	private function getPattern () {
		return '#{([^}]+)}#mui';
	}




	/**
	 * @return string
	 */
	protected function getText () {
		return $this->cfg (self::PARAM_TEXT);
	}



	/**
	 * @return array
	 */
	protected function getObjects () {
		return $this->cfg (self::PARAM_OBJECTS);
	}


	const PARAM_TEXT = 'text';
	const PARAM_OBJECTS = 'objects';


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
	        ->addValidator (self::PARAM_TEXT, new Df_Zf_Validate_String())
	        ->addValidator (self::PARAM_OBJECTS, new Df_Zf_Validate_Array())
		;
	}


}