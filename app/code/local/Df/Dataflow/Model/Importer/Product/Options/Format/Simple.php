<?php


class Df_Dataflow_Model_Importer_Product_Options_Format_Simple extends Df_Dataflow_Model_Importer_Product_Options_Format_Abstract {




	/**
	 * @return Df_Dataflow_Model_Importer_Product_Options_Format_Simple
	 */
	public function process () {
		
		$this->deletePreviousOptionWithSameTitle ();
		
		
		/** @var $option Df_Catalog_Model_Product_Option */
		$option = df_model(Df_Catalog_Const::DF_PRODUCT_OPTION_CLASS_MF);
		
		df_assert ($option instanceof Df_Catalog_Model_Product_Option);
		

		$option
			->setProduct($this->getProduct ())
			->addOption (
				array (
					'type' => 'drop_down'
					,
					'is_require' => 1
					,
					'title' => $this->getImportedKey ()
					,
					'values' => $this->getValues ()
				)
			)
			->saveOptions()
		;


		return $this;
	}






	/**
	 * @return string
	 */
	protected function getPattern () {

		$result = "#^\s*df_custom_options\s*\[([^\]]+)\]\s*$#u";

		df_result_string ($result);

		return $result;
	}







	/**
	 * @return string
	 */
	protected function getImportedKey () {

		if (!isset ($this->_importedKey)) {

			/** @var array $matches  */
			$matches = array ();

			/** @var int|false $r  */
			$r = preg_match ($this->getPattern (), parent::getImportedKey(), $matches);

			df_assert (FALSE !== $r);


			/** @var string $result  */
			$result = df_a ($matches, 1);
			

			df_assert_string ($result);

			$this->_importedKey = $result;

		}

		df_result_string ($this->_importedKey);

		return $this->_importedKey;

	}



	/**
	 * @var string
	 */
	private $_importedKey;


	
	
	
	
	

	/**
	 * @return Df_Dataflow_Model_Importer_Product_Options_Format_Simple
	 */
	private function deletePreviousOptionWithSameTitle () {  
		
		/** @var array $options  */
		$options =
			$this
				->getProduct ()
				->getOptionsByTitle (
					$this->getImportedKey ()
				)
		;
		
		df_assert_array ($options);

		foreach ($options as $option) {
			
			/** @var Df_Catalog_Model_Product_Option $option */
			
			df_assert ($option instanceof Df_Catalog_Model_Product_Option);
			
			$option->deleteWithDependencies ();
		}

		return $this;
	}

		
	/**
	 * @var array
	 */
	private $_values;

	
	
	
	

	/**
	 * @return array
	 */
	private function getValues () {
		
		if (!isset ($this->_values)) {
			
			$this->_values = array ();
			$ordering = 0;
			
			foreach ($this->getValuesTitles () as $title) {
				
				$this->_values []=
					array (
						'title' => $title
						,
						'price' => 0
						,
						'price_type' => 'fixed'
						,
						'sort_order' => $ordering++
					)
				;
			}
		}
		return $this->_values;
	}







	/**
	 * @return array
	 */
	private function getValuesTitles () {

		if (!isset ($this->_valuesTitles)) {

			/** @var array $result  */
			$result =
				df_parse_csv (
					$this->getImportedValue ()
				)
			;
			
			df_assert_array ($result);
			
			$this->_valuesTitles = $result;

		}
		
		df_result_array ($this->_valuesTitles );

		return $this->_valuesTitles;
	}



	/**
	 * @var array
	 */
	private $_valuesTitles;






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Importer_Product_Options_Format_Simple';
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