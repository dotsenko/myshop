<?php

class Df_Zf_Filter_Json_Encoder implements Zend_Filter_Interface
{

    /**
     * @param  mixed $value
     * @return mixed
     */
    public function filter ($value) {

		/** @var string $result  */
		$result = Zend_Json::encode ($value);

		df_result_string ($result);

        return $result;
    }
}
