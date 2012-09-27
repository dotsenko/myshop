<?php

class Df_Eav_Model_Entity_Attribute_Option_Calculator extends Df_Core_Model_Abstract {


	/**
	 * @return array
	 */
	public function calculate () {


		/** @var Mage_Eav_Model_Entity_Attribute_Source_Table $source  */
		$source = $this->getAttribute()->getSource();

		df_assert ($source instanceof Mage_Eav_Model_Entity_Attribute_Source_Table);


		/**
		 * Похоже, надо сохранять те старые опции, у которых нет идентификаторов из 1С,
		 * потому что они были введены администратором вручную.
		 */


		/** @var array $oldValues  */
		$oldValues = array ();


		/** @var array $oldValuesCustom  */
		$oldValuesCustom = array ();


		/** @var array $oldLabels  */
		$oldLabels = array ();



		foreach ($this->getOptionsOld() as $oldOption) {

			/** @var Mage_Eav_Model_Entity_Attribute_Option $oldOption  */
			df_assert ($oldOption instanceof Mage_Eav_Model_Entity_Attribute_Option);

			/** @var int $value  */
			$value = intval ($oldOption->getData('option_id'));

			df_assert_integer ($value);


			if (
				/**
				 * Сохраняем те старые опции, у которых нет идентификаторов из 1С,
				 * потому что они были введены администратором вручную.
				 */
				is_null ($oldOption->getData(Df_1C_Const::ENTITY_1C_ID))
			) {
				$oldValuesCustom []= $value;
			}


			/** @var string $label  */
			$label = $oldOption->getData ('default_value');

			df_assert_string ($label);


			$oldLabels []= $label;


			$oldValues [$value] = array ($label);
		}



		/** @var int[] $oldValueIds  */
		$oldValueIds = array_keys ($oldValues);

		df_assert_array ($oldValueIds);



		/** @var array $oldMapFromLabelsToValueIds  */
		$oldMapFromLabelsToValueIds =
			df_array_combine (
				$this->labelsNormalize ($oldLabels)
				,
				$oldValueIds
			)
		;

		df_assert_array ($oldMapFromLabelsToValueIds);



		/** @var array $newLabels  */
		$newLabels = $this->extractLabelsFromValues ($this->getOptionsValuesNew());

		df_assert_array ($newLabels);



		/**
		 * @var array $actualValues
		 */
		$actualValues = $oldValues;



		/** @var array $labelsToAdd  */
		$labelsToAdd = $this->labelsDiff ($newLabels, $oldLabels);

		df_assert_array ($labelsToAdd);


		/** @var int $optionIndex  */
		$optionIndex = 0;

		foreach ($labelsToAdd as $labelToAdd) {

			/** @var string $labelToAdd */
			df_assert_string ($labelToAdd);


			/** @var string $valueId */
			$valueId =
				implode (
					'_'
					,
					array (
						'option'
						,
						$optionIndex
					)
				)
			;


			$actualValues [$valueId] = array ($labelToAdd);


			$optionIndex++;

		}




		/** @var array $labelsToDelete */
		$labelsToDelete = $this->labelsDiff ($oldLabels, $newLabels);

		df_assert_array ($labelsToDelete);




		/**
		 * Сначала все старые значения помечаем нулём
		 *
		 * @var array $actualDelete
		 */
		$actualDelete =
			df_array_combine (
				$oldValueIds
				,
				df_array_fill (0, count ($oldValueIds), 0)
			)
		;


		/**
		 * ... и лишь затем то, что надо удалить, помечаем единицей
		 */
		foreach ($labelsToDelete as $labelToDelete) {

			/** @var string $labelToDelete */
			df_assert_string ($labelToDelete);


			/** @var int $valueIdToDelete  */
			$valueIdToDelete =
				df_a (
					$oldMapFromLabelsToValueIds
					,
					$this->labelNormalize ($labelToDelete)
				)
			;

			df_assert_integer ($valueIdToDelete);


			if (
				!(
						/**
						 * Сохраняем те старые опции, у которых нет идентификаторов из 1С,
						 * потому что они были введены администратором вручную.
						 */
						in_array ($valueIdToDelete, $oldValuesCustom)
					||
						/**
						 * В режим вставки программист указывает параметром
						 * Df_Eav_Model_Entity_Attribute_Option_Calculator::PARAM__OPTIONS_VALUES_NEW
						 * не все опции свойства, а лишь новые — те,
						 * которые надо добавить к свойству
						 */
						$this->isModeInsert()
				)
			) {
				$actualDelete [$valueIdToDelete] = 1;
			}
		}



		/** @var array $actualOrders */
		$actualOrders = array ();



		/** @var string $actualLabels  */
		$actualLabels = $this->extractLabelsFromValues ($actualValues);

		df_assert_array ($actualLabels);


		/** @var array $actualMapFromLabelsToValueIds  */
		$actualMapFromLabelsToValueIds =
			df_array_combine (
				$this->labelsNormalize ($actualLabels)
				,
				array_keys ($actualValues)
			)
		;

		df_assert_array ($actualMapFromLabelsToValueIds);



		/** @var array $actualLabelsToSort  */
		$actualLabelsToSort = $actualLabels;


		sort ($actualLabelsToSort);



		$order = 0;

		foreach ($actualLabelsToSort as $sortedLabel) {

			/** @var string $sortedLabel */
			df_assert_string ($sortedLabel);

			/** @var string|int $valueId  */
			$valueId = df_a ($actualMapFromLabelsToValueIds, $this->labelNormalize ($sortedLabel));

			$actualOrders [$valueId] = $order;

			$order++;

		}



		/** @var array $result  */
		$result =
			array (
				'value' => $actualValues
				,
				'order' => $actualOrders
				,
				'delete' => $actualDelete
			)
		;

		df_result_array ($result);

		return $result;

	}





