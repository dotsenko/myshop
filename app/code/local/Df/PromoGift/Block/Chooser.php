<?php


class Df_PromoGift_Block_Chooser extends Df_Core_Block_Template {



	/**
	 * @return Df_PromoGift_Model_PromoAction_Collection
	 */
	public function getApplicablePromoActions () {
		$result = df_helper ()->promoGift()->getApplicablePromoActions();

		return $result;
	}


	
	
	/**
	 * @return bool
	 */
	protected function needToShow () {

		/** @var bool $result  */
		$result =
				parent::needToShow()
			&&
				df_enabled (Df_Core_Feature::PROMO_GIFT)
			&&
				df_cfg ()->promotion()->gifts()->getEnabled()
			&&
				$this->isEnabledForCurrentPageType ()
			&&
				$this->hasDataToShow ()
		;


		df_result_boolean ($result);

		return $result;

	}	
	
	
	
	
	
	/**
	 * @return bool
	 */
	private function isEnabledForCurrentPageType () {
	
		if (!isset ($this->_enabledForCurrentPageType)) {
	
			/** @var bool $result  */
			$result =
					(
							df_handle_presents(Df_Catalog_Const::LAYOUT_HANDLE__CATEGORY_VIEW)
						&&
							df_cfg ()->promotion()->gifts()->needShowChooserOnProductListPage()
					)
				||
					(
							df_handle_presents(Df_Catalog_Const::LAYOUT_HANDLE__PRODUCT_VIEW)
						&&
							df_cfg ()->promotion()->gifts()->needShowChooserOnProductViewPage()
					)
				||
					(
							df_handle_presents('checkout_cart_index')
						&&
							df_cfg ()->promotion()->gifts()->needShowChooserOnCartPage()
					)
				||
					(
							df_handle_presents('cms_page')
						&&
							!df_handle_presents ('cms_index_index')
						&&
							df_cfg ()->promotion()->gifts()->needShowChooserOnCmsPage()
					)
				||
					(
							df_handle_presents('cms_index_index')
						&&
							df_cfg ()->promotion()->gifts()->needShowChooserOnFrontPage()
					)
			;
	
	
			df_assert_boolean ($result);
	
			$this->_enabledForCurrentPageType = $result;
	
		}
	
	
		df_result_boolean ($this->_enabledForCurrentPageType);
	
		return $this->_enabledForCurrentPageType;
	
	}
	
	
	/**
	* @var bool
	*/
	private $_enabledForCurrentPageType;		
	
	





	/**
	 * @return bool
	 */
	private function hasDataToShow () {

		if (!isset ($this->_hasDataToShow)) {

			$result =

				(0 < count ($this->getApplicablePromoActions()))

			;


			/*************************************
			 * Проверка результата работы метода
			 */
			df_result_boolean ($result);
			/*************************************/


			$this->_hasDataToShow = $result;

		}

		return $this->_hasDataToShow;
	}


	/**
	 * @var bool
	 */
	private $_hasDataToShow;








	/**
	 * @param Df_PromoGift_Model_PromoAction $promoAction
	 * @param  string $template
	 * @return string
	 */
	public function renderPromoAction (Df_PromoGift_Model_PromoAction $promoAction, $template) {

		/*************************************
		 * Проверка входных параметров метода
		 */
		df_param_string ($template, 1);
		/*************************************/

		$block =
			df_mage()->core()->layout()
				->createBlock(
					Df_PromoGift_Block_Chooser_PromoAction::getNameInMagentoFormat()
					,
					Df_Core_Const::T_EMPTY
				)
		;
		/** @var Df_PromoGift_Block_Chooser_PromoAction $block */


		df_assert ($block instanceof Df_PromoGift_Block_Chooser_PromoAction);


		$block->setPromoAction ($promoAction);


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
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Block_Chooser';
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


}

