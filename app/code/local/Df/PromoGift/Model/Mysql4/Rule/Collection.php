<?php


class Df_PromoGift_Model_Mysql4_Rule_Collection
	extends Mage_SalesRule_Model_Mysql4_Rule_Collection {


	protected function _beforeLoad() {

		$select = $this->getSelect ();
		/** @var Varien_Db_Select $select */

		df_assert ($select instanceof Varien_Db_Select);



		$adapter = $select->getAdapter();
		/** @var Zend_Db_Adapter_Abstract $adapter */

		df_assert ($adapter instanceof Zend_Db_Adapter_Abstract);



		/**
		 * Отбраковываем отключенные правила
		 */
		$this
			->addFieldToFilter (
				$adapter->quoteIdentifier (Df_SalesRule_Const::DB__SALESRULE__IS_ACTIVE)
				,
				array (Df_Varien_Const::EQ => 1)
			)
		;



		/**
		 * Нас интересуют только правила типа «Percent of product price discount»,
		 * потому что именно такими правилами определяются промо-подарки
		 */
		$this
			->addFieldToFilter (
				$adapter->quoteIdentifier (Df_SalesRule_Const::DB__SALESRULE__SIMPLE_ACTION)
				,
				array (Df_Varien_Const::EQ => Df_SalesRule_Const::BY_PERCENT_ACTION)
			)
		;



		/**
		 * Выбираем правила, дающую 100-процентную скидку
		 * (другими словами, предоставляющие товары в поларок)
		 */
		$this
			->addFieldToFilter (
				$adapter->quoteIdentifier (Df_SalesRule_Const::DB__SALESRULE__DISCOUNT_AMOUNT)
				,
				array (Df_Varien_Const::EQ => 100)
			)
		;



		/**
		 * Отбраковываем правила с истёкшим сроком действия.
		 *
		 * Обратите внимание, что правила, недействующие сейчас, но запланированные на будущее,
		 * мы не отбраковываем, потому что они могут стать действующими
		 * в период между данным процессом (индексацией) и покупкой.
		 */
		$this
			->addFieldToFilter (
				$adapter->quoteIdentifier (Df_SalesRule_Const::DB__SALESRULE__TO_DATE)
				,
				array (
					 Df_Varien_Const::_OR =>
						array (
							array (
								Df_Varien_Const::FROM => Zend_Date::now ()
								,
								Df_Varien_Const::DATETIME => true
							)
							,
							array (Df_Varien_Const::IS => new Zend_Db_Expr (Df_Zf_Const::NULL))
						)
				)
			)
		;


		parent::_beforeLoad();

	}





	/**
	 * Отбраковываем ещё не начавшиеся правила
	 * @return Df_PromoGift_Model_Mysql4_Rule_Collection
	 */
	public function addNotStartedYetRulesExclusionFilter () {

		$this
			->addFieldToFilter (
				$this->getSelect()->getAdapter ()
					->quoteIdentifier (Df_SalesRule_Const::DB__SALESRULE__FROM_DATE)
				,
				array (
					 Df_Varien_Const::_OR =>
						array (
							array (
								Df_Varien_Const::TO => Zend_Date::now ()
								,
								Df_Varien_Const::DATETIME => true
							)
							,
							array (Df_Varien_Const::IS => new Zend_Db_Expr (Df_Zf_Const::NULL))
						)
				)
			)
		;


		return $this;

	}






	const CLASS_MF = 'df_promo_gift/rule_collection';
	const _CLASS =  'Df_PromoGift_Model_Mysql4_Rule_Collection';


}


