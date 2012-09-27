<?php


class Df_PromoGift_Block_Chooser_PromoAction extends Df_Core_Block_Template {






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Block_Chooser_PromoAction';
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
	 * @return string
	 */
	public function getDescription () {

		$result =
			$this->getPromoAction()->getRule()
				->getData ('description')
		;

		$result = df_convert_null_to_empty_string ($result);

		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_string ($result);
		/*************************************/

		return $result;
	}






	/**
	 * @return Df_Varien_Data_Collection
	 */
	public function getGifts () {
		return $this->getPromoAction()->getGifts();
	}






	/**
	 * @param Df_PromoGift_Model_Gift $gift
	 * @param  string $template
	 * @return string
	 */
	public function renderGift (Df_PromoGift_Model_Gift $gift, $template) {

		/*************************************
		 * Проверка входных параметров метода
		 */
		df_param_string ($template, 1);
		/*************************************/

		$block =
			df_mage()->core()->layout()
				->createBlock(
					Df_PromoGift_Block_Chooser_Gift::getNameInMagentoFormat()
					,
					Df_Core_Const::T_EMPTY
				)
		;
		/** @var Df_PromoGift_Block_Chooser_Gift $block */


		df_assert ($block instanceof Df_PromoGift_Block_Chooser_Gift);


		$block->setGift ($gift);


		$block->setTemplate ($template);


		$result = $block->renderView ();



		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_string ($result);
		/*************************************/


		return $result;
	}







	/**
	 * @return Df_PromoGift_Model_PromoAction
	 */
	public function getPromoAction () {

		$result = $this->_promoAction;

		df_assert ($result instanceof Df_PromoGift_Model_PromoAction);

		return $result;
	}





	/**
	 * @param Df_PromoGift_Model_PromoAction $promoAction
	 * @return Df_PromoGift_Block_Chooser_PromoAction
	 */
	public function setPromoAction (Df_PromoGift_Model_PromoAction $promoAction) {
		$this->_promoAction = $promoAction;
		return $this;
	}


	/**
	 * @var Df_PromoGift_Model_PromoAction
	 */
	private $_promoAction;



}

