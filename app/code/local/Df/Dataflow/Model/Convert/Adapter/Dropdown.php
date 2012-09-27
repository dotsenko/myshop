<?php



class Df_Dataflow_Model_Convert_Adapter_Dropdown extends Mage_Dataflow_Model_Convert_Adapter_Abstract {

	public function __construct () {
		df_assert (
			df_enabled (Df_Core_Feature::DATAFLOW_CO)
			,
			"Import of custom options disallowed for you because of absense of license"
		)
		;
	    parent::__construct();
	}


	/**
	 * @return Df_Dataflow_Model_Convert_Adapter_Dropdown
	 */
	public function load () {
		return $this;
	}


	/**
	 * @return Df_Dataflow_Model_Convert_Adapter_Dropdown
	 */
	public function save () {
		return $this;
	}


	/**
	 * @param array $importData
	 * @return Df_Dataflow_Model_Convert_Adapter_Dropdown
	 */
	public function saveRow (array $importData) {
		$this->_rowData = $importData;

		if (!$this->getRowIndex ()) {
			$this->deleteAllOptions ();
		}

		$this
			->getAttribute ()
			->addData (
				array (
					"option" =>
						array (
							"value" =>
								array(
									'option_0' =>
										array (
											$this->getStore () => $this->getOption ()
										)
								)
							,
							"order" =>
								array (
									'option_0' => $this->getRowIndex ()
								)

						)
				)
			)
			->save ()
		;


		$this->setSessionParam ("rowIndex", 1 + $this->getRowIndex ());

		return $this;
	}



	/**
	 * @var int
	 */
	private $_rowIndex;

	/**
	 * @return int
	 */
	private function getRowIndex () {
		if (!isset ($this->_rowIndex)) {
			$this->_rowIndex =
				$this->getSessionParam ("rowIndex", 0)
			;
		}
		return $this->_rowIndex;
	}


	/**
	 * @var Mage_Catalog_Model_Resource_Eav_Attribute
	 */
	private $_attribute;


	/**
	 * @return Mage_Catalog_Model_Resource_Eav_Attribute
	 */
	private function getAttribute () {
		if (!isset ($this->_attribute)) {
			$this->_attribute =
				$this->getAttributeByCode (
					$this->getAttributeCode ()
				)
			;
		}
		return $this->_attribute;
	}



	/**
	 * @param  string $code
	 * @return Mage_Catalog_Model_Resource_Eav_Attribute
	 */
	private function getAttributeByCode ($code) {
		$attributes = $this->getSessionParam ("attributes", array ());
		if (!isset ($attributes [$code])) {
			$attributes [$code] =
				df_model ('catalog/resource_eav_attribute')
					->loadByCode(
						df_model('eav/entity')->setType('catalog_product')->getTypeId()
						,
						$code
					)
			;
			$this->setSessionParam ("attributes", $attributes);
		}
		return $attributes [$code];
	}


	/**
	 * @var array
	 */
	private $_rowData = array ();


	/**
	 * @param string $key
	 * @param null|mixed $default
	 * @return string
	 */
	private function getRowData ($key, $default = null) {
		return isset ($this->_rowData [$key]) ? $this->_rowData [$key] : $default;
	}



	/**
	 * @return string
	 */
	private function getAttributeCode () {
		return $this->getRowData ("attribute", $this->getBatchParams ('attribute'));
	}


	/**
	 * @return string
	 */
	private function getOption () {
		return $this->getRowData ("option");
	}


	/**
	 * @return string
	 */
	private function getStore () {
		return $this->getRowData ("store", $this->getBatchParams ('store'));
	}


	/**
	 * @return void
	 */
	private function deleteAllOptions () {
		$allOptions = $this->getAttribute ()->getSource()->getAllOptions (false);
		$optionsToDelete = array ();
		foreach ($allOptions as $option) {
			$optionsToDelete [$option["value"]]	= 1;
		}

		$options2 = array ();
		foreach ($allOptions as $option) {
			$options2 [$option["value"]]= array ($option["label"]);
		}

		$data =
			array (
				"option" =>
					array (
						"value" => $options2
						,
						"delete"  => $optionsToDelete
					)
			)
		;

		$this->getAttribute ()
			->addData (
				$data
			)
		;

		$this->getAttribute ()->save ();
	}




	/**
	 * @return array
	 */
	private function getSessionStorage () {
		$result =
			Mage::getSingleton('core/session')
				->getData (
					$this->getSessionKey ()
				)
		;
		if (!$result) {
			$result = array ();
		}
		return $result;
	}



	/**
	 * @param array $storage
	 * @return Df_Dataflow_Model_Convert_Adapter_Dropdown
	 */
	private function setSessionStorage (array $storage) {
		Mage::getSingleton('core/session')
			->setData (
				$this->getSessionKey ()
				,
				$storage
			)
		;
		return $this;
	}



	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	private function getSessionParam ($key, $default = NULL) {
		$storage = $this->getSessionStorage ();
		return isset ($storage [$key]) ? $storage [$key] : $default;
	}



	/**
	 * @param  string $key
	 * @param  mixed $value
	 * @return Df_Dataflow_Model_Convert_Adapter_Dropdown
	 */
	private function setSessionParam ($key, $value) {
		$storage = $this->getSessionStorage ();
		$storage [$key] = $value;
		$this->setSessionStorage ($storage);
		return $this;
	}



	/**
	 * @return string
	 */
	private function getSessionKey () {
		return sprintf ("%s_%s", get_class ($this), $_REQUEST["batch_id"]);
	}




}
