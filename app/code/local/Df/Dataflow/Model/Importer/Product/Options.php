<?php



class Df_Dataflow_Model_Importer_Product_Options extends Df_Dataflow_Model_Importer_Product_Specialized {



	/**
	 * @override
	 * @return Df_Dataflow_Model_Importer_Product_Options
	 */
	public function process () {


		foreach ($this->getImportedRow () as $key => $value) {

			/** @var string $key */
			/** @var string|null $value */

			df_assert_string ($key);


			/**
			 * Обратите внимание, что значение может отсутствовать
			 * (пустая ячейка в таблице)
			 */
			if (!is_null ($value)) {
				df_assert_string ($value);
			}


			foreach ($this->getImporters () as $importer) {

				/** @var Df_Dataflow_Model_Importer_Product_Options_Format_Abstract $importer */

				df_assert ($importer instanceof Df_Dataflow_Model_Importer_Product_Options_Format_Abstract);

				if ($importer->canProcess ($key)) {


					df_assert (is_object ($importer));


					/** @var string $importerClass  */
					$importerClass = get_class ($importer);

					df_assert_string ($importerClass);



					/** @var Df_Dataflow_Model_Importer_Product_Options_Format_Abstract $freshImporter  */
					$freshImporter = new $importerClass;

					df_assert ($freshImporter instanceof Df_Dataflow_Model_Importer_Product_Options_Format_Abstract);


					$freshImporter

						->setData (

							array (

								Df_Dataflow_Model_Importer_Product_Options_Format_Abstract
									::PARAM_PRODUCT => $this->getProduct ()
								,


								Df_Dataflow_Model_Importer_Product_Options_Format_Abstract
									::PARAM_IMPORTED_KEY => df_trim ($key)

								,
								Df_Dataflow_Model_Importer_Product_Options_Format_Abstract
									::PARAM_IMPORTED_VALUE => $value
							)
						)
					;



					$freshImporter->process();


				    $this->getProduct ()
					    ->setData (Df_Catalog_Const::PRODUCT_PARAM_HAS_OPTIONS, true)
					;

					$this->getProduct ()->save ();

				}

			}

		}
		return $this;
	}










	/**
	 * @return array
	 */
	protected function getImporters () {

		if (!isset ($this->_importers)) {


			/** @var array $result  */
			$result =
				array_map (
					'df_model'
					,
					array (
						Df_Dataflow_Model_Importer_Product_Options_Format_Json
							::getNameInMagentoFormat()
						,
						Df_Dataflow_Model_Importer_Product_Options_Format_Simple
							::getNameInMagentoFormat()
					)
				)
			;

			df_assert_array ($result);

			$this->_importers = $result;
		}

		df_assert_array ($this->_importers);

		return $this->_importers;
	}



	/**
	 * @var array
	 */
	private $_importers;




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Importer_Product_Options';
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