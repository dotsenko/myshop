<?php


class Df_Catalog_Model_Category_Navigation_Observer extends Df_Core_Model_Abstract {




	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function core_block_abstract_to_html_after (Varien_Event_Observer $observer) {

		if (self::isHookRight()) {

			if (!$this->_catalogNavigationInserted) {
				if (!$this->_inProcessing) {
					$this->_inProcessing = true;

					/**
					 * При загрузке главной страницы мы сюда попадаем 55 раз
					 */

					$this->_catalogNavigationInserted =
						$this->getInserter ($observer)->insert ()
					;
					$this->_inProcessing = false;
				}
			}

		}

	}




	/**
	 * При обработке текущего блока мы создаём новые блоки,
	 * и нам надо избежать бесконечной рекурсии
	 *
	 * @var bool
	 */
	private $_inProcessing = false;

	/**
	 * @var bool
	 */
	private $_catalogNavigationInserted = false;










	/**
	 * @param Varien_Event_Observer $observer
	 * @return Df_Catalog_Model_Category_Content_Inserter
	 */
	private function getInserter (Varien_Event_Observer $observer) {
		return
			df_model (
				Df_Catalog_Model_Category_Content_Inserter::getNameInMagentoFormat()
				,
				array (
					Df_Catalog_Model_Category_Content_Inserter::PARAM_OBSERVER => $observer
					,
					Df_Catalog_Model_Category_Content_Inserter::PARAM_BLOCK_TO_INSERT =>
						$this->getNavigationBlock ()
				)
			)
		;
	}





	/**
	 * @return Df_Catalog_Block_Category_Navigation
	 */
	private function getNavigationBlock () {
		if (!$this->_navigationBlock) {
			$this->_navigationBlock =
				df_mage()->core()->layout()
					->createBlock(
						'df_catalog/category_navigation'
						,
						'df_catalog.category.navigation'
						,
						array ()
					)
			;
		}
		return $this->_navigationBlock;
	}


	/**
	 * @var Df_Catalog_Block_Category_Navigation
	 */
	private $_navigationBlock;





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Category_Navigation_Observer';
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
	 * Определяет, подлежит ли текущий блок обработке
	 *
	 * @return bool
	 */
	private static function isHookRight () {

		/** @var bool $result */
		static $result;

		if (!isset ($result)) {

			$result =
					df_enabled (Df_Core_Feature::TWEAKS)
				&&
					df_cfg()->catalog()->navigation()->getEnabled()
				&&
					!df_is_admin()
				&&
					df_handle_presents (Df_Catalog_Const::LAYOUT_HANDLE__CATEGORY_VIEW)
			;
		}

		return $result;
	}


	
}