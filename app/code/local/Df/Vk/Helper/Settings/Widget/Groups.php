<?php

class Df_Vk_Helper_Settings_Widget_Groups extends Df_Vk_Helper_Settings_Widget {



	/**
	 * @override
	 * @return string
	 */
	protected function getWidgetType () {
		return 'groups';
	}




	/**
	 * @return Df_Vk_Helper_Settings_Widget_Groups_Page
	 */
	public function accountPage () {

		/** @var Df_Vk_Helper_Settings_Widget_Groups_Page $result  */
		$result =
			$this->getPageSettings('account')
		;

		df_assert ($result instanceof Df_Vk_Helper_Settings_Widget_Groups_Page);

		return $result;

	}






	/**
	 * @return Df_Vk_Helper_Settings_Widget_Groups_Page
	 */
	public function catalogProductListPage () {

		/** @var Df_Vk_Helper_Settings_Widget_Groups_Page $result  */
		$result =
			$this->getPageSettings('catalog_product_list')
		;

		df_assert ($result instanceof Df_Vk_Helper_Settings_Widget_Groups_Page);

		return $result;

	}






	/**
	 * @return Df_Vk_Helper_Settings_Widget_Groups_Page
	 */
	public function catalogProductViewPage () {

		/** @var Df_Vk_Helper_Settings_Widget_Groups_Page $result  */
		$result =
			$this->getPageSettings('catalog_product_view')
		;

		df_assert ($result instanceof Df_Vk_Helper_Settings_Widget_Groups_Page);

		return $result;

	}






	/**
	 * @return Df_Vk_Helper_Settings_Widget_Groups_Page
	 */
	public function frontPage () {

		/** @var Df_Vk_Helper_Settings_Widget_Groups_Page $result  */
		$result =
			$this->getPageSettings('front')
		;

		df_assert ($result instanceof Df_Vk_Helper_Settings_Widget_Groups_Page);

		return $result;

	}







	/**
	 * @return Df_Vk_Helper_Settings_Widget_Groups_Page
	 */
	public function otherPage () {

		/** @var Df_Vk_Helper_Settings_Widget_Groups_Page $result  */
		$result =
			$this->getPageSettings('other')
		;

		df_assert ($result instanceof Df_Vk_Helper_Settings_Widget_Groups_Page);

		return $result;

	}




	
	
	
	
	/**
	 * @param string $pageType
	 * @return Df_Vk_Helper_Settings_Widget_Groups_Page
	 */
	private function getPageSettings ($pageType) {

		df_param_string ($pageType, 0);
	
		if (!isset ($this->_pageSettings [$pageType])) {
	
			/** @var Df_Vk_Helper_Settings_Widget_Groups_Page $result  */
			$result = 
				Mage::helper (Df_Vk_Helper_Settings_Widget_Groups_Page::getNameInMagentoFormat())
			;
	
			df_assert ($result instanceof Df_Vk_Helper_Settings_Widget_Groups_Page);

			$result->setType ($pageType);
	
			$this->_pageSettings [$pageType] = $result;
	
		}
	
	
		df_assert ($this->_pageSettings [$pageType] instanceof Df_Vk_Helper_Settings_Widget_Groups_Page);
	
		return $this->_pageSettings [$pageType];
	
	}
	
	
	/**
	* @var array
	*/
	private $_pageSettings = array ();




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Vk_Helper_Settings_Widget_Groups';
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