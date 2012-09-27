<?php


/**
 * Применимые к текущему заказу подарочные акции
 */
class Df_PromoGift_Model_PromoAction_Collection extends Df_Varien_Data_Collection {



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Model_PromoAction_Collection';
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
     * Load data
     *
     * @return  Varien_Data_Collection
     */
    public function loadData($printQuery = false, $logQuery = false) {

		if (!$this->isLoaded()) {

			parent::loadData ($printQuery, $logQuery);

			$this->_setIsLoaded (true);


			/**
			 * Наполняем коллекцию элементами
			 */

			foreach ($this->getApplicableGiftingRules() as $rule) {
				/** @var Mage_SalesRule_Model_Rule $rule  */

				$promoAction =
					df_model (
						Df_PromoGift_Model_PromoAction::getNameInMagentoFormat()
						,
						array (
							Df_PromoGift_Model_PromoAction::PARAM_RULE => $rule
						)
					)
				;
				/** @var Df_PromoGift_Model_PromoAction $promoAction */


				/**
				 * Итак, мы нашли применимые к заказу правила.
				 * Однако из этих правил надо ещё отбраковать правила без товаров.
				 *
				 * Например, правило было создано, а потом подарочный товар закончился на складе.
				 *
				 * В процессе такой проверки мы определяем для каждого правила
				 * множество относящися к нему товаров-подарков.
				 * И эти подарки неплохо бы кешировать.
				 */


				if ($promoAction->hasGifts ()) {

					$this->addItem($promoAction);
				}

			}


		}

        return $this;
    }







	/**
	 * @return Df_Varien_Data_Collection
	 */
	private function getApplicableGiftingRules () {

		if (!isset ($this->_applicableGiftingRules)) {


			$result =
				Mage::getResourceModel (
					Df_PromoGift_Model_Mysql4_Rule_Collection::CLASS_MF)
			;
			/** @var Df_PromoGift_Model_Mysql4_Rule_Collection $result */



			/**
			 * Отбраковываем правила, не относящиеся к обрабатываемому сайту $website
			 */
			$result
				->addWebsiteFilter (
					array (
						  Mage::app()->getWebsite()->getId ()
					)
				)
			;


			/**
			 * Отбраковываем ещё не начавшиеся правила
			 */
			$result->addNotStartedYetRulesExclusionFilter ();




			/**
			 * Отбираем применимые к данному заказу правила
			 */
			$byCurrentQuote =
				df_model (
					Df_PromoGift_Model_Filter_Rule_Collection_ByCurrentQuote::getNameInMagentoFormat()
				)
			;
			/** @var Df_PromoGift_Model_Filter_Rule_Collection_ByCurrentQuote $byCurrentQuote */




			$result = $byCurrentQuote->filter ($result);
			/** @var Df_Varien_Data_Collection $rules */

			df_assert ($result instanceof Df_Varien_Data_Collection);
			df_result_collection ($result, Df_SalesRule_Const::RULE_CLASS);

			$this->_applicableGiftingRules = $result;

		}

		return $this->_applicableGiftingRules;
	}



	/**
	 * @var Df_PromoGift_Model_Mysql4_Rule_Collection
	 */
	private $_applicableGiftingRules;





	/**
	 * @override
	 * @return string
	 */
	protected function getItemClass () {

		/** @var string $result */
		$result = Df_PromoGift_Model_PromoAction::getClass();

		return $result;
	}


}


