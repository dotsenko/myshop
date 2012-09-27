<?php


class Df_Vk_Block_Frontend_Widget_Groups extends Df_Vk_Block_Frontend_Widget {



	/**
	 * @return int
	 */
	public function getApplicationId () {

		if (!isset ($this->_applicationId)) {

			/** @var string $pattern  */
			$pattern =
				sprintf (
					'#%s\([^{)]*{[^}]*}, (\d+)#m'
					,
					preg_quote (
						$this->getJavaScriptObjectName()
					)
				)
			;

			/** @var array $matches  */
			$matches = array ();

			preg_match ($pattern, $this->getSettings()->getCode(), $matches);

			/** @var int $result  */
			$result =
				intval (
					df_a ($matches, 1, 0)
				)
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
	 * @return Df_Vk_Helper_Settings_Widget_Groups_Page
	 */
	public function getSettingsForTheCurrentPage () {
	
		if (!isset ($this->_settingsForTheCurrentPage)) {
	
			/** @var Df_Vk_Helper_Settings_Widget_Groups_Page $result  */
			$result = 
				Mage::helper (
					Df_Vk_Helper_Settings_Widget_Groups_Page::getNameInMagentoFormat()
				)
			;
	
			df_assert ($result instanceof Df_Vk_Helper_Settings_Widget_Groups_Page);


			$result
				->setType (
					$this->getCurrentPageType()
				)
			;

	
			$this->_settingsForTheCurrentPage = $result;
	
		}
	
	
		df_assert ($this->_settingsForTheCurrentPage instanceof Df_Vk_Helper_Settings_Widget_Groups_Page);
	
		return $this->_settingsForTheCurrentPage;
	
	}
	
	
	/**
	* @var Df_Vk_Helper_Settings_Widget_Groups_Page
	*/
	private $_settingsForTheCurrentPage;






	/**
	 * @override
	 * @return string
	 */
	public function getJavaScriptNameSpace () {
		return 'groups';
	}





	/**
	 * @override
	 * @return string|null
	 */
	protected function getDefaultTemplate () {
		return 'df/vk/groups.phtml';
	}





	/**
	 * @override
	 * @return string
	 */
	protected function getJavaScriptObjectName () {
		return 'VK.Widgets.Group';
	}



	/**
	 * @override
	 * @return Df_Vk_Helper_Settings_Widget
	 */
	protected function getSettings () {
		return df_cfg()->vk()->groups();
	}




	/**
	 * @override
	 * @return bool
	 */
	protected function needToShow () {

		/** @var bool $result  */
		$result =
				parent::needToShow()
			&&
				$this->getSettingsForTheCurrentPage()->getEnabled()
		;

		df_result_boolean ($result);

		return $result;
	}





	/**
	 * @return string
	 */
	private function getCurrentPageType () {

		if (!isset ($this->_currentPageType)) {

			/** @var string $result  */
			$result = Df_Vk_Model_Widget_PageType::OTHER;

			foreach ($this->getPageTypeMap() as $type => $handle) {

				/** @var string $type */
				/** @var string $handle */

				df_assert_string ($type);
				df_assert_string ($handle);

				if (df_handle_presents($handle)) {
					$result = $type;
					break;
				}
			}

			df_assert_string ($result);

			$this->_currentPageType = $result;

		}


		df_result_string ($this->_currentPageType);

		return $this->_currentPageType;

	}


	/**
	* @var string
	*/
	private $_currentPageType;






	/**
	 * @return array
	 */
	private function getPageTypeMap () {

		/** @var array $result  */
		$result =
			array (
 				Df_Vk_Model_Widget_PageType::ACCOUNT => 'customer_account'
				,
				Df_Vk_Model_Widget_PageType::CATALOG_PRODUCT_LIST => 'catalog_category_view'
				,
				Df_Vk_Model_Widget_PageType::CATALOG_PRODUCT_VIEW => 'catalog_product_view'
				,
				Df_Vk_Model_Widget_PageType::FRONT => 'cms_index_index'
			)
		;


		df_result_array ($result);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Vk_Block_Frontend_Widget_Groups';
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


