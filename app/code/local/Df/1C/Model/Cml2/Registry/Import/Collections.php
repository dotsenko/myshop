<?php

class Df_1C_Model_Cml2_Registry_Import_Collections extends Df_Core_Model_Abstract {


	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_Attributes
	 */
	public function getAttributes () {

		if (!isset ($this->_attributes)) {

			/** @var Df_1C_Model_Cml2_Import_Data_Collection_Attributes $result  */
			$result =
				df_model (
					Df_1C_Model_Cml2_Import_Data_Collection_Attributes::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Data_Collection_Products::PARAM__SIMPLE_XML =>
							df_helper()->_1c()->cml2()->registry()->import()->files('catalog')
								->getByRelativePath (
									Df_1C_Model_Cml2_Registry_Import_Files::FILE__IMPORT
								)
					)
				)
			;


			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_Attributes);

			$this->_attributes = $result;

		}


		df_assert ($this->_attributes instanceof Df_1C_Model_Cml2_Import_Data_Collection_Attributes);

		return $this->_attributes;

	}


	/**
	* @var Df_1C_Model_Cml2_Import_Data_Collection_Attributes
	*/
	private $_attributes;


	
	
	
	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_Categories
	 */
	public function getCategories () {
	
		if (!isset ($this->_categories)) {
	
			/** @var Df_1C_Model_Cml2_Import_Data_Collection_Categories $result  */
			$result = 
				df_model (
					Df_1C_Model_Cml2_Import_Data_Collection_Categories::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Data_Collection_Categories::PARAM__SIMPLE_XML =>
							df_helper()->_1c()->cml2()->registry()->import()->files('catalog')
								->getByRelativePath (
									Df_1C_Model_Cml2_Registry_Import_Files::FILE__IMPORT
								)
						,
						Df_1C_Model_Cml2_Import_Data_Collection_Categories::PARAM__XML_PATH_AS_ARRAY =>
							array (
								Df_Core_Const::T_EMPTY
								,
								'КоммерческаяИнформация'
								,
								'Классификатор'
								,
								'Группы'
								,
								'Группа'
							)
					)
				)
			;
	
	
			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_Categories);
	
			$this->_categories = $result;
	
		}
	
	
		df_assert ($this->_categories instanceof Df_1C_Model_Cml2_Import_Data_Collection_Categories);
	
		return $this->_categories;
	
	}
	
	/**
	* @var Df_1C_Model_Cml2_Import_Data_Collection_Categories
	*/
	private $_categories;
	




	
	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_Offers
	 */
	public function getOffers () {
	
		if (!isset ($this->_offers)) {
	
			/** @var Df_1C_Model_Cml2_Import_Data_Collection_Offers $result  */
			$result = 
				df_model (
					Df_1C_Model_Cml2_Import_Data_Collection_Offers::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Data_Collection_Offers::PARAM__SIMPLE_XML =>
							df_helper()->_1c()->cml2()->registry()->import()->files('catalog')
								->getByRelativePath (
									Df_1C_Model_Cml2_Registry_Import_Files::FILE__OFFERS
								)
					)
				)
			;
	
	
			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_Offers);
	
			$this->_offers = $result;
	
		}
	
	
		df_assert ($this->_offers instanceof Df_1C_Model_Cml2_Import_Data_Collection_Offers);
	
		return $this->_offers;
	
	}
	
	
	/**
	* @var Df_1C_Model_Cml2_Import_Data_Collection_Offers
	*/
	private $_offers;		
	
	
	
	
	
	/**
	 * @return Df_1C_Model_Cml2_Import_Data_Collection_Products
	 */
	public function getProducts () {
	
		if (!isset ($this->_products)) {
	
			/** @var Df_1C_Model_Cml2_Import_Data_Collection_Products $result  */
			$result = 
				df_model (
					Df_1C_Model_Cml2_Import_Data_Collection_Products::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Data_Collection_Products::PARAM__SIMPLE_XML =>
							df_helper()->_1c()->cml2()->registry()->import()->files('catalog')
								->getByRelativePath (
									Df_1C_Model_Cml2_Registry_Import_Files::FILE__IMPORT
								)
					)
				)
			;
	
	
			df_assert ($result instanceof Df_1C_Model_Cml2_Import_Data_Collection_Products);
	
			$this->_products = $result;
	
		}
	
	
		df_assert ($this->_products instanceof Df_1C_Model_Cml2_Import_Data_Collection_Products);
	
		return $this->_products;
	
	}
	
	
	/**
	* @var Df_1C_Model_Cml2_Import_Data_Collection_Products
	*/
	private $_products;	
	

	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Registry_Import_Collections';
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

