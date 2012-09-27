<?php


class Df_Newsletter_Model_Dispatcher extends Df_Core_Model_Abstract {



	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function newsletter_subscriber_save_before (
		Varien_Event_Observer $observer
	) {

		try {

			/** @var Mage_Newsletter_Model_Subscriber $subscriber  */
			$subscriber = $observer->getEvent()->getData ('subscriber');

			df_assert ($subscriber instanceof Mage_Newsletter_Model_Subscriber);



			if (
					(0 === intval ($subscriber->getStoreId()))
				&&
					df_cfg()->newsletter()->subscription()->fixSubscriberStore()
				&&
					df_enabled (Df_Core_Feature::NEWSLETTER)
			) {

				/** @var Mage_Customer_Model_Customer $customer  */
				$customer = Mage::getModel('customer/customer');

				df_assert ($customer instanceof Mage_Customer_Model_Customer);



				$customer->setData ('website_id', Mage::app()->getStore()->getWebsiteId());

				$customer->loadByEmail ($subscriber->getSubscriberEmail());



				/** @var bool $isSubscribeOwnEmail  */
				$isSubscribeOwnEmail =
						df_mage()->customer()->session()->isLoggedIn()
					&&
						$customer->getId () == df_mage()->customer()->session()->getId()
				;



				if ($isSubscribeOwnEmail) {

					$subscriber->setStoreId (Mage::app()->getStore()->getId());

				}

			}
		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Newsletter_Model_Dispatcher';
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


