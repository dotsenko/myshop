<?php

class Df_Cms_Block_Frontend_Catalog_Navigation_Submenu
	extends Df_Core_Block_Abstract
	implements Df_Catalog_Block_Navigation_Submenu {



	/**
	 * @override
	 * @param Varien_Data_Tree $additionalRoot
	 * @return Df_Cms_Block_Frontend_Catalog_Navigation_Submenu
	 */
	public function appendMenu (Varien_Data_Tree $additionalRoot) {

		if (
				df_cfg()->cms()->hierarchy()->isEnabled()
			&&
				df_enabled (Df_Core_Feature::CMS_2)
		) {

			foreach (df_helper()->cms()->getTree()->getTree()->getNodes() as $node) {
				/** @var Df_Cms_Varien_Data_Tree_Node $node */
				df_assert ($node instanceof Df_Cms_Varien_Data_Tree_Node);

				if (is_null ($node->getParent())) {

					$additionalRoot->addNode ($node);

				}
			}

		}

		return $this;
	}
	
	
	


	/**
	 * @override
	 * @param Varien_Data_Tree_Node $additionalRoot
	 * @return Df_Cms_Block_Frontend_Catalog_Navigation_Submenu
	 */
	public function appendMenu_1_7 (Varien_Data_Tree_Node $additionalRoot) {

		if (
				df_cfg()->cms()->hierarchy()->isEnabled()
			&&
				df_enabled (Df_Core_Feature::CMS_2)
		) {

			foreach (df_helper()->cms()->getTree()->getTree()->getNodes() as $node) {
				/** @var Df_Cms_Varien_Data_Tree_Node $node */
				df_assert ($node instanceof Df_Cms_Varien_Data_Tree_Node);

				if (is_null ($node->getParent())) {
					$additionalRoot->addChild ($node);
				}
			}

		}

		return $this;
	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Block_Frontend_Catalog_Navigation_Submenu';
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
