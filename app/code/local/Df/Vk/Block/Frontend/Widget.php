<?php


abstract class Df_Vk_Block_Frontend_Widget extends Df_Core_Block_Template {



	/**
	 * @return string
	 */
	abstract public function getJavaScriptNameSpace ();


	/**
	 * @return string
	 */
	abstract protected function getJavaScriptObjectName ();


	/**
	 * @return Df_Vk_Helper_Settings_Widget
	 */
	abstract protected function getSettings ();







	/**
	 * @return int
	 */
	public function getApplicationId () {

		if (!isset ($this->_applicationId)) {

			/** @var string $pattern  */
			$pattern = '#apiId: (\d+)#m';

			/** @var array $matches  */
			$matches = array ();

			preg_match ($pattern, $this->getSettings()->getCode(), $matches);

			/** @var int $result  */
			$result =
				df_a ($matches, 1, 0)
			;


			df_assert_integer ($result);

			$this->_applicationId = $result;

		}


		df_result_integer ($this->_applicationId);

		return $this->_applicationId;

	}


	/**
	* @var int
	*/
	private $_applicationId;





	/**
	 * @return string
	 */
	public function getSettingsAsJson () {

		/** @var string $pattern  */
		$pattern =
			sprintf (
				'#%s\([^{)]*({[^}]*})#m'
				,
				preg_quote (
					$this->getJavaScriptObjectName()
				)
			)
		;

		/** @var array $matches  */
		$matches = array ();

		preg_match ($pattern, $this->getSettings()->getCode(), $matches);

		/** @var string $result  */
		$result =
			df_a ($matches, 1, 0)
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @override
	 * @return string|null
	 */
	protected function getDefaultTemplate () {
		return 'df/vk/widget.phtml';
	}





	/**
	 * @override
	 * @return bool
	 */
	protected function needToShow () {

		/** @var bool $result  */
		$result =
				df_enabled (Df_Core_Feature::VK)
			&&
				$this->getSettings()->getEnabled()
		;

		df_result_boolean ($result);

		return $result;
	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Vk_Block_Frontend_Widget';
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