	/**
	 * @param array $values
	 * @return array
	 */
	private function extractLabelsFromValues (array $values) {

		/** @var array $result  */
		$result = array ();

		foreach ($values as $label) {

			/** @var string|array $label */

			if (is_array ($label)) {
				$label = df_a ($label, 0);
			}

			df_assert_string ($label);

			$result []= $label;

		}


		df_result_array ($result);

		return $result;

	}





	/**
	 * @return Mage_Eav_Model_Resource_Entity_Attribute_Option_Collection|Mage_Eav_Model_Mysql4_Entity_Attribute_Option_Collection
	 */
	private function getOptionsOld () {

		if (!isset ($this->_optionsOld)) {

			/** @var int $attributeId  */
			$attributeId = intval ($this->getAttribute()->getId());

			df_assert_integer ($attributeId);


			/** @var int $storeId  */
			$storeId = intval ($this->getAttribute()->getDataUsingMethod ('store_id'));

			df_assert_integer ($storeId);


			/** @var Mage_Eav_Model_Resource_Entity_Attribute_Option_Collection|Mage_Eav_Model_Mysql4_Entity_Attribute_Option_Collection $result */
			$result = Mage::getResourceModel('eav/entity_attribute_option_collection');

			df_helper()->eav()->assert()->entityAttributeOptionCollection ($result);


			$result->setPositionOrder ('asc');
			$result->setAttributeFilter ($attributeId);
			$result->setStoreFilter ($this->getAttribute()->getDataUsingMethod ('store_id'));

			$this->_optionsOld = $result;

		}

		df_helper()->eav()->assert()->entityAttributeOptionCollection ($this->_optionsOld);

		return $this->_optionsOld;

	}


	/**
	* @var Mage_Eav_Model_Resource_Entity_Attribute_Option_Collection|Mage_Eav_Model_Mysql4_Entity_Attribute_Option_Collection
	*/
	private $_optionsOld;




	/**
	 * @return Mage_Eav_Model_Entity_Attribute
	 */
	private function getAttribute () {
		return $this->cfg (self::PARAM__ATTRIBUTE);
	}



	/**
	 * @return array
	 */
	private function getOptionsValuesNew () {
		return $this->cfg (self::PARAM__OPTIONS_VALUES_NEW);
	}



	/**
	 * @return bool
	 */
	private function isModeCaseInsensitive () {
		return $this->cfg (self::PARAM__MODE__CASE_INSENSITIVE, false);
	}



