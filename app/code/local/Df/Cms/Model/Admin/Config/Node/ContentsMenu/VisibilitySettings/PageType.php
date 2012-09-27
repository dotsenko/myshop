<?php

class Df_Cms_Model_Admin_Config_Node_ContentsMenu_VisibilitySettings_PageType
	extends Df_Core_Model_Abstract {
	
	
	
	/**
	 * @return Df_Cms_Model_Admin_Config_Node_ContentsMenu_VisibilitySettings_PageType
	 */
	public function addFields () {
		

        $this->getFieldset()
			->addField (
				$this->getFieldId ('enabled')
				,
				'select'
				,
				array (
					'label'  => df_helper()->cms()->__($this->getEnabledLabel())
					,
					'name' => $this->getFieldId ('enabled')
					,
					'container_id' => $this->getFieldContainerId ('enabled')
					,
					'values'  => $this->getOptionsYesNo ()
					,
					'tabindex' => $this->getTabIndex()
					,
					'class' => 'df-field'
				)
			)
		;


        $this->getFieldset()
			->addField (
				$this->getFieldId ('position')
				,
				'select'
				,
				array (
					'label' => df_helper()->cms()->__($this->getPositionLabel())
					,
					'name' => $this->getFieldId ('position')
					,
					'container_id' => $this->getFieldContainerId ('position')
					,
					'values' => $this->getOptionsPosition ()
					,
					'tabindex' => 1 + $this->getTabIndex ()
					,
					'class' => $this->getCssClassesAsStringForDependentFields ()
				)
			)
		;


        $this->getFieldset()
			->addField (
				$this->getFieldId ('vertical_ordering')
				,
				'select'
				,
				array (
					'label' => df_helper()->cms()->__($this->getVerticalOrderingLabel())
					,
					'note' => df_helper()->cms()->__('Считается сверху вниз, среди всех блоков в данном месте')
					,
					'name' => $this->getFieldId ('vertical_ordering')
					,
					'values' => $this->getOptionsVerticalOrdering ()
					,
					'container_id' => $this->getFieldContainerId ('vertical_ordering')
					,
					'tabindex'  => 2 + $this->getTabIndex ()
					,
					'class' => $this->getCssClassesAsStringForDependentFields ()
				)
			)
		;		
	

	
		return $this;
	
	}		
	
	
	
	
	/**
	 * @return string
	 */
	private function getCssClassesAsStringForDependentFields () {
	
		if (!isset ($this->_cssClassesAsStringForDependentFields)) {
	
			/** @var string $result  */
			$result = 
				implode (
					Df_Core_Const::T_SPACE
					,
					array (
						'df-field'
						,
						$this->getCssClassForDependency ()
					)
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_cssClassesAsStringForDependentFields = $result;
	
		}
	
	
		df_result_string ($this->_cssClassesAsStringForDependentFields);
	
		return $this->_cssClassesAsStringForDependentFields;
	
	}
	
	
	/**
	* @var string
	*/
	private $_cssClassesAsStringForDependentFields;	
	
	
	
	
	
	
	/**
	 * @return string
	 */
	private function getCssClassForDependency () {
	
		if (!isset ($this->_cssClassForDependency)) {
	
			/** @var string $result  */
			$result = 
				implode (
					'--'
					,
					array (
						'df-depends'
						,
						$this->getFieldId ('enabled')						
					)
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_cssClassForDependency = $result;
	
		}
	
	
		df_result_string ($this->_cssClassForDependency);
	
		return $this->_cssClassForDependency;
	
	}
	
	
	/**
	* @var string
	*/
	private $_cssClassForDependency;	
	
	
	



	/**
	 * @return string
	 */
	private function getEnabledLabel () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__ENABLED__LABEL);

		df_result_string ($result);

		return $result;

	}
	
	
	
	
	/**                                         
	 * @param string $fieldShortName
	 * @return string
	 */
	private function getFieldContainerId ($fieldShortName) {
		
		df_param_string ($fieldShortName, 0);
	
		/** @var string $result  */
		$result =
			implode (
				'__'
				,
				array (
					'field'
					,
					'contents_menu'
					, 
					$this->getPageTypeId()
					, 
					$fieldShortName					
				)
			)
		;	
	
		df_result_string ($result);
	
		return $result;
	
	}	
	
	
	
	
	
	
	/**                                         
	 * @param string $fieldShortName
	 * @return string
	 */
	private function getFieldId ($fieldShortName) {
		
		df_param_string ($fieldShortName, 0);
	
		/** @var string $result  */
		$result =
			implode (
				'__'
				,
				array (
					'contents_menu'
					, 
					$this->getPageTypeId()
					, 
					$fieldShortName					
				)
			)
		;	
	
		df_result_string ($result);
	
		return $result;
	
	}	
	
	
	
	
	
	
	/**
	 * @return Varien_Data_Form_Element_Fieldset
	 */
	private function getFieldset () {
	
		/** @var Varien_Data_Form_Element_Fieldset $result  */
		$result = $this->cfg (self::PARAM__FIELDSET);
	
		df_assert ($result instanceof Varien_Data_Form_Element_Fieldset);
	
		return $result;
	
	}
	
	
	


	/**
	 * @return array
	 */
	private function getOptionsPosition () {

		if (!isset ($this->_optionsPosition)) {

			/** @var Df_Cms_Model_Config_Source_ContentsMenu_Position $sourcePosition */
			$sourcePosition =
				Mage::getSingleton (
					Df_Cms_Model_Config_Source_ContentsMenu_Position::getNameInMagentoFormat()
				)
			;

			df_assert ($sourcePosition instanceof Df_Cms_Model_Config_Source_ContentsMenu_Position);


			/** @var array $result  */
			$result = $sourcePosition->toOptionArray ();


			df_assert_array ($result);

			$this->_optionsPosition = $result;

		}


		df_result_array ($this->_optionsPosition);

		return $this->_optionsPosition;

	}


	/**
	* @var array
	*/
	private $_optionsPosition;




	
	
	
	
	/**
	 * @return array
	 */
	private function getOptionsYesNo () {
	
		if (!isset ($this->_optionsYesNo)) {

			/** @var Mage_Adminhtml_Model_System_Config_Source_Yesno $yesNo */
			$yesNo = Mage::getSingleton('adminhtml/system_config_source_yesno');

			df_assert ($yesNo instanceof Mage_Adminhtml_Model_System_Config_Source_Yesno);

	
			/** @var array $result  */
			$result =
				array_reverse (
					$yesNo->toOptionArray(), true
				)
			;
	
	
			df_assert_array ($result);
	
			$this->_optionsYesNo = $result;
	
		}
	
	
		df_result_array ($this->_optionsYesNo);
	
		return $this->_optionsYesNo;
	
	}
	
	
	/**
	* @var array
	*/
	private $_optionsYesNo;





	/**
	 * @return array
	 */
	private function getOptionsVerticalOrdering () {
	
		if (!isset ($this->_optionsVerticalOrdering)) {

			/** @var Df_Admin_Model_Config_Source_SelectNumberFromDropdown $sourceOrdering */
			$sourceOrdering =
				Mage::getModel (
					Df_Admin_Model_Config_Source_SelectNumberFromDropdown::getNameInMagentoFormat()
					,
					array (
						Df_Admin_Model_Config_Source_SelectNumberFromDropdown::CONFIG_PARAM__DF_MAX => df_string (15)
					)
				)
			;

			df_assert ($sourceOrdering instanceof Df_Admin_Model_Config_Source_SelectNumberFromDropdown);

	
			/** @var array $result  */
			$result = $sourceOrdering->toOptionArray ();
	
	
			df_assert_array ($result);
	
			$this->_optionsVerticalOrdering = $result;
	
		}
	
	
		df_result_array ($this->_optionsVerticalOrdering);
	
		return $this->_optionsVerticalOrdering;
	
	}
	
	
	/**
	* @var array
	*/
	private $_optionsVerticalOrdering;

	
	
	
	
	
	
	
	/**
	 * @return string
	 */
	private function getPageTypeId () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__PAGE_TYPE_ID);

		df_result_string ($result);

		return $result;

	}
	
	




	/**
	 * @return string
	 */
	private function getPositionLabel () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__POSITION__LABEL);

		df_result_string ($result);

		return $result;

	}
	
	
	
	
	/**
	 * @return int
	 */
	private function getTabIndex () {
	
		/** @var int $result  */
		$result = $this->cfg (self::PARAM__TAB_INDEX);
		
		df_result_integer ($result);
	
		return $result;
	
	}		
	




	/**
	 * @return string
	 */
	private function getVerticalOrderingLabel () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__VERTICAL_ORDERING__LABEL);

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
			->validateClass (
				self::PARAM__FIELDSET, 'Varien_Data_Form_Element_Fieldset'
			)
			->addValidator (
				self::PARAM__ENABLED__LABEL, new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__POSITION__LABEL, new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__VERTICAL_ORDERING__LABEL, new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__PAGE_TYPE_ID, new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__TAB_INDEX, new Df_Zf_Validate_Int()
			)
		;
	}




	const PARAM__ENABLED__LABEL = 'enabled__label';
	const PARAM__FIELDSET = 'fieldset';
	const PARAM__PAGE_TYPE_ID = 'page_type_id';
	const PARAM__POSITION__LABEL = 'position__label';
	const PARAM__TAB_INDEX = 'tab_index';
	const PARAM__VERTICAL_ORDERING__LABEL = 'vertical_ordering__label';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Model_Admin_Config_Node_ContentsMenu_VisibilitySettings_PageType';
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


