<?php


class Df_Dataflow_Model_Convert_Mapper_Value extends Df_Dataflow_Model_Convert_Mapper_Abstract {



	/**
	 * @return string
	 */
	protected function getFeatureCode () {

		/** @var string $result  */
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


		/** @var string $valueBeforeProcessing  */
		$valueBeforeProcessing = df_a ($row, $this->getAttributeName ());

		if (!df_empty ($valueBeforeProcessing)) {

			/** @var string|null $valueAfterProcessing  */
			$valueAfterProcessing = NULL;


			foreach ($this->getMap () as $occurence => $replacement) {
				/** @var string $occurence */
				/** @var string $replacement */

				df_assert_string ($occurence);
				df_assert_string ($replacement);


				if (!is_null (mb_strpos ($valueBeforeProcessing, $occurence))) {

					$valueAfterProcessing =
						str_replace ($valueBeforeProcessing, $occurence, $replacement)
					;

					break;

				}
			}

			$row [$this->getAttributeName ()] = $valueAfterProcessing;
		}


		df_result_array ($row);

	    return $row;
	}





	/**
	 * @return string
	 */
	private function getAttributeName () {

		/** @var string $result  */
		$result =
			$this->getVar (self::VAR_ATTRIBUTE)
		;


		df_result_string ($result);

		return $result;

	}



	const VAR_ATTRIBUTE = 'df-attribute';


}