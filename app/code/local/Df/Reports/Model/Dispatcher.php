<?php


class Df_Reports_Model_Dispatcher extends Df_Core_Model_Abstract {



	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function adminhtml_block_html_before (
		Varien_Event_Observer $observer
	) {

		try {

			/**
			 * Для ускорения работы системы проверяем класс блока прямо здесь,
			 * а не в обработчике события.
			 *
			 * Это позволяет нам не создавать обнаботчики событий для каждого блока.
			 */

			/** @var Mage_Core_Block_Abstract $block  */
			$block = $observer->getData ('block');



			if (
					($block instanceof Mage_Adminhtml_Block_Report_Filter_Form)
				&&
					df_cfg()->reports()->common()->enableGroupByWeek()
				&&
					df_enabled (Df_Core_Feature::REPORTS)
			) {

				df_handle_event (
					Df_Reports_Model_Handler_GroupResultsByWeek_AddOptionToFilter
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Adminhtml_Block_HtmlBefore
						::getNameInMagentoFormat ()
					,
					$observer
				);
			}



			if (
					($block instanceof Mage_Adminhtml_Block_Report_Filter_Form)
				&&
					df_cfg()->reports()->common()->needSetEndDateToTheYesterday ()
				&&
					df_enabled (Df_Core_Feature::REPORTS)
			) {

				df_handle_event (
					Df_Reports_Model_Handler_SetDefaultFilterValues
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Adminhtml_Block_HtmlBefore
						::getNameInMagentoFormat ()
					,
					$observer
				);

			}



			if (
					($block instanceof Mage_Adminhtml_Block_Report_Grid_Abstract)
				&&
					df_cfg()->reports()->common()->enableGroupByWeek ()
				&&
					df_helper()->reports()->groupResultsByWeek()->isSelectedInFilter()
				&&
					df_enabled (Df_Core_Feature::REPORTS)
			) {

				df_handle_event (
					Df_Reports_Model_Handler_GroupResultsByWeek_SetColumnRenderer
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Adminhtml_Block_HtmlBefore
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
	public function controller_action_layout_generate_blocks_after (
		Varien_Event_Observer $observer
	) {

		try {

			if (
					df_cfg()->reports()->common()->needRemoveTimezoneNotice()
				&&
					df_enabled (Df_Core_Feature::REPORTS)
			) {

				df_handle_event (
					Df_Reports_Model_Handler_RemoveTimezoneNotice
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
	public function core_collection_abstract_load_before (
		Varien_Event_Observer $observer
	) {

		try {
				df_handle_event (
					Df_Reports_Model_Handler_GroupResultsByWeek_PrepareCollection
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Core_Collection_Abstract_LoadBefore
						::getNameInMagentoFormat ()
					,
					$observer
				)
			;

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}


	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Reports_Model_Dispatcher';
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


