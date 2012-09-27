<?php


class Df_AccessControl_Block_Admin_Tab
	extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface {
	
	
	
	
	
	/**
	 * @return bool
	 */
	public function isModuleEnabled () {

		/** @var bool $result  */
		$result = $this->getRole()->isModuleEnabled();

		df_result_boolean ($result);
	
		return $result;
	
	}
	




	/**
	 * @return Df_AccessControl_Model_Role
	 */
	private function getRole () {

		if (!isset ($this->_role)) {

			/** @var Df_AccessControl_Model_Role $result  */
			$result =
				df_model (
					Df_AccessControl_Model_Role::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_AccessControl_Model_Role);


			$result->load ($this->getRoleId());


			df_assert ($result instanceof Df_AccessControl_Model_Role);

			$this->_role = $result;

		}


		df_assert ($this->_role instanceof Df_AccessControl_Model_Role);

		return $this->_role;

	}


	/**
	* @var Df_AccessControl_Model_Role
	*/
	private $_role;


	





	
	/**
	 * @return int|null
	 */
	private function getRoleId () {

		/** @var int|null $result  */
		$result =
			df_request ('rid')
		;


		if (!is_null($result)) {
			df_result_integer ($result);
		}


		return $result;

	}







	/**
	 * @return string
	 */
	public function renderCategoryTree () {


		/** @var Df_AccessControl_Block_Admin_Tab_Tree $block  */
		$block =
			$this->getLayout()->createBlock('df_access_control/admin_tab_tree')
		;

		df_assert ($block instanceof Df_AccessControl_Block_Admin_Tab_Tree);


		/** @var string $result  */
		$result =
			$block->toHtml()
		;


		df_result_string ($result);

		return $result;

	}






	/**
	 * @return string
	 */
	public function renderStoreSwitcher () {


		/** @var Mage_Adminhtml_Block_Store_Switcher $block  */
		$block =
			$this->getLayout()->createBlock('adminhtml/store_switcher')
		;

		df_assert ($block instanceof Mage_Adminhtml_Block_Store_Switcher);



		$block->setTemplate ('store/switcher/enhanced.phtml');



		/** @var string $result  */
		$result =
			$block->toHtml()
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @override
	 * @return string|null
	 */
    public function getTemplate() {

		/** @var string|null $result  */
        $result =
				!(
						df_enabled (Df_Core_Feature::ACCESS_CONTROL)
					&&
						df_cfg()->admin()->access_control()->getEnabled ()
				)
			?
				null
			:
				self::TEMPLATE

		;


		if (!is_null($result)) {
			/*************************************
			 * Проверка результата работы метода
			 */
			df_result_string ($result);
			/*************************************/
		}


		return $result;
    }






    /**
     * Return Tab label
     *
     * @return string
     */
    public function getTabLabel() {

		/** @var string $result  */
		$result = self::LABEL;

		df_result_string ($result);

		return $result;
	}







    /**
     * Return Tab title
     *
     * @return string
     */
    public function getTabTitle() {

		/** @var string $result  */
		$result = self::LABEL;

		df_result_string ($result);

		return $result;

	}





    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab() {

		/** @var bool $result  */
		$result = true;

		df_result_boolean ($result);

		return $result;

	}





    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden() {

		/** @var bool $result  */
		$result = false;

		df_result_boolean ($result);

		return $result;

	}




	const LABEL = 'Доступ к товарным разделам';
	const TEMPLATE = 'df/access_control/tab.phtml';
}

