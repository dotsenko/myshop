<?php


class Df_Localization_Block_Admin_Verification extends Df_Core_Block_Template {
	

	/**
	 * @override
	 * @return string
	 */
	public function getDetailsAsJson () {
	
		if (!isset ($this->_detailsAsJson)) {
	
			/** @var string $result  */
			$result = 
				json_encode (
					$this->getDetails ()
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_detailsAsJson = $result;
	
		}
	
	
		df_result_string ($this->_detailsAsJson);
	
		return $this->_detailsAsJson;
	
	}
	
	
	/**
	* @var string
	*/
	private $_detailsAsJson;	
	
	

	


	/**
	 * @param Df_Localization_Model_Translation_File $file
	 * @return string
	 */
	public function renderFile (Df_Localization_Model_Translation_File $file) {


		/** @var Df_Localization_Block_Admin_Verification_File $block  */
		$block =
			df_mage()->core()->layout()
				->createBlock (
					Df_Localization_Block_Admin_Verification_File::getNameInMagentoFormat()
					,
					null
					,
					array (
						Df_Localization_Block_Admin_Verification_File::PARAM__FILE => $file
					)
				)
		;

		df_assert ($block instanceof Df_Localization_Block_Admin_Verification_File);



		/** @var string $result  */
		$result =
			$block->toHtml()
		;


		return $result;

	}





	/**
	 * @return string
	 */
	public function getTitle () {

		/** @var string $result  */
		$result =
			'Проверка качества перевода'
		;


		df_result_string ($result);

		return $result;

	}




	/**
	 * @return Df_Localization_Model_Translation_File_Collection
	 */
	public function getFiles () {

		/** @var Df_Localization_Model_Translation_File_Collection $result  */
		$result =
			$this->getReport()->getFiles()
		;

		df_assert ($result instanceof Df_Localization_Model_Translation_File_Collection);

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
	 * @return array
	 */
	private function getDetails () {

		if (!isset ($this->_details)) {

			/** @var array $result  */
			$result = array ();


			foreach ($this->getFiles() as $file)  {

				/** @var Df_Localization_Model_Translation_File $file  */
				df_assert ($file instanceof Df_Localization_Model_Translation_File);

				$result [$file->getName ()]=
					array (
						'absentEntries' => $file->getAbsentEntries()
						,
						'untranslatedEntries' => $file->getUntranslatedEntries()
					)
				;
			}


			df_assert_array ($result);

			$this->_details = $result;

		}


		df_result_array ($this->_details);

		return $this->_details;

	}


	/**
	* @var array
	*/
	private $_details;


	
	
	
	
	/**
	 * @return Df_Localization_Model_Report_Verification
	 */
	private function getReport () {
	
		if (!isset ($this->_report)) {
	
			/** @var Df_Localization_Model_Report_Verification $result  */
			$result = 
				df_model (
					Df_Localization_Model_Report_Verification::getClass()
				)
			;
	
	
			df_assert ($result instanceof Df_Localization_Model_Report_Verification);
	
			$this->_report = $result;
	
		}
	
	
		df_assert ($this->_report instanceof Df_Localization_Model_Report_Verification);
	
		return $this->_report;
	
	}
	
	
	/**
	* @var Df_Localization_Model_Report_Verification
	*/
	private $_report;






	const DEFAULT_TEMPLATE = 'df/localization/verification.phtml';
	




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Localization_Block_Admin_Verification';
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


