<?php


class Df_Sales_Model_Mysql4_Order extends Mage_Sales_Model_Mysql4_Order {



	/**
	 * @param string $protectCode
	 * @return int
	 */
	public function getOrderIdByProtectCode ($protectCode) {

		df_param_string ($protectCode, 0);


		/** @var Zend_Db_Select $select  */
        $select =
			$this
				->getReadConnection()
					->select()
            		->from (
						$this->getMainTable(), array("entity_id")
					)
					->where('protect_code = ?', $protectCode)
		;


		/** @var string $result  */
        $result =
			$this->getReadConnection()
				->fetchOne (
					$select
				)
		;


		df_result_integer ($result);



		$result = intval ($result);

		return $result;

	}



}


