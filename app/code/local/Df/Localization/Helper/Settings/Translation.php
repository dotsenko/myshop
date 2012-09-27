<?php


class Df_Localization_Helper_Settings_Translation extends Df_Core_Helper_Settings {



	/**
	 * @return Df_Localization_Model_Settings_Area
	 */
	public function admin () {

		/** @var Df_Localization_Model_Settings_Area $result */
		$result = $this->getArea ('admin');

		df_assert ($result instanceof Df_Localization_Model_Settings_Area);

		return $result;

	}




	/**
	 * @return Df_Localization_Model_Settings_Area
	 */
	public function email () {

		/** @var Df_Localization_Model_Settings_Area $result */
		$result = $this->getArea ('email');

		df_assert ($result instanceof Df_Localization_Model_Settings_Area);

		return $result;

	}




	/**
	 * @return Df_Localization_Model_Settings_Area
	 */
	public function frontend () {

		/** @var Df_Localization_Model_Settings_Area $result */
		$result = $this->getArea (Df_Core_Const_Design_Area::FRONTEND);

		df_assert ($result instanceof Df_Localization_Model_Settings_Area);

		return $result;

	}
	





	/**
	 * @param string $name
	 * @return Df_Localization_Model_Settings_Area
	 */
	private function getArea ($name) {

		df_param_string ($name, 0);

		if (!isset ($this->_area [$name])) {

			/** @var Df_Localization_Model_Settings_Area $result  */
			$result =
				df_model (
					Df_Localization_Model_Settings_Area::getNameInMagentoFormat()
					,
					array (
						Df_Localization_Model_Settings_Area::PARAM__GROUP => $name
					)
				)
			;


			df_assert ($result instanceof Df_Localization_Model_Settings_Area);

			$this->_area [$name] = $result;

		}


		df_assert ($this->_area [$name] instanceof Df_Localization_Model_Settings_Area);

		return $this->_area [$name];

	}


	/**
	* @var array
	*/
	private $_area = array ();







	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Localization_Helper_Settings_Translation';
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