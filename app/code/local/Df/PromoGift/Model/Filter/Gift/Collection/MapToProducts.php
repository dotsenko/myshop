<?php



/**
 * Для данного множества подарков возвращает соответствующее ему множество товаров
 */
class Df_PromoGift_Model_Filter_Gift_Collection_MapToProducts
	extends Df_Core_Model_Abstract
	implements Zend_Filter_Interface {


	/**
	 *
	 * @param  mixed $value
	 * @throws Zend_Filter_Exception If filtering $value is impossible
	 * @return Df_Catalog_Model_Resource_Product_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
	 */
	public function filter ($value) {

		df_assert ($value instanceof Df_Varien_Data_Collection);
		df_param_collection ($value, Df_PromoGift_Model_Gift::getClass(), 0);

		/** @var Df_Varien_Data_Collection $value */


		/**
		 * А вот здесь мы можем создать коллекцию товаров
		 */

		$productIds =
			$value->getColumnValues (
				Df_PromoGift_Const::DB__PROMO_GIFT__PRODUCT_ID
			)
		;


		$result = Mage::getModel (Df_Catalog_Const::PRODUCT_CLASS_MF)->getCollection();
		/** @var  Df_Catalog_Model_Resource_Product_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $result */

		df_helper()->catalog()->assert()->productCollection ($result);

		$result->addAttributeToSelect (Df_Catalog_Const::ALL_ATTRIBUTES);



		$result->addIdFilter (array_values ($productIds));


		$result->addIdFilter (array_values ($productIds));


		/**
		 * Иначе адреса будут вида
		 * http://example.com/catalog/product/view/id/119/s/coalesce-shirt/category/34/
		 */
		$result->addUrlRewrite();


		$result->load ();


		/*************************************
		 * Проверка результата работы метода
		 */
		df_helper()->catalog()->assert()->productCollection ($result);
		/*************************************/

		return $result;
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Model_Filter_Gift_Collection_MapToProducts';
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