<?php

class Df_Zf_Filter_Json_PrettyPrint implements Zend_Filter_Interface
{

    /**
     * @param  string $value
     * @return string
     */
    public function filter ($value) {

		df_param_string ($value, 0);

		/** @var string $result  */
		$result =
				method_exists ('Zend_Json', 'prettyPrint')
			?
				Zend_Json::prettyPrint($value)
			:
				$value
		;

		df_result_string ($result);

        return $result;
    }
}
