<?php


class Df_PromoGift_Block_Catalog_Gift_Highlighter extends Df_Core_Block_Template {







	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Block_Catalog_Gift_Highlighter';
	}


	/**
	 * Например, для класса Df_SalesRule_Block_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return
			df()->reflection()

				/**
				 * Для блоков тоже работает
				 */
				->getModelNameInMagentoFormat (
					self::getClass()
				)
		;
	}








	/**
	 * @return array
	 */
	public function getEligibleProductIds () {

		if (!isset ($this->_eligibleProductIds)) {


			$result = array ();

			foreach (df_helper ()->promoGift()->getApplicablePromoActions() as $promoAction) {
				/** @var Df_PromoGift_Model_PromoAction $promoAction */

				foreach ($promoAction->getGifts () as $gift) {
					/** @var Df_PromoGift_Model_Gift $gift */

					$result []= $gift->getProductId ();
				}
			}


			/*************************************
			 * Проверка результата работы метода
			 */
			df_result_array ($result);
			/*************************************/

			$this->_eligibleProductIds = $result;
		}

		return $this->_eligibleProductIds;

	}

	private $_eligibleProductIds;


}


