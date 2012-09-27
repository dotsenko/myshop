<?php

class Df_Eav_Helper_Data extends Mage_Core_Helper_Abstract {



	/**
	 * Обновляет глобальный кэш EAV.
	 * Это нужно, например, при добавлении новых свойств к прикладным типам товаров.
	 *
	 * @return Df_Eav_Helper_Data
	 */
	public function cleanCache () {

		Mage::unregister ('_singleton/eav/config');

		return $this;
	}



	/**
	 * @return int
	 */
	public function getProductEntityTypeId () {

		if (!isset ($this->_productEntityTypeId)) {

			/** @var Mage_Eav_Model_Entity $eavEntity  */
			$eavEntity = df_model ('eav/entity');

			df_assert ($eavEntity instanceof Mage_Eav_Model_Entity);


			$eavEntity->setType('catalog_product');


			/** @var int $result  */
			$result = $eavEntity->getTypeId();


			df_assert_integer ($result);

			$this->_productEntityTypeId = $result;

		}


		df_result_integer ($this->_productEntityTypeId);

		return $this->_productEntityTypeId;

	}


	/**
	* @var int
	*/
	private $_productEntityTypeId;





	/**
	 * @param Mage_Eav_Model_Entity_Attribute $attribute
	 * @return Df_Eav_Helper_Data
	 */
	public function translateAttribute (Mage_Eav_Model_Entity_Attribute $attribute) {

		$attribute
			->setData (
				'frontend_label'
				,
				$this->__ (
					$attribute->getData ('frontend_label')
				)
			)
		;

		return $this;

	}


	
	
	/**
	 * @return Df_Eav_Helper_Assert
	 */
	public function assert () {

		/** @var Df_Eav_Helper_Assert $result  */
		$result =
			Mage::helper (Df_Eav_Helper_Assert::getNameInMagentoFormat())
		;


		df_assert ($result instanceof Df_Eav_Helper_Assert);

		return $result;

	}




	/**
	 * @return Df_Eav_Helper_Check
	 */
	public function check () {

		/** @var Df_Eav_Helper_Check $result  */
		$result =
			Mage::helper (Df_Eav_Helper_Check::getNameInMagentoFormat())
		;


		df_assert ($result instanceof Df_Eav_Helper_Check);

		return $result;

	}




    /**
	 * @param array $args
     * @return string
     */
    public function translateByParent (array $args) {

		/** @var string $result  */
        $result =
			df_helper()->localization()->translation()->translateByModule (
				$args, self::DF_PARENT_MODULE
			)
		;

		df_result_string ($result);

	    return $result;
    }



	const DF_PARENT_MODULE = 'Mage_Eav';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Eav_Helper_Data';
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