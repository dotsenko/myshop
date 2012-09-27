<?php


class Df_AccessControl_Model_Event_Permissions_Role_Saverole
	extends Df_Core_Model_Event_Controller_Action_Postdispatch {




	/**
	 * @return bool
	 */
	public function isModuleEnabledForRole () {

		/** @var bool $result  */
		$result =
				0
			!==
				intval (
					$this->getController()->getRequest()->getParam (
						self::REQUEST_PARAM__DF_ACCESSCONTROL__ENABLE
					)
				)
		;

		df_result_boolean ($result);

		return $result;

	}







	/**
	 * @return int
	 */
	public function getRoleId () {

		/** @var int $result  */
		$result =
			$this->getController()->getRequest()->getParam (
				self::REQUEST_PARAM__ROLE_ID
			)
		;

		if (df_empty ($result)) {
			/**
			 * Сюда мы попадаем при первом сохранении новой роли
			 */
			$result = df_helper()->accessControl()->getLastSavedRoleId();
		}


		df_result_integer ($result);

		return $result;

	}

	

	
	
	/**
	 * @return int[]
	 */
	public function getSelectedCategoryIds () {
	
		if (!isset ($this->_selectedCategoryIds)) {
	
			/** @var int[] $result  */
			$result =
				df_parse_csv (
					$this->getSelectedCategoryIdsAsString ()
				)
			;
	
	
			df_assert_array ($result);
	
			$this->_selectedCategoryIds = $result;
	
		}
	
	
		df_result_array ($this->_selectedCategoryIds);
	
		return $this->_selectedCategoryIds;
	
	}
	
	
	/**
	* @var int[]
	*/
	private $_selectedCategoryIds;






	/**
	 * @return string
	 */
	private function getSelectedCategoryIdsAsString () {

		/** @var string $result  */
		$result =

			$this->getController()->getRequest()->getParam (
				self::REQUEST_PARAM__DF_ACCESSCONTROL__SELECTEDCATEGORIES
				,
				Df_Core_Const::T_EMPTY
			)
		;

		df_result_string ($result);

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_AccessControl_Model_Event_Permissions_Role_Saverole';
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



	const REQUEST_PARAM__DF_ACCESSCONTROL__SELECTEDCATEGORIES = 'df_accessControl_selectedCategories';
	const REQUEST_PARAM__DF_ACCESSCONTROL__ENABLE = 'df_accessControl_enable';
	const REQUEST_PARAM__ROLE_ID = 'role_id';

}


