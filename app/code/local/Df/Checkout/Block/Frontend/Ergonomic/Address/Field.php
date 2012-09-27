<?php


class Df_Checkout_Block_Frontend_Ergonomic_Address_Field extends Df_Core_Block_Template {


	
	/**
	 * @return string
	 */
	public function getApplicability () {
	
		if (!isset ($this->_applicability)) {
	
			/** @var string $result  */
			$result = $this->getConfigValue ('applicability');

			df_assert_string ($result);
	
			$this->_applicability = $result;
	
		}
	
	
		df_result_string ($this->_applicability);
	
		return $this->_applicability;
	
	}
	
	
	/**
	* @var string
	*/
	private $_applicability;	

	



	/**
	 * @return string
	 */
	public function getCssClassesAsText () {

		/** @var string $result  */
		$result =
			df_output()->getCssClassesAsString (
				$this->getCssClasses ()
			)
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	public function getDomId () {

		if (!isset ($this->_domId)) {

			/** @var string $result  */
			$result =
				implode (
					':'
					,
					array (
						$this->getAddress()->getType()
						,
						$this->getType()
					)
				)
			;


			df_assert_string ($result);

			$this->_domId = $result;

		}


		df_result_string ($this->_domId);

		return $this->_domId;

	}


	/**
	* @var string
	*/
	private $_domId;


	
	

	
	
	/**
	 * @return string
	 */
	public function getDomName () {
	
		if (!isset ($this->_domName)) {
	
			/** @var string $result  */
			$result = 
				sprintf (
					'%s[%s]'
					,
					$this->getAddress()->getType()
					,
					$this->getType()
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_domName = $result;
	
		}
	
	
		df_result_string ($this->_domName);
	
		return $this->_domName;
	
	}
	
	
	/**
	* @var string
	*/
	private $_domName;	




	/**
	 * @return string
	 */
	public function getLabel () {

		/** @var string $result  */
		$result =
			$this->escapeHtml (
				df_helper()->checkout()->__ (
					$this->getData (self::PARAM__LABEL)
				)
			)
		;


		df_result_string ($result);

		return $result;

	}
	
	
	
	
	/**
	 * @return string
	 */
	public function getLabelHtml () {
	
		if (!isset ($this->_labelHtml)) {
	
			/** @var string $result  */
			$result = 
				Df_Core_Model_Format_Html_Tag::output (
					implode (
						Df_Core_Const::T_EMPTY
						,
						df_clean (
							array (
								($this->isRequired() ? '<em>*</em>' : null)
								,
								$this->getLabel()
							)
						)
					)
					,
					'label'
					,
					df_clean (
						array (
							'for' => $this->getDomId()
							,
							'class' => ($this->isRequired() ? 'required' : null)
						)
					)

				)
			;
	
	
			df_assert_string ($result);
	
			$this->_labelHtml = $result;
	
		}
	
	
		df_result_string ($this->_labelHtml);
	
		return $this->_labelHtml;
	
	}
	
	
	/**
	* @var string
	*/
	private $_labelHtml;	
	
	




	/**
	 * @return int
	 */
	public function getOrderingInConfig () {

		/** @var string $result  */
		$result = intval ($this->cfg (self::PARAM__ORDERING_IN_CONFIG));

		df_result_integer ($result);

		return $result;

	}




	
	
	/**
	 * @return int
	 */
	public function getOrderingWeight () {
	
		if (!isset ($this->_orderingWeight)) {
	
			/** @var int $result  */
			$result =
				intval (
					$this->getConfigValue ('ordering')
				)
			;
	
			df_assert_integer ($result);
	
			$this->_orderingWeight = $result;
	
		}
	
	
		df_result_integer ($this->_orderingWeight);
	
		return $this->_orderingWeight;
	
	}
	
	
	/**
	* @var int
	*/
	private $_orderingWeight;





	/**
	 *
	 * @override
	 * @return string|null
	 */
    public function getTemplate() {

		/** @var string|null $result  */
        $result =
				!$this->needToShow()
			?
				null
			:
				(
						/**
						 * Разработчик может указать шаблон поля в настроечном файле XML.
						 * Например:
						 *
						 * 	<customer_password>
								<template>df/checkout/ergonomic/address/field/password.phtml</template>
							</customer_password>
						 */
						$this->hasData (self::PARAM__TEMPLATE)
					?
						$this->getData (self::PARAM__TEMPLATE)
					:
						$this->getDefaultTemplate ()
				)
		;


		if (!is_null($result)) {
			df_result_string ($result);
		}


		return $result;
    }




	/**
	 * public, потому что вызывается через walk
	 *
	 * @return string
	 */
	public function getType () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__TYPE);

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return mixed
	 */
	public function getValue () {

		/** @var mixed $result  */
		$result =
			$this->getAddress()->getAddress()->getDataUsingMethod (
				$this->getType()
			)
		;

		return $result;

	}





	/**
	 * @return bool
	 */
	public function isRequired () {

		/** @var bool $result  */
		$result =
			(
					Df_Checkout_Model_Config_Source_Field_Applicability::VALUE__REQUIRED
				===
					$this->getApplicability()
			)
		;

		df_result_boolean ($result);

		return $result;

	}





	/**
	 * @override
	 * @return bool
	 */
	public function needToShow () {

		/** @var bool $result  */
		$result =
				parent::needToShow()
			&&
				(
						Df_Checkout_Model_Config_Source_Field_Applicability::VALUE__NO
					!==
						$this->getApplicability()
				)
			&&
			 	$this->checkAuthenticationStatus ()
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @return bool
	 */
	protected function checkAuthenticationStatus () {

		/** @var bool $result  */
		$result =
				(self::PARAM__AUTHENTICATED__ANY === $this->getAuthenticated())
			||
				(
						df_mage()->customer()->isLoggedIn()
					&&
						(self::PARAM__AUTHENTICATED__YES === $this->getAuthenticated())
				)
			||
				(
						!df_mage()->customer()->isLoggedIn()
					&&
						(self::PARAM__AUTHENTICATED__NO === $this->getAuthenticated())
				)
		;


		df_result_boolean ($result);

		return $result;

	}





	/**
	 * @return Df_Checkout_Block_Frontend_Ergonomic_Address
	 */
	protected function getAddress () {

		/** @var Df_Checkout_Block_Frontend_Ergonomic_Address $result  */
		$result = $this->cfg (self::PARAM__ADDRESS);

		df_assert ($result instanceof Df_Checkout_Block_Frontend_Ergonomic_Address);

		return $result;

	}




	/**
	 * Кто может видеть данное поле: авторизованные, неавторизованные или все
	 *
	 * @return string
	 */
	protected function getAuthenticated () {

		/** @var string $result  */
		$result =
			$this->cfg (self::PARAM__AUTHENTICATED, self::PARAM__AUTHENTICATED__ANY)
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * Этот метод перекрывается в классе
	 * Df_Checkout_Block_Frontend_Ergonomic_Address_Field_Region_Dropdown
	 * @return string
	 */
	protected function getConfigShortKey () {

		/** @var string $result  */
		$result = $this->getType ();

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return array
	 */
	protected function getCssClasses () {

		/** @var array $result  */
		$result =
			df_clean (
				array_merge (
					array (
						$this->isRequired() ? 'required-entry' : null
					)
					,
					df_parse_csv (
						df_convert_null_to_empty_string (
							$this->cfg (self::PARAM__CSS_CLASSES)
						)
					)
				)
			)
		;


		df_result_array ($result);

		return $result;

	}





	/**
	 * @param string $paramName
	 * @return string
	 */
	private function getConfigValue ($paramName) {

		df_param_string ($paramName, 0);


		/** @var string $key  */
		$key =
			df()->config()->implodeKey (
				array (
					sprintf (
						'df_checkout/%s_field_%s'
						,
						$this->getAddress()->getType()
						,
						$paramName
					)
					,
					$this->getConfigShortKey ()
				)
			)
		;

		df_assert_string ($key);


		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				$key
			)
		;


		df_assert (
			is_string ($result)
			,
			sprintf (
				'Не могу прочитать значение настройки «%s»'
				,
				$key
			)
		);

		return $result;

	}





	const PARAM__ADDRESS = 'address';
	const PARAM__AUTHENTICATED = 'authenticated';
	const PARAM__CSS_CLASSES = 'css-classes';
	const PARAM__LABEL = 'label';
	const PARAM__ORDERING_IN_CONFIG = 'ordering_in_config';
	const PARAM__TEMPLATE = 'template';
	const PARAM__TYPE = 'type';




	const PARAM__AUTHENTICATED__NO = 'no';
	const PARAM__AUTHENTICATED__YES = 'yes';
	const PARAM__AUTHENTICATED__ANY = 'any';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Block_Frontend_Ergonomic_Address_Field';
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


