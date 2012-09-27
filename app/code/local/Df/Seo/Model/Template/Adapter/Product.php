<?php


class Df_Seo_Model_Template_Adapter_Product extends Df_Seo_Model_Template_Adapter {





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Model_Template_Adapter_Product';
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











	/**
	 * @var array
	 */
	private $_propertyClasses = array ();


	/**
	 * @param  string $propertyName
	 * @return string
	 */
	protected function getPropertyClass ($propertyName) {
		if (!isset ($this->_propertyClasses [$propertyName])) {
			$this->_propertyClasses [$propertyName] =
				$this->evalPropertyClass ($propertyName)
			;
		}
		return $this->_propertyClasses [$propertyName];
	}



	/**
	 * @param  string $propertyName
	 * @return string
	 */
	private function evalPropertyClass ($propertyName) {
		$node =
			$this->getConfigNode (
		         $propertyName
	        )
		;
		if (!$node) {
			$node =
				$this->getConfigNode (
					"default"
				)
			;
		}

		/** @var string $result  */
		$result =
			df()->config()->getNodeValueAsString (
				$node
			)
		;

		return $result;
	}




	/**
	 * @param string $propertyType
	 * @return Mage_Core_Model_Config_Element
	*/
	private function getConfigNode ($propertyType) {
		return
			df()->config()->getNodeByKey (
				sprintf (
					"df/seo/template/objects/%s/properties/%s"
					,
					$this->getName ()
					,
					$propertyType
				)
			)
		;
	}



	/**
	 * @return Mage_Catalog_Model_Resource_Product|Mage_Catalog_Model_Resource_Eav_Mysql4_Product
	 */
	public function getProductResource () {
		return $this->getProduct ()->getResource();
	}



	/**
	 * @return Mage_Catalog_Model_Product
	 */
	public function getProduct () {
		return parent::getObject ();
	}


}