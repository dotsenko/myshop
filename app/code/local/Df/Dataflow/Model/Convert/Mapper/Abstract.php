<?php



abstract class Df_Dataflow_Model_Convert_Mapper_Abstract extends Mage_Dataflow_Model_Convert_Mapper_Abstract {


	/**
	 * @abstract
	 * @param  array $row
	 * @return void
	 */
	abstract protected function processRow (array $row);


	/**
	 * @abstract
	 * @return string
	 */
	abstract protected function getFeatureCode ();





	/**
	 * @param array $row
	 * @param string $fieldName
	 * @param bool $isRequired [optional]
	 * @param string|null $defaultValue [optional]

	 * @return string
	 */
	protected function getFieldValue (array $row, $fieldName, $isRequired = false, $defaultValue = null) {

		df_param_array ($row, 0);
		df_param_string ($fieldName, 1);
		df_param_boolean ($isRequired, 2);

		if (!is_null ($defaultValue)) {
			df_param_string ($defaultValue, 3);
		}


		/** @var string|null $result  */
		$result = df_a ($row, $fieldName);

		if (!is_null ($result)) {
			df_assert_string ($result);
		}


		if (is_null ($result)) {

			df_assert (
				!$isRequired
				,
				sprintf (
					'В строке импортируемых данных необходимо заполнить поле «%s»'
					,
					$fieldName
				)
			)
			;

			$result = $defaultValue;

		}


		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}




	/**
	 * @return Df_Licensor_Model_Feature
	 */
	protected function getFeature () {

		/** @var Df_Licensor_Model_Feature $result */
		$result = df_feature ($this->getFeatureCode ());

		df_assert ($result instanceof Df_Licensor_Model_Feature);

		return $result;

	}







	/**
	 * @return Df_Dataflow_Model_Convert_Mapper_Abstract
	 */
	public function map () {

		if (!$this->getFeature()->isEnabled()) {
			$this
				->addException (
					sprintf (
						self::T_LICENSE_NEEDED
						,
						$this->getFeature ()->getTitle ()
					)
					,
					Mage_Dataflow_Model_Convert_Exception::ERROR
				)
			;
		}
		else {

			foreach ($this->getSourceRowIds () as $sourceRowId) {
				/** @var int $sourceRowModel  */

				df_assert_integer ($sourceRowId);

				/** @var Mage_Dataflow_Model_Batch_Abstract $sourceRowModel  */
				$sourceRowModel =
					$this
						->createSourceRowModel ()
							->load ($sourceRowId)
				;

				df_assert ($sourceRowModel instanceof Mage_Dataflow_Model_Batch_Abstract);


				/** @var array $sourceRow  */
				$sourceRow =
					$sourceRowModel
						->getBatchData ()
				;

				df_assert_array ($sourceRow);


				/** @var array $destinationRow  */
				$destinationRow =
					$this->processRow ($sourceRow)
				;

				df_assert_array ($destinationRow);




				/** @var Mage_Dataflow_Model_Batch_Abstract $destinationRowModel  */
				$destinationRowModel =
						$this->isSourceAndDestinationAreSame ()
					?
						$sourceRowModel
					:
						$this->createDestinationRowModel()
				;

				df_assert ($destinationRowModel instanceof Mage_Dataflow_Model_Batch_Abstract);



				$destinationRowModel
					->setBatchData($destinationRow)
					->setData ('status', 2)
				;


				$destinationRowModel->save();



				// update column list (list of field names)
				$this
					->getBatchModel()
					->parseFieldList (
						$destinationRowModel
							->getBatchData()
					)
				;
			}
		}

        return $this;
	}




	const T_LICENSE_NEEDED = 'Для использования данного профиля импорта/экспорта вы должны приобрести лицензию на модуль «%s»';




	/**
	 * @return Mage_Dataflow_Model_Batch
	 */
    public function getBatchModel() {

        if (is_null($this->_batch)) {

			/** @var Mage_Dataflow_Model_Batch $result  */
            $result = Mage::getSingleton (Df_Dataflow_Const::BATCH_CLASS_MF);

	        if (!$result->getData (Df_Dataflow_Model_Batch::PARAM_ADAPTER)) {

				/** @var string|null $adapterClassMf  */
				$adapterClassMf = $this->getVar(self::VAR_ADAPTER);


				$result
					->setParams($this->getVars())
				;

				if (!is_null ($adapterClassMf)) {

					df_assert_string ($adapterClassMf);

					$result
						->setData (Df_Dataflow_Model_Batch::PARAM_ADAPTER, $adapterClassMf)
					;
				}

				$result->save ();
	        }


			df_assert ($result instanceof Mage_Dataflow_Model_Batch);

			$this->_batch = $result;

        }


		df_assert ($this->_batch instanceof Mage_Dataflow_Model_Batch);

        return $this->_batch;
    }


	/**
	 * @var Mage_Dataflow_Model_Batch
	 */
    protected $_batch;










	/**
	 * @return bool
	 */
	protected function isSourceAndDestinationAreSame () {

		if (!isset ($this->_sourceAndDestinationAreSame)) {

			/** @var bool $result  */
			$result =
				(
						$this->getBatchTableAlias (self::DESTINATION)
					==
						$this->getBatchTableAlias (self::SOURCE)
				)
			;

			df_assert_boolean ($result);

			$this->_sourceAndDestinationAreSame = $result;

		}

		df_result_boolean ($this->_sourceAndDestinationAreSame);

		return $this->_sourceAndDestinationAreSame;
	}