	/**
	 * @return bool
	 */
	private function isModeInsert () {
		return $this->cfg (self::PARAM__MODE__INSERT, false);
	}




	/**
	 * @param string $label
	 * @return string
	 */
	private function labelNormalize ($label) {

		df_param_string ($label, 0);

		/** @var string $result  */
		$result =
				$this->isModeCaseInsensitive()
			?
				mb_strtolower($label)
			:
				$label
		;

		df_result_string ($result);

		return $result;
	}




	/**
	 * @param array $labels1
	 * @param array $labels2
	 * @return string
	 */
	private function labelsDiff (array $labels1, array $labels2) {


		/** @var array $map  */
		$map =
			df_array_combine (
				$this->labelsNormalize ($labels1)
				,
				$labels1
			)
		;

		df_assert_array ($map);


		/** @var array $diff  */
		$diff =
			array_diff (
				$this->labelsNormalize ($labels1)
				,
				$this->labelsNormalize ($labels2)
			)
		;

		df_assert_array ($diff);


		/** @var array $result  */
		$result = array ();


		foreach ($diff as $label) {

			/** @var string $label */

			$result []= df_a ($map, $label);

		}

		df_result_array ($result);

		return $result;

	}






	/**
	 * @param array $labels
	 * @return string
	 */
	private function labelsNormalize (array $labels) {

		/** @var string $result  */
		$result = array ();

		foreach ($labels as $label) {

			/** @var string $label */
			df_assert_string ($label);

			$result []= $this->labelNormalize ($label);
		}

		df_result_array ($result);

		return $result;

	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__ATTRIBUTE, 'Mage_Eav_Model_Entity_Attribute'
			)
			->addValidator (
				self::PARAM__MODE__CASE_INSENSITIVE, new Df_Zf_Validate_Boolean(), false
			)
			->addValidator (
				self::PARAM__MODE__INSERT, new Df_Zf_Validate_Boolean(), false
			)
			->addValidator (
				self::PARAM__OPTIONS_VALUES_NEW, new Df_Zf_Validate_Array()
			)
		;
	}



	const PARAM__ATTRIBUTE = 'attribute';
	const PARAM__MODE__CASE_INSENSITIVE = 'mode__case_insensitive';
	const PARAM__MODE__INSERT = 'mode__insert';
	const PARAM__OPTIONS_VALUES_NEW = 'options_values_new';




	/**
	 * @param Mage_Eav_Model_Entity_Attribute $attribute
	 * @param array $optionsNew
	 * @param bool $isModeInsert [optional]
	 * @param bool $caseInsensitive [optional]
	 * @return array
	 */
	public static function calculateStatic (
		Mage_Eav_Model_Entity_Attribute $attribute
		,
		array $optionsNew
		,
		$isModeInsert = false
		,
		$caseInsensitive = false
	) {

		df_param_boolean ($isModeInsert, 2);
		df_param_boolean ($caseInsensitive, 3);

		/** @var Df_Eav_Model_Entity_Attribute_Option_Calculator $calculator  */
		$calculator =
			df_model (
				Df_Eav_Model_Entity_Attribute_Option_Calculator::getNameInMagentoFormat()
				,
				array (
					Df_Eav_Model_Entity_Attribute_Option_Calculator
						::PARAM__ATTRIBUTE => $attribute
					,
					Df_Eav_Model_Entity_Attribute_Option_Calculator
						::PARAM__MODE__CASE_INSENSITIVE => $caseInsensitive
					,
					Df_Eav_Model_Entity_Attribute_Option_Calculator
						::PARAM__MODE__INSERT => $isModeInsert
					,
					Df_Eav_Model_Entity_Attribute_Option_Calculator
						::PARAM__OPTIONS_VALUES_NEW => $optionsNew
				)
			)
		;

		df_assert ($calculator instanceof Df_Eav_Model_Entity_Attribute_Option_Calculator);

		/** @var array $result  */
		$result = $calculator->calculate();

		df_result_array ($result);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Eav_Model_Entity_Attribute_Option_Calculator';
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


