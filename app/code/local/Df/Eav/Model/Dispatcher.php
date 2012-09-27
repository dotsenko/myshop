<?php


class Df_Eav_Model_Dispatcher extends Df_Core_Model_Abstract {




	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function core_collection_abstract_load_after (
		Varien_Event_Observer $observer
	) {

		/**
		 * Здесь нельзя вызывать df_enabled из-за рекурсии:
		 *
				df_enabled( )	..\Dispatcher.php:17
				Df_Licensor_Model_Feature->isEnabled( )	..\other.php:438
				Df_Licensor_Model_Feature->calculateEnabled( )	..\Feature.php:66
				Df_Licensor_Helper_Data->getStores( )	..\Feature.php:130
				Df_Licensor_Model_Collection_Store->loadAll( )	..\Data.php:91
				Varien_Data_Collection->getIterator( )	..\Collection.php:213
				Mage_Core_Model_Resource_Store_Collection->load( )	..\Collection.php:729
				Varien_Data_Collection_Db->load( )	..\Collection.php:174
				Mage_Core_Model_Resource_Db_Collection_Abstract->_afterLoad( )	..\Db.php:536
				Mage::dispatchEvent( )	..\Abstract.php:634
				Mage_Core_Model_App->dispatchEvent( )	..\Mage.php:416
				Mage_Core_Model_App->_callObserverMethod( )	..\App.php:1288
				Df_Eav_Model_Dispatcher->core_collection_abstract_load_after( )	..\App.php:1307
				df_enabled( )	..\Dispatcher.php:17
		 */


		try {

			/**
			 * Для ускорения работы системы проверяем класс коллекции прямо здесь,
			 * а не в обработчике события.
			 *
			 * Это позволяет нам не создавать обработчики событий для каждой коллекции.
			 */

			$collection = $observer->getData ('collection');

			if (
					df_cfg()->localization()->translation()->admin()->isEnabled()
				&&
					df_helper()->eav()->check()->entityAttributeCollection ($collection)
			) {

				df_handle_event (
					Df_Eav_Model_Handler_TranslateAttributeLabelsInCollection
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Core_Collection_Abstract_LoadAfter
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
	public function eav_entity_attribute_load_after (
		Varien_Event_Observer $observer
	) {


		try {

			if (
					df_cfg()->localization()->translation()->admin()->isEnabled()
				&&
					df_enabled (Df_Core_Feature::LOCALIZATION)
			) {

				df_handle_event (
					Df_Eav_Model_Handler_TranslateAttributeLabel
						::getNameInMagentoFormat ()
					,
					Df_Core_Model_Event_Eav_Entity_Attribute_LoadAfter
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
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Eav_Model_Dispatcher';
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


