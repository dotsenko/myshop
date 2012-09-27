<?php


class Df_Localization_Block_Admin_Verification_File extends Df_Core_Block_Template {




	/**
	 * @return bool
	 */
	public function isFullyTranslated () {

		/** @var bool $result  */
		$result =
			$this->getFile()->isFullyTranslated()
		;


		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getName () {

		/** @var string $result  */
		$result =
			$this->getFile()->getName()
		;


		df_result_string ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	public function getNumEntries () {

		/** @var int $result  */
		$result =
			$this->getFile()->getNumEntries()
		;


		df_result_integer ($result);

		return $result;

	}






	/**
	 * @return int
	 */
	public function getNumUntranslatedEntries () {

		/** @var int $result  */
		$result =
			$this->getFile()->getNumUntranslatedEntries()
		;


		df_result_integer ($result);

		return $result;

	}





	/**
	 * @return int
	 */
	public function getNumAbsentEntries () {

		/** @var int $result  */
		$result =
			$this->getFile()->getNumAbsentEntries()
		;


		df_result_integer ($result);

		return $result;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getDefaultTemplate () {

		/** @var string $result  */
		$result = self::DEFAULT_TEMPLATE;

		df_result_string ($result);

		return $result;

	}






	/**
	 * @return Df_Localization_Model_Translation_File
	 */
	private function getFile () {

		/** @var Df_Localization_Model_Translation_File $result  */
		$result =
			$this->cfg (self::PARAM__FILE)
		;

		df_assert ($result instanceof Df_Localization_Model_Translation_File);

		return $result;

	}





	const DEFAULT_TEMPLATE = 'df/localization/verification/file.phtml';

	const PARAM__FILE = 'file';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Localization_Block_Admin_Verification_File';
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


