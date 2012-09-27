<?php


/**
 * Logging helper
 */
class Df_Logging_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Join array into string except empty values
     *
     * @param array $array Array to join
     * @param string $glue Separator to join
     * @return string
     */
    public function implodeValues($array, $glue = ', ')
    {
        if (!is_array($array)) {
            return $array;
        }
        $result = array();
        foreach ($array as $item) {
            if ((string)$item !== '') {
                $result[] = $item;
            }
        }
        return implode($glue, $result);
    }



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Logging_Helper_Data';
	}


	/**
	 * Например, для класса Df_LoggingRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_logging_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return
			df()->reflection()->getModelNameInMagentoFormat (
				self::getClass()
			)
		;
	}


}
