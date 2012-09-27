<?php


class Df_AccessControl_Model_Dispatcher extends Df_Core_Model_Abstract {



	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function catalog_product_collection_load_before (
		Varien_Event_Observer $observer
	) {

		try {
			if (df_is_admin()) {
				df_handle_event (
					Df_AccessControl_Model_Handler_Catalog_Product_Collection_ExcludeForbiddenProducts
						::getNameInMagentoFormat ()
					,
					Df_Catalog_Model_Event_Product_Collection_Load_Before
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
	public function catalog_category_collection_load_before (
		Varien_Event_Observer $observer
	) {

		try {

			if (df_is_admin()) {

				df_handle_event (
					Df_AccessControl_Model_Handler_Catalog_Category_Collection_ExcludeForbiddenCategories
						::getNameInMagentoFormat ()
					,
					Df_Catalog_Model_Event_Category_Collection_Load_Before
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
	public function admin_roles_save_after (
		Varien_Event_Observer $observer
	) {

		try {

			df_handle_event (
				Df_AccessControl_Model_Handler_RemindLastSavedRoleId
					::getNameInMagentoFormat ()
				,
				Df_Admin_Model_Event_Roles_Save_After
					::getNameInMagentoFormat ()
				,
				$observer
			);
		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}

	}








	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function controller_action_postdispatch_adminhtml_permissions_role_saverole (
		Varien_Event_Observer $observer
	) {

		try {

			df_handle_event (
				Df_AccessControl_Model_Handler_Permissions_Role_Saverole_UpdateCatalogAccessRights
					::getNameInMagentoFormat ()
				,
				Df_AccessControl_Model_Event_Permissions_Role_Saverole
					::getNameInMagentoFormat ()
				,
				$observer
			);

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}

	}


}


