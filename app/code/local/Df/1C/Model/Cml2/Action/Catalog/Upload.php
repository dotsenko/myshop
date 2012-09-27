<?php

class Df_1C_Model_Cml2_Action_Catalog_Upload extends Df_1C_Model_Cml2_Action_Catalog {


	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Action
	 */
	protected function processInternal () {

		/** @var Varien_Io_File $file  */
		$file = new Varien_Io_File();

		df_assert ($file instanceof Varien_Io_File);


		$file->setAllowCreateFolders(true);

		$file
			->createDestinationDir (
				dirname (
					$this->getFileFullPath()
				)
			)
		;

		file_put_contents (
			$this->getFileFullPath()
			,
			file_get_contents (
				'php://input'
			)
		);


		$this
			->setResponseBodyAsArrayOfStrings (
				array (
					'success'
					,
					Df_Core_Const::T_EMPTY
				)
			)
		;

		return $this;

	}
	
	
	

	

	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Action_Catalog_Upload';
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
