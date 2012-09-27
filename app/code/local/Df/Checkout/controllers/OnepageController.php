<?php

require_once 'Mage/Checkout/controllers/OnepageController.php';

class Df_Checkout_OnepageController extends Mage_Checkout_OnepageController {




	/**
	 * @override
	 * @return Df_Checkout_Model_Type_Onepage
	 */
	public function getOnepage () {
	
		if (!isset ($this->_onepage)) {
	
			/** @var Df_Checkout_Model_Type_Onepage $result  */
			$result = 
				df_model (
					Df_Checkout_Model_Type_Onepage::getNameInMagentoFormat()
				)
			;
	
			df_assert ($result instanceof Df_Checkout_Model_Type_Onepage);

			$result->setController ($this);
	
			$this->_onepage = $result;
	
		}
	
	
		df_assert ($this->_onepage instanceof Df_Checkout_Model_Type_Onepage);
	
		return $this->_onepage;
	
	}
	
	
	/**
	* @var Df_Checkout_Model_Type_Onepage
	*/
	private $_onepage;






	/**
	* Shipping method save action
	*/
	public function saveShippingMethodAction() {
		if ($this->_expireAjax()) {
			return;
		}
		if ($this->getRequest()->isPost()) {
			$data = $this->getRequest()->getPost('shipping_method', '');
			$result = $this->getOnepage()->saveShippingMethod($data);
			/*
			$result will have erro data if shipping method is empty
			*/
			if(!$result) {
				Mage::dispatchEvent(
					'checkout_controller_onepage_save_shipping_method'
					,
					array(
						'request'=>$this->getRequest()
						,
						'quote'=>$this->getOnepage()->getQuote()
					)
				);

				$this->getOnepage()->getQuote()->collectTotals();
				$this->getResponse()->setBody(df_mage()->coreHelper()->jsonEncode($result));


				$result['goto_section'] = 'payment';
				$result['update_section'] = array(
					'name' => 'payment-method',
					'html' => $this->_getPaymentMethodsHtml()
				);


				/**
				 * BEGIN PATCH
				 */

				Mage::app()->getCacheInstance()->banUse ('layout');

				/** @var Exception $exception|null  */
				$exception = null;

				try {

					$this->loadLayout('checkout_onepage_review');

					$result ['df_update_sections'] =
						array (
							array (
								'name' => 'review',
								'html' =>
									$this->getLayout()->getBlock('root')->toHtml()
							)
						)
					;

				}
				catch (Exception $e) {
					$exception = $e;
				}

				if (!is_null ($exception)) {
					throw $exception;
				}

				/**
				 * END PATCH
				 */

			}
			$this->getOnepage()->getQuote()->collectTotals()->save();
			$this->getResponse()->setBody(df_mage()->coreHelper()->jsonEncode($result));
		}
	}


}


