<?php


class Df_PromoGift_Model_PromoAction extends Df_Core_Model_Abstract {





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Model_PromoAction';
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
	 * @return int
	 */
	public function getId () {

		$result = (int)$this->getRule ()->getId ();


		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_integer ($result);
		/*************************************/

		return $result;
	}



	/**
	 * @return bool
	 */
	public function hasGifts () {

		if (!isset ($this->_hasGifts)) {

			$result = 0 < count ($this->getGifts());


			/*************************************
			 * Проверка результата работы метода
			 */
			df_result_boolean ($result);
			/*************************************/

			$this->_hasGifts = $result;

		}

		return $this->_hasGifts;
	}


	/**
	 * @var bool
	 */
	private $_hasGifts;






	/**
	 * @return Df_Varien_Data_Collection
	 */
	public function getGifts () {

		if (!isset ($this->_gifts)) {


			$allGifts = Mage::getResourceModel (Df_PromoGift_Const::GIFT_COLLECTION_CLASS_MF);
			/** @var Df_PromoGift_Model_Mysql4_Gift_Collection $allGifts */


			$filter =
				df_model (
					Df_PromoGift_Model_Filter_Gift_Collection_ByRuleGiven::getNameInMagentoFormat()
					,
					array (
						Df_PromoGift_Model_Validate_Gift_RelatedToRuleGiven::PARAM_RULE_ID =>
							$this->getRule()->getId ()
					)
				)
			;
			/** @var Df_PromoGift_Model_Filter_Gift_Collection_ByRuleGiven $filter */



			$result = $filter->filter ($allGifts);
			/** @var Df_Varien_Data_Collection $result */



			/**
			 * Конечно, объекты класса Df_PromoGift_Model_Gift умеют сами загужать
			 * относящиеся к ним модели (правило, товар, сайт),
			 * но если они будут делать это по-отдельности — они создадут много запросов к БД.
			 *
			 * Эффективней явно дать им нужные им модели.
			 */


			$website = Mage::app()->getWebsite();
			/** @var Mage_Core_Model_Website */

			$mapToProducts =
				df_model (
					Df_PromoGift_Model_Filter_Gift_Collection_MapToProducts
						::getNameInMagentoFormat()
				)
			;
			/** @var Df_PromoGift_Model_Filter_Gift_Collection_MapToProducts $mapToProducts */


			$products = $mapToProducts->filter ($result);
			/** @var Df_Catalog_Model_Resource_Product_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection $products */

			df_helper()->catalog()->assert()->productCollection ($products);


			foreach ($result as $gift) {
				/** @var Df_PromoGift_Model_Gift $gift */

				$gift->setWebsite ($website);
				$gift->setRule ($this->getRule());

				$gift
					->setProduct (
						$products->getItemById (
							$gift->getProductId ()
						)
					)
				;

			}


			df_assert ($result instanceof Df_Varien_Data_Collection);
			df_result_collection ($result, Df_PromoGift_Model_Gift::getClass());


			$this->_gifts = $result;

		}

		return $this->_gifts;

	}

	/**
	 * @var Df_Varien_Data_Collection
	 */
	private $_gifts;







	/**
	 * @return Mage_SalesRule_Model_Rule
	 */
	public function getRule () {
		return $this->cfg (self::PARAM_RULE);
	}




	const PARAM_RULE = 'rule';


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM_RULE, Df_SalesRule_Const::RULE_CLASS
			)
		;
	}


}


