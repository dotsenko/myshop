<?php

class Df_Tweaks_Block_Frontend_Style extends Df_Core_Block_Abstract {


    /**
     * @override
     * @return string
     */
    protected function _toHtml() {

		/** @var string $result  */
		$result =
				!(
						df_module_enabled (Df_Core_Module::TWEAKS)
					&&
						df_enabled (Df_Core_Feature::TWEAKS)
				)
			?
				Df_Core_Const::T_EMPTY
			:
				$this->getStyle()->toHtml()
		;

		return $result;

    }




	/**
	 * @return Df_Tweaks_Block_Frontend_Style
	 */
	private function adjustLetterCase () {

		if (
				Df_Admin_Model_Config_Source_Format_Text_LetterCase::_DEFAULT
			!==
				df_cfg()->tweaks()->labels()->getButtonFont()->getLetterCase ()
		) {

			$this->getStyle()->getSelectors()
				->addItem (
					df_block (
						Df_Core_Block_Element_Style_Selector::getNameInMagentoFormat()
						,
						null
						,
						array (
							Df_Core_Block_Element_Style_Selector::PARAM__SELECTOR => '.button *, .buttons-set'
							,
							Df_Core_Block_Element_Style_Selector::PARAM__RULE_SET =>
								Df_Varien_Data_Collection::createFromCollection (
									array (
										df_cfg()->tweaks()->labels()->getButtonFont()->getLetterCaseAsCssRule()
									)
									,
									Df_Core_Model_Output_Css_Rule_Set::getClass()
								)
						)
					)
				)
			;

		}

		return $this;

	}




	/**
	 * @return Df_Tweaks_Block_Frontend_Style
	 */
	private function adjustReviewsAndRatings () {

		if (df_helper()->tweaks()->isItCatalogProductList()) {

			if (
					df_cfg()->tweaks()->catalog()->product()->_list()->getHideRating()
				&&
					df_cfg()->tweaks()->catalog()->product()->_list()->getHideReviews()
			) {

				$this->getStyle()->getSelectors()
					->addHider ('.category-products .ratings')
				;
			}

			else if (df_cfg()->tweaks()->catalog()->product()->_list()->getHideRating()) {

				$this->getStyle()->getSelectors()
					->addHider ('.category-products .ratings .rating-box')
				;

			}

			else if (df_cfg()->tweaks()->catalog()->product()->_list()->getHideReviews()) {

				$this->getStyle()->getSelectors()
					->addHider ('.category-products .ratings .amount')
				;

			}
		}


		else if (df_handle_presents(Df_Catalog_Const::LAYOUT_HANDLE__PRODUCT_VIEW)) {


			if 	(
					df_cfg()->tweaks()->catalog()->product()->view ()->getHideRating ()
				&&
					df_cfg()->tweaks()->catalog()->product()->view ()->getHideReviewsLink ()
				&&
					df_cfg()->tweaks()->catalog()->product()->view ()->getHideAddReviewLink ()
			) {
				$this->getStyle()->getSelectors()
					->addHider ('.product-view .ratings')
				;
			}

			else {

				if (df_cfg()->tweaks()->catalog()->product()->view ()->getHideRating ()) {
					$this->getStyle()->getSelectors()
						->addHider ('.product-view .ratings .rating-box')
					;
				}

				if (
						df_cfg()->tweaks()->catalog()->product()->view ()->getHideReviewsLink ()
					&&
						df_cfg()->tweaks()->catalog()->product()->view ()->getHideAddReviewLink ()
				) {
					$this->getStyle()->getSelectors()
						->addHider ('.product-view .ratings .rating-links')
					;
				}

				else {

					if (
							df_cfg()->tweaks()->catalog()->product()->view ()->getHideReviewsLink ()
						||
							df_cfg()->tweaks()->catalog()->product()->view ()->getHideAddReviewLink ()
					) {
						$this->getStyle()->getSelectors()
							->addHider ('.product-view .ratings .rating-links .separator')
						;
					}

					if (df_cfg()->tweaks()->catalog()->product()->view ()->getHideReviewsLink ()) {
						$this->getStyle()->getSelectors()
							->addHider ('.product-view .ratings .rating-links a:first-child')
							->addHider ('.product-view .ratings .rating-links a.first-child')
						;
					}

					if (df_cfg()->tweaks()->catalog()->product()->view ()->getHideAddReviewLink ()) {
						$this->getStyle()->getSelectors()
							->addHider ('.product-view .ratings .rating-links a:last-child')
							->addHider ('.product-view .ratings .rating-links a.last-child')
						;
					}

				}

			}

		}



		return $this;

	}



	
	
	
	
	/**
	 * @return Df_Core_Block_Element_Style
	 */
	private function getStyle () {
	
		if (!isset ($this->_style)) {
	
			/** @var Df_Core_Block_Element_Style $result  */
			$result = 
				df_block (
					Df_Core_Block_Element_Style::getNameInMagentoFormat()
				)
			;

	
			df_assert ($result instanceof Df_Core_Block_Element_Style);
	
			$this->_style = $result;



			$this->adjustLetterCase ();

			$this->adjustReviewsAndRatings ();

			if (df_cfg()->tweaks()->footer()->getRemoveHelpUs()) {
				$this->getStyle()->getSelectors()
					->addHider ('p.bugs')
				;
			}


			if (
					df_handle_presents (Df_Catalog_Const::LAYOUT_HANDLE__PRODUCT_VIEW)
				&&
					df_cfg()->tweaks()->catalog()->product()->view()->getHideAvailability()
			) {
				$this->getStyle()->getSelectors()
					->addHider ('.product-view p.availability')
				;
			}
	
		}
	
	
		df_assert ($this->_style instanceof Df_Core_Block_Element_Style);
	
		return $this->_style;
	
	}
	
	
	/**
	* @var Df_Core_Block_Element_Style
	*/
	private $_style;






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Block_Frontend_Style';
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


