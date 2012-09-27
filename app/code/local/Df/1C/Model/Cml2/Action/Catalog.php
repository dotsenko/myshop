<?php

abstract class Df_1C_Model_Cml2_Action_Catalog extends Df_1C_Model_Cml2_Action {


	/**
	 * @return string
	 */
	protected function getFileFullPath () {

		/** @var string $result  */
		$result =
			$this->getFiles()->getFullPathByRelativePath (
				$this->getFileRelativePath()
			)
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * Обратите внимание,
	 * что этот метод может вернуть не просто имя файла (catalog.xml, offers.xml),
	 * но и имя с относительным путём (для файлов картинок), например:
	 * import_files/cb/cbcf4934-55bc-11d9-848a-00112f43529a_b5cfbe1a-c400-11e1-a851-4061868fc6eb.jpeg
	 *
	 * @return string
	 */
	protected function getFileRelativePath () {

		/** @var string $result  */
		$result = $this->getRmRequest()->getParam ('filename');

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return Df_1C_Model_Cml2_Registry_Import_Files
	 */
	protected function getFiles () {

		/** @var Df_1C_Model_Cml2_Registry_Import_Files $result  */
		$result = $this->getRegistry()->import()->files ('catalog');

		df_assert ($result instanceof Df_1C_Model_Cml2_Registry_Import_Files);

		return $result;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Action_Catalog';
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
