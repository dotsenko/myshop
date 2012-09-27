<?php

class Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_Image
	extends Df_1C_Model_Cml2_Import_Data_Entity {



	/**
	 * @override
	 * @return string
	 */
	public function getExternalId () {

		/** @var string $result  */
		$result = $this->getFilePathRelative();

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getFilePathFull () {

		/** @var string $result  */
		$result =
			str_replace (
				DS
				,
				'/'
				,
				$this->getRegistry()->import()->files ('catalog')->getFullPathByRelativePath (
					$this->getFilePathRelative()
				)
			)
		;

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return string
	 */
	public function getFilePathRelative () {

		/** @var string $result  */
		$result = (string)($this->getSimpleXmlElement());

		df_result_string ($result);

		return $result;

	}





	/**
	 * @override
	 * @return string
	 */
	public function getName () {

		/** @var string $result  */
		$result = $this->getSimpleXmlElement()->getAttribute ('Описание');

		df_result_string ($result);

		return $result;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_Image';
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

