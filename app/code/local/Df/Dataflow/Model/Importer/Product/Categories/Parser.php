<?php


abstract class Df_Dataflow_Model_Importer_Product_Categories_Parser
	extends Df_Core_Model_Abstract  {


	/**
	 * @abstract
	 * @return array
	 */
	abstract public function getPaths ();





	/**
	 * @return string
	 */
	protected function getImportedValue () {

		/** @var string $result  */
		$result =
			df_trim (
				$this->cfg (self::PARAM_IMPORTED_VALUE)
			)
		;

		df_result_string ($result);

		return $result;
	}





	const PARAM_IMPORTED_VALUE = 'importedValue';


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
	        ->addValidator (self::PARAM_IMPORTED_VALUE, new Zend_Validate_NotEmpty ())
		;
	}



}