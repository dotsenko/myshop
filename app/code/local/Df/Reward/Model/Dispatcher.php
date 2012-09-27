<?php


class Df_Reward_Model_Dispatcher extends Df_Core_Model_Abstract {



	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function core_block_abstract_to_html_after (
		Varien_Event_Observer $observer
	) {

		try {

			/** @var Mage_Core_Block_Abstract $block  */
			$block = $observer->getData ('block');

			if ('checkout.payment.methods' === $block->getNameInLayout()) {


				/** @var Varien_Object $transport  */
				$transport = $observer->getData ('transport');

				df_assert ($transport instanceof Varien_Object);


				/** @var string $html  */
				$html = $transport->getData ('html');


				$html =
					implode (
						Df_Core_Const::T_EMPTY
						,
						array (
							$this->getBlockReward()->toHtml()
							,
							$html
							,
							$this->getBlockRewardScripts ()->toHtml ()
						)
					)					
				;

				$transport->setData ('html', $html);



			}

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}

	}





	/**
	 * Модуль при получении сообщения «sales_quote_collect_totals_before»
	 * обнуляет свои счётчики
	 * применимых к корзине правил с накопительными скидками
	 *
	 * @param Varien_Event_Observer $observer
	 * @return void
	 *
	 */
	public function sales_quote_collect_totals_before (Varien_Event_Observer $observer) {

		try {
			if (df_helper()->reward()->isEnabledOnFront()) {

				/**
				 * Обнуляем коллекцию применимых к корзине правил с накопительными скидками
				 */
				df_helper ()->reward()->getSalesRuleApplications()->clear();

			}
		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}

	}





	/**
	 * Подсчитываем применимые к корзине правила с накопительными скидками
	 *
	 * @param Varien_Event_Observer $observer
	 * @return void
	 *
	 */
	public function salesrule_validator_process (Varien_Event_Observer $observer) {

		try {
			if (df_helper()->reward()->isEnabledOnFront()) {

				/**
				 * Чтобы коллекция не ругалась на элемент без идентификатора
				 */
				$observer->setData ('id', uniqid());

				df_helper ()->reward()->getSalesRuleApplications ()
					->addItem (
						$observer
					)
				;

			}
		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}

	}





	
	
	
	
	
	/**
	 * @return Df_Reward_Block_Checkout_Payment_Additional
	 */
	private function getBlockReward () {
	
		if (!isset ($this->_blockReward)) {
	
			/** @var Df_Reward_Block_Checkout_Payment_Additional $result  */
			$result = 
				df_block (
					Df_Reward_Block_Checkout_Payment_Additional
						::getNameInMagentoFormat()
					,
					'reward.points'
					,
					array ()
				)
			;
			
			$result->setTemplate (
				'df/reward/checkout/onepage/payment/additional.phtml'
			);
			
	
			df_assert ($result instanceof Df_Reward_Block_Checkout_Payment_Additional);
	
			$this->_blockReward = $result;
	
		}
	
	
		df_assert ($this->_blockReward instanceof Df_Reward_Block_Checkout_Payment_Additional);
	
		return $this->_blockReward;
	
	}
	
	
	/**
	* @var Df_Reward_Block_Checkout_Payment_Additional
	*/
	private $_blockReward;	
	
	
	
	
	
	
	
	/**
	 * @return Df_Reward_Block_Checkout_Payment_Additional
	 */
	private function getBlockRewardScripts () {
	
		if (!isset ($this->_blockRewardScripts)) {
	
			/** @var Df_Reward_Block_Checkout_Payment_Additional $result  */
			$result = 
				df_block (
					Df_Reward_Block_Checkout_Payment_Additional
						::getNameInMagentoFormat()
					,
					'reward.scripts'
					,
					array ()
				)
			;
			
			$result->setTemplate (
				'df/reward/checkout/onepage/payment/scripts.phtml'
			);
			
	
			df_assert ($result instanceof Df_Reward_Block_Checkout_Payment_Additional);
	
			$this->_blockRewardScripts = $result;
	
		}
	
	
		df_assert ($this->_blockRewardScripts instanceof Df_Reward_Block_Checkout_Payment_Additional);
	
		return $this->_blockRewardScripts;
	
	}
	
	
	/**
	* @var Df_Reward_Block_Checkout_Payment_Additional
	*/
	private $_blockRewardScripts;
	
	
	
	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Reward_Model_Dispatcher';
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


