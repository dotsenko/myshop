<?php


class Df_PromoGift_Block_Chooser_Gift extends Df_Core_Block_Template {






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Block_Chooser_Gift';
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
	 * @param Mage_Catalog_Model_Product $product
	 * @param  string $template
	 * @return string
	 */
	public function renderProduct (Mage_Catalog_Model_Product $product, $template) {

		/*************************************
		 * Проверка входных параметров метода
		 */
		df_param_string ($template, 1);
		/*************************************/

		$block =
			df_mage()->core()->layout()
				->createBlock(
					Df_PromoGift_Block_Chooser_Product::getNameInMagentoFormat()
					,
					Df_Core_Const::T_EMPTY
				)
		;
		/** @var Df_PromoGift_Block_Chooser_Product $block */


		df_assert ($block instanceof Df_PromoGift_Block_Chooser_Product);


		$block->setProduct ($product);


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
	 * @return Df_PromoGift_Model_Gift
	 */
	public function getGift () {

		$result = $this->_gift;

		df_assert ($result instanceof Df_PromoGift_Model_Gift);

		return $result;
	}





	/**
	 * @param Df_PromoGift_Model_Gift $gift
	 * @return Df_PromoGift_Block_Chooser_Gift
	 */
	public function setGift (Df_PromoGift_Model_Gift $gift) {
		$this->_gift = $gift;
		return $this;
	}


	/**
	 * @var Df_PromoGift_Model_Gift
	 */
	private $_gift;


}

