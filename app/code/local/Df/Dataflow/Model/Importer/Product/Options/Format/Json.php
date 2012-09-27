<?php


class Df_Dataflow_Model_Importer_Product_Options_Format_Json
	extends Df_Dataflow_Model_Importer_Product_Options_Format_Abstract {





	/**
	 * @return Df_Dataflow_Model_Importer_Product_Options_Format_Json
	 */
	public function process () {

		try {

			/** @var string|null $customOptionsAsJson  */
			$customOptionsAsJson = $this->getImportedValue ();

			if (!is_null ($customOptionsAsJson)) {
				df_assert_string ($customOptionsAsJson);
			}


			if (!is_null ($customOptionsAsJson)) {

				$this->getProduct ()->deleteOptions ();


				/** @var array|null $customOptionsData  */
				$customOptionsData =
					Zend_Json::decode(
						$customOptionsAsJson
					)
				;


				if (!is_null ($customOptionsData)) {
					df_assert_array ($customOptionsData);
				}


				if (!is_null ($customOptionsData)) {


					/** @var Mage_Catalog_Model_Product_Option $aggregateOption  */
					$aggregateOption =
						df_model (
							Df_Catalog_Const::PRODUCT_OPTION_CLASS_MF
						)
					;

					df_assert ($aggregateOption instanceof Mage_Catalog_Model_Product_Option);



					$aggregateOption->setProduct ($this->getProduct ());

					foreach ($customOptionsData as $customOptionData) {

						/** @var array Mage_Catalog_Model_Product_Option */
						
						df_assert_array ($customOptionData);
						


						
						/**
						 * If option has id then Magento assumes that option already exists in DB
						 * So, because we delete all options above, we unset option_id too.
						 *
						 *
						 * Если у опции присутствует идентификатор, то Magento считает, 
						 * что опция уже присутствует в базе данных, 
						 * и записывает опцию в базе данных алгоритмом UPDATE, а не INSERT.
						 * 
						 * 
						 * Однако, мы удалили все опции выше (Df_Catalog_Model_Product::deleteOptions()),
						 * поэтому убираем у импортируемой опции идентификатор,
						 * чтобы Magento считала опцию совершенно новой и использовала алгоритм INSERT.
						 */
						unset ($customOptionData['option_id']);
						

						/** @var array|null $optionValuesData  */
						$optionValuesData = df_a ($customOptionData, '_values');

						if (!is_null ($optionValuesData)) {
							df_assert_array ($optionValuesData);
						}


						if (!is_null ($optionValuesData)) {

							foreach ($optionValuesData as &$optionValueData) {

								/** @var array $optionValueData */

								df_assert_array ($optionValueData);

								unset ($optionValueData['option_type_id']);
							}

						}


						$customOptionData ['values'] = $optionValuesData;

						unset ($customOptionData['_values']);

						unset ($customOptionData['_options']);


						$aggregateOption->addOption ($customOptionData);

					}

					$aggregateOption->saveOptions();
				}
			}
		}

		catch (Exception $e) {
			df_handle_entry_point_exception($e, true);
		}


		return $this;
	}






	/**
	 * @return string
	 */
	protected function getPattern () {

		/** @var string $result  */
		$result = "#^\s*df_custom_options\s*$#";

		df_result_string ($result);

		return $result;
	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Importer_Product_Options_Format_Json';
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