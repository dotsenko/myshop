<?php



class Df_Dataflow_Model_Import_Product_Row extends Df_Dataflow_Model_Import_Abstract_Row {



	/**
	 * @return bool
	 */
	public function isProductNew () {

		/** @var bool $result  */
		$result =
			is_null ($this->getId ())
		;

		df_result_boolean ($result);

		return $result;

	}





	/**
	 * Идентификатор товара, расчитанный на основе артикула.
	 * При импорте новых товаров - отсутствует.
	 *
	 * @return int|null
	 */
	public function getId () {

		if (!isset ($this->_id)) {

			/** @var int|null $result  */
			$result =
				df_helper()->catalog()->product()->getIdBySku (
					$this->getSku ()
				)
			;


			/**
			 * Отсутствие идентификатора в данном случае - нормальная ситация.
			 * Идентификатор отсутствует для новых товаров.
			 */
	        if (!is_null ($result)) {
				df_assert_integer ($result);
			}

			$this->_id = $result;

		}


		if (!is_null ($this->_id)) {
			df_result_integer ($this->_id);
		}

		return $this->_id;

	}


	/**
	* @var int|null
	*/
	private $_id;










	/**
	 * @return string
	 */
	public function getSku () {

		/** @var string $result  */
		$result =
			$this->getFieldValue (self::FIELD__SKU, true)
		;

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return string|null
	 */
	public function getCategoryIdsAsString () {

		/** @var string|null $result  */
		$result =
			$this->getFieldValue (self::FIELD__CATEGORY_IDS)
		;


		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}

	
	
	
	/**
	 * @return array
	 */
	public function getWebsites () {
	
		if (!isset ($this->_websites)) {
	
			/** @var array $result  */
			$result =
				array ()
			;

			if (!is_null ($this->getWebsitesAsString())) {

				/** @var array $websiteCodes  */
				$websiteCodes = explode(Df_Core_Const::T_COMMA, $this->getWebsitesAsString());

				df_assert_array ($websiteCodes);


				foreach ($websiteCodes as $websiteCode) {

					/** @var string $websiteCode  */

					/** @var Mage_Core_Model_Website $website  */
					$website =
						Mage::app()->getWebsite (
							df_trim($websiteCode)
						)
					;

					df_assert (
						$website instanceof Mage_Core_Model_Website
						,
						sprintf (
							"Сайт с кодом «%s», указанный в строке №%d, не найден в системе."
							,
							$websiteCode
							,
							$this->getOrdering()
						)
					)
					;


					$result []= $website;
				}

			}
	
	
			df_assert_array ($result);
	
			$this->_websites = $result;
	
		}
	
	
		df_result_array ($this->_websites);
	
		return $this->_websites;
	
	}
	
	
	/**
	* @var array
	*/
	private $_websites;	
	

	



	/**
	 * @return string|null
	 */
	private function getWebsitesAsString () {

		/** @var string|null $result  */
		$result =
			$this->getFieldValue (self::FIELD__WEBSITES)
		;


		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}








	/**
	 * @return Mage_Core_Model_Store
	 */
	public function getStore () {

		if (!isset ($this->_store)) {


			/** @var string|null $storeCode  */
			$storeCode =
				$this->getFieldValue (
					self::FIELD__STORE
				)
			;

			if (is_null ($storeCode)) {
				$storeCode =
					$this->getConfig()->getParam (
						Df_Dataflow_Model_Import_Config::DATAFLOW_PARAM__STORE
					)
				;
			}


			df_assert (
				!is_null ($storeCode)
				,
				sprintf (
							'Вы должны либо заполнить поле «%s» в строке импортируемых данных №%d, '
						.
							'либо заполнить поле «%s» в профиле Magento Dataflow.'
					,
						self::FIELD__STORE
					,
						$this->getOrdering()
					,
						Df_Dataflow_Model_Import_Config::DATAFLOW_PARAM__STORE
				)
			)
			;


			df_assert_string ($storeCode);


			/** @var Mage_Core_Model_Store $result  */
			$result =
				Mage::app()->getStore ($storeCode)
			;


			df_assert (
				$result instanceof Mage_Core_Model_Store
				,
				sprintf (
							"В строке импортируемых данных №%d указан несуществующий магазин «%s»."
						.
							"\nВы должны либо для каждого импортируемого товара указать магазин"
						.
							" в поле «%s» строки импортируемых данных,"
						.
							" либо указать магазин по умолчанию в поле «%s» профиля Magento Dataflow."
					,
						$this->getOrdering()
					,
						$storeCode
					,
						self::FIELD__STORE
					,
						Df_Dataflow_Model_Import_Config::DATAFLOW_PARAM__STORE
				)
			)
			;

			$this->_store = $result;

		}


		df_assert ($this->_store instanceof Mage_Core_Model_Store);

		return $this->_store;

	}


	/**
	* @var Mage_Core_Model_Store
	*/
	private $_store;





	/**
	 * @return int|null
	 */
	public function getAttributeSetId () {

		if (!isset ($this->_attributeSetId)) {

			if (is_null ($this->getAttributeSetName())) {

				$result = null;

			}

			else {

				/** @var Mage_Eav_Model_Entity_Attribute_Set|null $attributeSet  */
				$attributeSet =
					df_helper()->dataflow()->registry()->attributeSets()->findByLabel (
						$this->getAttributeSetName()
					)
				;


				df_assert (
					!is_null ($attributeSet)
					,
					sprintf (
							'Прикладной тип товара «%s», '
						.
							'указаный в поле «attribute_set» строки №%d импортируемых данных, '
						.
							'неизвестен системе.'
						,
							$this->getAttributeSetName()
						,
							$this->getOrdering()
					)
				)
				;

				/** @var int $result  */
				$result = intval ($attributeSet->getId());

			}


			if (!is_null ($result)) {
				df_result_integer ($result);
			}


			$this->_attributeSetId = $result;

		}


		if (!is_null ($this->_attributeSetId)) {
			df_result_integer ($this->_attributeSetId);
		}


		return $this->_attributeSetId;

	}


	/**
	* @var int|null
	*/
	private $_attributeSetId;







	/**
	 * Обратите внимание, что идентификатором типа товаров является строка, а не число.
	 * Пример идентификатарора: «simple», «bundle»
	 *
	 * @return string|null
	 */
	public function getProductType () {

		/** @var string|null $result  */
		$result =
			$this->getFieldValue (
				self::FIELD__PRODUCT_TYPE

				,
				false

				,
				$this->getConfig()->getParam (self::FIELD__PRODUCT_TYPE)
			)
		;



		df_assert (
			is_string ($result)
			,
			sprintf (
						'Вы должны либо заполнить поле «%s» в строке импортируемых данных №%d, '
					.
						'либо заполнить поле «%s» в профиле Magento Dataflow.'
				,
					self::FIELD__PRODUCT_TYPE
				,
					$this->getOrdering()
				,
					Df_Dataflow_Model_Import_Config::DATAFLOW_PARAM__PRODUCT_TYPE
			)
		)
		;


		df_result_string ($result);


		return $result;

	}






	/**
	 * @return string|null
	 */
	private function getAttributeSetName () {

		/** @var string|null $result  */
		$result =
			$this->getFieldValue (
				self::FIELD__ATTRIBUTE_SET
				,

				/**
				 * Название набора свойств обязательно для указания только для новых товаров
				 */
				$this->isProductNew()
			)
		;

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}








	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Import_Product_Row';
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



	const FIELD__SKU = 'sku';
	const FIELD__STORE = 'store';
	const FIELD__PRODUCT_TYPE = 'type';

	const FIELD__ATTRIBUTE_SET = 'attribute_set';
	const FIELD__CATEGORY_IDS = 'category_ids';
	const FIELD__WEBSITES = 'websites';



	const FIELD__BUNDLE = 'df_bundle';

}


