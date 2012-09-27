<?php



/**
 * @param  string $value
 * @return string
 */
function df_json_pretty_print ($value) {

	df_param_string ($value, 0);

	$filter = new Df_Zf_Filter_Json_PrettyPrint ();

	/** @var string $result  */
	$result = $filter->filter ($value);

	df_result_string ($result);

	return $result;

}





/**
 * @param Zend_Date $date
 * @param bool $inCurrentTimeZone [optional]
 * @return string
 */
function df_date_to_mysql_datetime (Zend_Date $date, $inCurrentTimeZone = true) {


	/** @var string $result  */
	$result =
		$date->toString (
			$inCurrentTimeZone ? 'Y-MM-dd HH:mm:ss' : Zend_Date::ISO_8601
		)
	;

	df_result_string ($result);

	return $result;

}






/**
 * @param  string $datetime
 * @return Zend_Date|null
 */
function df_parse_mysql_datetime ($datetime) {

	df_param_string ($datetime, 0);

	/** @var Zend_Date|null $result  */
	$result = Df_Zf_Date::createFromMySqlDateTime ($datetime);

	if (!is_null ($result)) {
		df_assert ($result instanceof Zend_Date);
	}

	return $result;

}





/**
 * @param Zend_Date $date
 * @return bool
 */
function df_is_date_in_future (Zend_Date $date) {

	/** @var bool $result */
	$result = df_helper ()->zf ()->date ()->isInFuture ($date);

	df_result_boolean ($result);

	return $result;

}




/**
 * Retrieve model object
 *
 * @link    Mage_Core_Model_Config::getModelInstance
 * @param   string $modelClass
 * @param   mixed $arguments
 * @return  Zend_Filter_Interface
 * @throws Exception
 */
function df_zf_filter ($modelClass = '', $arguments = array()) {

	/** @var Zend_Filter_Interface $result */
	$result = df_model ($modelClass, $arguments);

	df_assert ($result instanceof Zend_Filter_Interface);

	return $result;
}






