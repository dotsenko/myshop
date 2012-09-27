<?php


class Df_Directory_Model_Dispatcher extends Df_Core_Model_Abstract {



	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function core_collection_abstract_load_before (
		Varien_Event_Observer $observer
	) {

		/**
		 * Для ускорения работы системы проверяем класс коллекции прямо здесь,
		 * а не в обработчике события.
		 *
		 * Это позволяет нам не создавать обработчики событий для каждой коллекции.
		 */

		$collection = $observer->getData ('collection');

		if (df_helper()->directory()->check()->regionCollection($collection)) {

			if (df_enabled (Df_Core_Feature::DIRECTORY)) {

				try {

					df_handle_event (
						Df_Directory_Model_Handler_OrderRegions
							::getNameInMagentoFormat ()
						,
						Df_Core_Model_Event_Core_Collection_Abstract_LoadBefore
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
	}





	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function core_collection_abstract_load_after (
		Varien_Event_Observer $observer
	) {

		/**
		 * Для ускорения работы системы проверяем класс коллекции прямо здесь,
		 * а не в обработчике события.
		 *
		 * Это позволяет нам не создавать обработчики событий для каждой коллекции.
		 */

		$collection = $observer->getData ('collection');

		if (
				df_helper()->directory()->check()->regionCollection ($collection)
			&&
				df_cfg()->directory()->regions()->ru()->getEnabled()
			&&
				df_enabled (Df_Core_Feature::DIRECTORY)
		) {

			try {

					df_handle_event (
						Df_Directory_Model_Handler_ProcessRegionsAfterLoading
							::getNameInMagentoFormat ()
						,
						Df_Core_Model_Event_Core_Collection_Abstract_LoadAfter
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
	}







	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Model_Dispatcher';
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


