<?php


class Df_Tweaks_Model_Handler_Account_AdjustLinks extends Df_Core_Model_Handler {

	/**
	 * Метод-обработчик события
	 *
	 * @override
	 * @return void
	 */
	public function handle () {

		/**
		 * Обратите внимание, что мы не вынесли условие !is_null ($this->getBlock()
		 * вверх, потому что не хотим, чтобы его программный код исполнялся
		 * при отключенных функциях модуля Df_Tweaks
		 */

		if (
				df_cfg()->tweaks()->account ()->getRemoveSectionBillingAgreements ()
			&&
				!is_null ($this->getBlock())
		) {
			$this->getBlock()
				->removeLinkByPath (
					'sales/billing_agreement/'
				)
			;
		}


		if (
				df_cfg()->tweaks()->account ()->getRemoveSectionDownloadableProducts ()
			&&
				!is_null ($this->getBlock())
		) {
			$this->getBlock()
				->removeLinkByPath (
					'downloadable/customer/products'
				)
			;
		}


		if (
				df_cfg()->tweaks()->account ()->getRemoveSectionNewsletterSubscriptions ()
			&&
				!is_null ($this->getBlock())
		) {
			$this->getBlock()
				->removeLinkByPath (
					'newsletter/manage/'
				)
				->removeLinkByPath (
					'newsletter/manage'
				)
			;
		}


		if (
				df_cfg()->tweaks()->account ()->getRemoveSectionProductReviews ()
			&&
				!is_null ($this->getBlock())
		) {
			$this->getBlock()
				->removeLinkByPath (
					'review/customer'
				)
			;
		}


		if (
				df_cfg()->tweaks()->account ()->getRemoveSectionRecurringProfiles ()
			&&
				!is_null ($this->getBlock())
		) {
			$this->getBlock()
				->removeLinkByPath (
					'sales/recurring_profile/'
				)
			;
		}



		if (
				df_cfg()->tweaks()->account ()->getRemoveSectionTags ()
			&&
				!is_null ($this->getBlock())
		) {
			$this->getBlock()
				->removeLinkByPath (
					'tag/customer/'
				)
			;
		}



		if (
				df_cfg()->tweaks()->account ()->getRemoveSectionWishlist ()
			&&
				!is_null ($this->getBlock())
		) {
			$this->getBlock()
				->removeLinkByPath (
					'wishlist/'
				)
			;
		}

	}


	


	/**
	 * Объявляем метод заново, чтобы IDE знала настоящий тип объекта-события
	 *
	 * @override
	 * @return Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter
	 */
	protected function getEvent () {

		/** @var Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter $result  */
		$result = parent::getEvent();

		df_assert ($result instanceof Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter);

		return $result;
	}





	/**
	 * Класс события (для валидации события)
	 *
	 * @override
	 * @return string
	 */
	protected function getEventClass () {

		/** @var string $result  */
		$result = Df_Core_Model_Event_Controller_Action_Layout_GenerateBlocksAfter::getClass();

		df_result_string ($result);

		return $result;

	}
	
	
	
	
	/**
	 * @return Df_Customer_Block_Account_Navigation|null
	 */
	private function getBlock () {
	
		if (!isset ($this->_block) && !$this->_blockIsNull) {
	
			/** @var Df_Customer_Block_Account_Navigation|null $result  */
			$result = 
				$this->getEvent()->getLayout()->getBlock ('customer_account_navigation')
			;


			if (
				/**
				 * Раньше тут просто стояла проверка на равенство false.
				 * Однако в магазине могут быть установлены сторонние модули,
				 * которые перекрывают класс Mage_Customer_Block_Account_Navigation
				 * своим классом вместо нашего Df_Customer_Block_Account_Navigation,
				 * и нам в этом случае вовсе необязательно падать:
				 * вместо этого функция просто будет отключена.
				 */
				!($result instanceof Df_Customer_Block_Account_Navigation)
			) {
				$result = null;
				$this->_blockIsNull = true;
			}
	
			$this->_block = $result;
		}
	
	
		if (!is_null ($this->_block)) {
			df_assert ($this->_block instanceof Df_Customer_Block_Account_Navigation);
		}		
		
	
		return $this->_block;
	
	}
	
	
	/**
	* @var Df_Customer_Block_Account_Navigation|null
	*/
	private $_block;	
	
	/**
	 * @var bool
	 */
	private $_blockIsNull = false;	
	




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Model_Handler_Account_AdjustLinks';
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


