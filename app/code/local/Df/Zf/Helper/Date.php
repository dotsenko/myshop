<?php


class Df_Zf_Helper_Date extends Mage_Core_Helper_Abstract {
	
		
	
	/**
	 * @return Zend_Date
	 */
	public function getToday () {
	
		/** @var Zend_Date $result  */
		$result = 
			new Zend_Date ()
		;

		df_assert ($result instanceof Zend_Date);
		
		$result->setHour(0);
		$result->setMinute(0);
		$result->setSecond(0);	

		return $result;
	
	}
	
	
	
	
	
	/**
	 * @return Zend_Date
	 */
	public function getYesterday () {
	
		/** @var Zend_Date $result  */
		$result = 
			$this->getToday()
		;

		df_assert ($result instanceof Zend_Date);
		
		$result->subDay(1);

		return $result;
	
	}	
	
	
	
	
	



	/**
	 * @static
	 * @param Zend_Date $date
	 * @return bool
	 */
	public function isInFuture (Zend_Date $date) {
		$result =  (0 > Zend_Date::now ()->compare ($date));


		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_boolean ($result);
		/*************************************/

		return $result;
	}




	/**
	 * @static
	 * @param Zend_Date $date
	 * @return bool
	 */
	public function isInPast (Zend_Date $date) {
		$result = (0 < Zend_Date::now ()->compare ($date));


		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_boolean ($result);
		/*************************************/

		return $result;
	}







	/**
	 * @param  string $dateAsString
	 * @return Zend_Date
	 */
	public function parseFromInternalFormat ($dateAsString) {

		df_param_string ($dateAsString, 0);

		/** @var Zend_Date $result  */
		$result =
			Mage::app()->getLocale()->date (
				$dateAsString
				,
				Varien_Date::DATE_INTERNAL_FORMAT
				,
				null
				,
				false
			)
		;

		df_assert ($result instanceof Zend_Date);

		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Zf_Helper_Date';
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
