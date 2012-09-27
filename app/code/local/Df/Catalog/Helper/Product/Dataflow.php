<?php


class Df_Catalog_Helper_Product_Dataflow extends Mage_Catalog_Helper_Data {
	
	
	
	
	/**
	 * @param string $productType
	 * @return array
	 */
	public function getInventoryFieldsByProductType ($productType) {

		df_param_string ($productType, 0);
	
		if (!isset ($this->_inventoryFieldsByProductType [$productType])) {
	
			/** @var array $result  */
			$result =
				array ()
			;


			foreach ($this->getNodesByTag ('inventory') as $code => $inventoryNode) {

				/** @var string $code */
				/** @var Mage_Core_Model_Config_Element $inventoryNode */



				/** @var SimpleXMLElement $productTypesNode  */
				$productTypesNode = @$inventoryNode->product_type;

				df_assert ($productTypesNode instanceof SimpleXMLElement);


				/** @var array $productTypes  */
				$productTypes = array ();



				foreach ($productTypesNode->children() as $productTypeNode) {

					/** @var SimpleXMLElement $productTypeNode  */

					df_assert ($productTypeNode instanceof SimpleXMLElement);


					/** @var string $productTypeName  */
					$productTypeName = $productTypeNode->getName();

					df_assert_string ($productTypeName);


					$productTypes []= $productTypeName;

				}


				if (in_array ($productType, $productTypes)) {

					$result []= $code;

					if ($inventoryNode->is ('ise_config')) {

						$result []= 'use_config_' . $code;

					}


				}

			}

	
			df_assert_array ($result);
	
			$this->_inventoryFieldsByProductType [$productType] = $result;
	
		}
	
	
		df_result_array ($this->_inventoryFieldsByProductType [$productType]);
	
		return $this->_inventoryFieldsByProductType [$productType];
	
	}
	
	
	/**
	* @var array
	*/
	private $_inventoryFieldsByProductType = array ();	
	
	
	
	
	

	/**
	 * @return array
	 */
	public function getInventoryFields () {
	
		if (!isset ($this->_inventoryFields)) {

			/** @var array $result  */
			$result =
				array ()
			;


			/** @var array $inventoryNodes  */
			$inventoryNodes =
				$this->getNodesByTag ('inventory')
			;


			foreach ($inventoryNodes as $code => $inventoryNode) {

				/** @var string $code */
				/** @var Mage_Core_Model_Config_Element $inventoryNode */

				df_assert_string ($code);
				df_assert ($inventoryNode instanceof Mage_Core_Model_Config_Element);

				$result []= $code;

                if ($inventoryNode->is('use_config')) {
                    $result []= 'use_config_'.$code;
                }
			}
	
			df_assert_array ($result);
	
			$this->_inventoryFields = $result;
	
		}
	
	
		df_result_array ($this->_inventoryFields);
	
		return $this->_inventoryFields;
	
	}
	
	
	/**
	* @var array
	*/
	private $_inventoryFields;	






	/**
	 * @return array
	 */
	public function getNumericFields () {

		$result = $this->getFieldsByTag ('to_number');

		df_result_array ($result);

		return $result;
	}


	



	/**
	 * @return array
	 */
	public function getIgnoredFields () {

		$result = $this->getFieldsByTag ('ignore');

		df_result_array ($result);

		return $result;
	}




	/**
	 * @return array
	 */
	public function getRequiredFields () {
	
		$result = $this->getFieldsByTag ('required');
	
		df_result_array ($result);
	
		return $result;
	}





	/**
	 * @return array
	 */
	private function getFieldsByTag ($tag) {

		df_assert_string ($tag, 0);

		if (!isset ($this->_fieldsByTag [$tag])) {

			/** @var array $result  */
			$result =
				array_keys (
					$this->getNodesByTag($tag)
				)
			;

			df_assert_array ($result);

			$this->_fieldsByTag [$tag] = $result;

		}


		df_result_array ($this->_fieldsByTag [$tag]);

		return $this->_fieldsByTag [$tag];

	}


	/**
	 * @var array
	 */
	private $_fieldsByTag = array ();







	/**
	 * @return array
	 */
	private function getNodesByTag ($tag) {

		df_assert_string ($tag, 0);

		if (!isset ($this->_nodesByTag [$tag])) {

			/** @var array $result  */
			$result =
				array ()
			;

			foreach ($this->getFieldset() as $code => $node) {

				/** @var string $code */
				/** @var Mage_Core_Model_Config_Element $node */

				df_assert_string ($code);
				df_assert ($node instanceof Mage_Core_Model_Config_Element);

				if ($node->is ($tag)) {
					$result[$code] = $node;
				}
			}

			df_assert_array ($result);

			$this->_nodesByTag [$tag] = $result;

		}


		df_result_array ($this->_nodesByTag [$tag]);

		return $this->_nodesByTag [$tag];

	}


	/**
	 * @var array
	 */
	private $_nodesByTag = array ();









	/**
	 * @return SimpleXMLElement
	 */
	private function getFieldset () {

		if (!isset ($this->_fieldset)) {

			/** @var SimpleXMLElement|null $result  */
			$result =
				Mage::getConfig()->getFieldset('catalog_product_dataflow', 'admin')
			;

			/**
			 * Обратите внимание, что значение null мы не должны были получить,
			 * потому что работаем с системными параметрами (они всегда должны быть)
			 */

			df_assert ($result instanceof SimpleXMLElement);

			$this->_fieldset = $result;

		}


		df_assert ($this->_fieldset instanceof SimpleXMLElement );

		return $this->_fieldset;

	}


	/**
	* @var SimpleXMLElement
	*/
	private $_fieldset;





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Helper_Product_Dataflow';
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

