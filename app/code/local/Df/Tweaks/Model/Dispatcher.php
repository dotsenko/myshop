<?php


class Df_Tweaks_Model_Dispatcher extends Df_Core_Model_Abstract {


	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function controller_action_layout_generate_blocks_after (Varien_Event_Observer $observer) {

		try {

			if (df_enabled (Df_Core_Feature::TWEAKS)) {

				df_handle_event (
					Df_Tweaks_Model_Handler_Header_AdjustLinks
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
						::getNameInMagentoFormat ()
					,
					$observer
				);

				df_handle_event (
					Df_Tweaks_Model_Handler_Footer_AdjustLinks
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
						::getNameInMagentoFormat ()
					,
					$observer
				);

				df_handle_event (
					Df_Tweaks_Model_Handler_Footer_AdjustCopyright
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
						::getNameInMagentoFormat ()
					,
					$observer
				);


				if (
						df_handle_presents(Df_Catalog_Const::LAYOUT_HANDLE__PRODUCT_VIEW)
					&&
						df_cfg()->tweaks()->catalog()->product()->view ()->getHideTags()
				) {
					df()->layout()->removeBlock ('product_tag_list');
				}


				df_handle_event (
					Df_Tweaks_Model_Handler_Account_AdjustLinks
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
						::getNameInMagentoFormat ()
					,
					$observer
				);

				df_handle_event (
					Df_Tweaks_Model_Handler_ProductBlock_Recent_Compared
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
						::getNameInMagentoFormat ()
					,
					$observer
				);

				df_handle_event (
					Df_Tweaks_Model_Handler_ProductBlock_Recent_Viewed
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
						::getNameInMagentoFormat ()
					,
					$observer
				);

				df_handle_event (
					Df_Tweaks_Model_Handler_ProductBlock_Wishlist
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
						::getNameInMagentoFormat ()
					,
					$observer
				);

				df_handle_event (
					Df_Tweaks_Model_Handler_AdjustBanners
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
						::getNameInMagentoFormat ()
					,
					$observer
				);

				df_handle_event (
					Df_Tweaks_Model_Handler_AdjustPaypalLogo
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
						::getNameInMagentoFormat ()
					,
					$observer
				);

				df_handle_event (
					Df_Tweaks_Model_Handler_AdjustPoll
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
						::getNameInMagentoFormat ()
					,
					$observer
				);

				df_handle_event (
					Df_Tweaks_Model_Handler_AdjustNewsletterSubscription
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
						::getNameInMagentoFormat ()
					,
					$observer
				);

				df_handle_event (
					Df_Tweaks_Model_Handler_AdjustCartMini
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
						::getNameInMagentoFormat ()
					,
					$observer
				);

				df_handle_event (
					Df_Tweaks_Model_Handler_AdjustCartPage
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
						::getNameInMagentoFormat ()
					,
					$observer
				);

			}

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}


	}




	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function catalog_product_is_salable_after (Varien_Event_Observer $observer) {
		if (df_installed () && df_enabled (Df_Core_Feature::TWEAKS)) {
			if (
					(
							df_handle_presents (Df_Catalog_Const::LAYOUT_HANDLE__PRODUCT_VIEW)
						&&
							df_cfg()->tweaks()->catalog()->product()->view()->getHideAddToCart()
					)
				||
					(
							df_cfg()->tweaks()->catalog()->product()->_list()->getHideAddToCart()
						&&
							df_helper()->tweaks()->isItCatalogProductList()
					)
			) {

				$salable = $observer->getData ('salable');
				/** @var Varien_Object $salable */

				$salable->setData ('is_salable', false);
			}
		}
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Model_Dispatcher';
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