	/**
	 * @var bool
	 */
	private $_sourceAndDestinationAreSame;








	/**
	 * @return array
	 */
	protected function getSourceRowIds () {

		if (!isset ($this->_sourceRowIds)) {

			/** @var array $result  */
			$result =
				$this
					->getSourceRowModelSingleton ()
					->getIdCollection()
			;

			df_assert_array ($result);

			$this->_sourceRowIds = $result;

		}


		df_result_array ($this->_sourceRowIds);

	    return $this->_sourceRowIds;
	}



	/**
	 * @var array
	 */
	private $_sourceRowIds;








	/**
	 * @return array
	 */
	protected function getMap () {

		if (!isset ($this->_map)) {


			/** @var array $result  */
			$result = $this->getVar(self::VAR_MAP);

			if (is_null ($result)) {
				$result = array ();
			}

			df_assert_array ($result);

			$this->_map = $result;

		}

		df_result_array ($this->_map);

		return $this->_map;
	}


	/**
	 * @var array
	 */
	private $_map;








	/**
	 * @return Mage_Dataflow_Model_Batch_Abstract
	 */
	protected function getSourceRowModelSingleton () {

		if (!isset ($this->_sourceRowModelSingleton)) {

			/** @var Mage_Dataflow_Model_Batch_Abstract $result  */
			$result = $this->createSourceRowModel ();

			df_assert ($result instanceof Mage_Dataflow_Model_Batch_Abstract);

			$this->_sourceRowModelSingleton =
				$result
			;
		}

		df_assert ($this->_sourceRowModelSingleton instanceof Mage_Dataflow_Model_Batch_Abstract);

		return $this->_sourceRowModelSingleton;
	}



	/**
	 * @var Mage_Dataflow_Model_Batch_Abstract
	 */
	private $_sourceRowModelSingleton;








	/**
	 * @return Mage_Dataflow_Model_Batch_Abstract
	 */
	protected function createSourceRowModel () {

		/** @var Mage_Dataflow_Model_Batch_Abstract $result  */
        $result =
			$this->createRowModel (
				$this->getBatchTableAlias (self::SOURCE)
			)
		;

		df_assert ($result instanceof Mage_Dataflow_Model_Batch_Abstract);

		return $result;
	}



	/**
	 * @return Mage_Dataflow_Model_Batch_Abstract
	 */
	protected function createDestinationRowModel () {


		/** @var Mage_Dataflow_Model_Batch_Abstract $result  */
        $result =
			$this->createRowModel (
				$this->getBatchTableAlias (self::DESTINATION)
			)
		;

		df_assert ($result instanceof Mage_Dataflow_Model_Batch_Abstract);

		return $result;
	}






	/**
	 * @param  string $importOrExport  ("import" or "export")
	 * @return Mage_Dataflow_Model_Batch_Abstract
	 */
	protected function createRowModel ($importOrExport) {

		df_param_string ($importOrExport, 0);


		/** @var Mage_Dataflow_Model_Batch_Abstract $result  */
		$result =
			df_model(
				sprintf (
					"dataflow/batch_%s"
					,
					$importOrExport
				)
			)
		;

		df_assert ($result instanceof Mage_Dataflow_Model_Batch_Abstract);


		$result
			->setData (
				'batch_id'
				,
				$this
					->getBatchModel()
					->getId()
			)
		;


		df_assert ($result instanceof Mage_Dataflow_Model_Batch_Abstract);

		return $result;

	}











	/**
	 * @param  string $souceOrDestination  ("source" or "destination")
	 * @return string
	 */
	private function getBatchTableAlias ($souceOrDestination) {

		df_param_string ($souceOrDestination, 0);




		/** @var array $validResults  */
		$validResults = array (self::IMPORT, self::EXPORT);

		df_assert_array ($validResults);




		/** @var string $paramName  */
		$paramName =
			$this->convertBatchTableTypeToParamName (
				$souceOrDestination
			)
		;

		df_assert_string ($paramName);




		/** @var string $result  */
		$result =
			(string)
				$this->getVar (
					$paramName
				)
		;

		df_assert (
			!df_empty ($result)
			,
			sprintf (
				"You must specify «%s» parameter in the profile XML"
				,
				$paramName
			)
		)
		;

		df_assert (
			in_array ($result, $validResults)
			,
			sprintf (
					"Invalid value «%s» for parameter «%s» in the profile XML"
				    .
					"\nValid values are: «import» and «export»"
				,
				$result
				,
				$paramName
			)
		)
		;


		df_result_string ($result);


		return $result;
	}






	/**
	 * @param  string $souceOrDestination
	 * @return string
	 */
	private function convertBatchTableTypeToParamName ($souceOrDestination) {

		df_param_string ($souceOrDestination, 0);

		/** @var string $result  */
		$result =
			sprintf (
				self::DF_TABLE_TYPE_TEMPLATE
				,
				$souceOrDestination
			)
		;

		df_result_string ($result);

		return $result;
	}


	const DF_TABLE_TYPE_TEMPLATE = 'df-table-%s';


	const VAR_MAP = 'map';
	const VAR_ADAPTER = 'adapter';


	const SOURCE = 'source';
	const DESTINATION = 'destination';

	const IMPORT = 'import';
	const EXPORT = 'export';


}
