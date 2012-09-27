<?php


class Df_Zf_Date extends Zend_Date {


	const FORMAT__YEAR = 'yyyy';



	/**
	 * @static
	 * @param string $datetime
	 * @return Zend_Date|null
	 */
	public static function createFromMySqlDateTime ($datetime) {

		df_param_string ($datetime, 0);

		/** @var Zend_Date|null $result  */
		$result = null;

		if (!df_empty($datetime)) {
			try {
				$result = new Zend_Date ($datetime, Zend_Date::ISO_8601);
			}
			catch (Exception $e) {
			}
		}

		if (!is_null ($result)) {
			df_assert ($result instanceof Zend_Date);
		}

		return $result;

	}





	/**
	 * @static
	 * @param string $dateAsString
	 * @param string $format [optional]
	 * @throws Exception
	 * @return Zend_Date
	 */
	public static function createForDefaultTimezone ($dateAsString, $format = Zend_Date::W3C) {

		df_param_string ($dateAsString, 0);

		/** @var Zend_Date $result  */
		$result = null;


		/** @var string $timezone  */
		$timezone = date_default_timezone_get();

		date_default_timezone_set (Mage_Core_Model_Locale::DEFAULT_TIMEZONE);

		try {
			/** @var Zend_Date $result  */
			$result =
				new Zend_Date (
					$dateAsString
					,
					$format
				)
			;
		}
		catch (Exception $e) {
			date_default_timezone_set ($timezone);
			throw $e;
		}

		date_default_timezone_set ($timezone);


		return $result;

	}





	/**
	 * @param Zend_Date $date1
	 * @param Zend_Date $date2
	 * @return int
	 */
	public static function getNumberOfDaysBetweenTwoDates (Zend_Date $date1, Zend_Date $date2) {

		/** @var Zend_Date $dateMin  */
		$dateMin = self::min ($date1, $date2);

		df_assert ($dateMin instanceof Zend_Date);


		/** @var Zend_Date $dateMax  */
		$dateMax = self::max ($date1, $date2);

		df_assert ($dateMax instanceof Zend_Date);


		/**
		 * @link http://stackoverflow.com/a/3118478/254475
		 */

		/** @var Zend_Date $differenceAsDate  */
		$differenceAsDate = new Zend_Date ($dateMax);

		$differenceAsDate->sub ($dateMin);


		$dateMinA = new Zend_Date ($dateMin);
		$dateMaxA = new Zend_Date ($dateMax);

		$dateMinA->setHour(0)->setMinute(0)->setSecond(0);
		$dateMaxA->setHour(0)->setMinute(0)->setSecond(0);

		$result = round ($dateMaxA->sub($dateMinA)->toValue() / 86400);


		df_result_integer ($result);

		return $result;

	}





	/**
	 * @static
	 * @param Zend_Date $date1
	 * @param Zend_Date $date2
	 * @return Zend_Date
	 */
	public static function min (Zend_Date $date1, Zend_Date $date2) {
		return
				($date1->getTimestamp() < $date2->getTimestamp())
			?
				$date1
			:
				$date2
//				$date2->isEarlier ($date1)
//			?
//				$date2
//			:
//				$date1
		;
	}





	/**
	 * @static
	 * @param Zend_Date $date1
	 * @param Zend_Date $date2
	 * @return Zend_Date
	 */
	public static function max (Zend_Date $date1, Zend_Date $date2) {

		/** @var Zend_Date $result */
		$result =
				($date1->getTimestamp() > $date2->getTimestamp())
			?
				$date1
			:
				$date2
//				$date2->isLater ($date1)
//			?
//				$date2
//			:
//				$date1
		;


		df_assert ($result instanceof Zend_Date);


		return $result;

	}



	/**
	 * @return Df_Zf_Date
	 */
	public static function nowInCurrentTimeZone () {

		/** @var Df_Zf_Date $result  */
		$result = new Df_Zf_Date (Df_Zf_Date::now());

		df_assert ($result instanceof Df_Zf_Date);

		$result->setTimezone (Mage::app()->getLocale()->getTimezone());

		return $result;
	}


}