<?php


class Df_Localization_Model_Translation_FileStorage extends Df_Core_Model_Abstract {
	
	

	
	/**
	 * @return Df_Localization_Model_Translation_File_Collection
	 */
	public function getFiles () {
	
		if (!isset ($this->_files)) {
	
			/** @var Df_Localization_Model_Translation_File_Collection $result  */
			$result = 
				df_model (
					Df_Localization_Model_Translation_File_Collection::getClass()
				)
			;

			df_assert ($result instanceof Df_Localization_Model_Translation_File_Collection);



			if (is_dir ($this->getPath())) {

				foreach ($this->getIterator() as $iteratorFileItem) {

					/** @var DirectoryIterator $iteratorFileItem */
					df_assert ($iteratorFileItem instanceof DirectoryIterator);

					$result
						->addItem (
							df_model (
								Df_Localization_Model_Translation_File::getNameInMagentoFormat()
								,
								array (
									Df_Localization_Model_Translation_File::PARAM__PATH =>
										$iteratorFileItem->getRealPath ()
								)
							)
						)
					;
				}

			}


	
			$this->_files = $result;
	
		}
	
	
		df_assert ($this->_files instanceof Df_Localization_Model_Translation_File_Collection);
	
		return $this->_files;
	
	}
	
	
	/**
	* @var Df_Localization_Model_Translation_File_Collection
	*/
	private $_files;	
	


	
	
	/**
	 * @return string
	 */
	public function getCode () {
	
		/** @var string $result  */
		$result =
			$this->cfg (self::PARAM__CODE)
		;	
	
		df_result_string ($result);
	
		return $result;
	
	}		
	
	
	
	
	/**
	 * @return Df_Spl_Iterator_Directory_File
	 */
	private function getIterator () {
	
		if (!isset ($this->_iterator)) {

			df_assert (is_dir ($this->getPath ()));
	
			/** @var Df_Spl_Iterator_Directory_File $result  */
			$result = 
				new Df_Spl_Iterator_Directory_File (
					$this->getPath ()
					,
					array (
						Df_Spl_Iterator_Directory_File::PARAM_PATTERN =>
							Df_Core_Const::FILE_NAME_PATTERN__CSV
					)
				)
			;
	
	
			df_assert ($result instanceof Df_Spl_Iterator_Directory_File);
	
			$this->_iterator = $result;
	
		}
	
	
		df_assert ($this->_iterator instanceof Df_Spl_Iterator_Directory_File);
	
		return $this->_iterator;
	
	}
	
	
	/**
	* @var Df_Spl_Iterator_Directory_File
	*/
	private $_iterator;
	
	
	
	
	
	/**
	 * @return string
	 */
	private function getPath () {
	
		if (!isset ($this->_path)) {
	
			/** @var string $result  */
			$result = 
				implode (
					DS
					,
					array (
						Mage::getBaseDir('locale')
						,
						$this->getCode()
					)
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_path = $result;
	
		}
	
	
		df_result_string ($this->_path);
	
		return $this->_path;
	
	}
	
	
	/**
	* @var string
	*/
	private $_path;	






	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__CODE, new Df_Zf_Validate_String ()
			)
		;
	}




	const PARAM__CODE = 'code';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Localization_Model_Translation_FileStorage';
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


