<?php


class Df_Dataflow_Model_Convert_Mapper_Log
	extends Df_Dataflow_Model_Convert_Mapper_Abstract {



	/**
	 * @return string
	 */
	protected function getFeatureCode () {

		$result = Df_Core_Feature::DATAFLOW;

		df_result_string ($result);

		return $result;
	}





	/**
	 * @param array $row
	 * @return array
	 */
	protected function processRow (array $row) {

		df_param_array ($row, 0);


		/**
		 * В этом вызове - смысл всего класса
		 */
		df_log ($row);


		/** @var array $result  */
		$result =
			$row
		;

		df_result_array ($result);

		return $result;
	}


}


