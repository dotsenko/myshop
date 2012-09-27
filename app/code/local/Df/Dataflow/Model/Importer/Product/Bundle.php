<?php



class Df_Dataflow_Model_Importer_Product_Bundle extends Df_Dataflow_Model_Importer_Product_Specialized {





	/**
	 * @override
	 * @return Df_Dataflow_Model_Importer_Product_Bundle
	 */
	public function process () {

		if ('bundle' == $this->getProduct()->getTypeId ()) {


			if (!is_null ($this->getProduct()->getId ())) {


				/** @var Df_Bundle_Model_Resource_Bundle $bundleResource  */
				$bundleResource = Mage::getResourceModel ('df_bundle/bundle');

				df_assert ($bundleResource instanceof Df_Bundle_Model_Resource_Bundle);

				$bundleResource->deleteAllOptions ($this->getProduct()->getId ());

			}


			/**
			 * Не знаю, зачем это, но так рекомендуют на Stack Overflow
			 * @link http://stackoverflow.com/questions/3108775/programmatically-add-bundle-products-in-magento-using-the-sku-id-of-simple-ite
			 */
			Mage::register('product', $this->getProduct());
			Mage::register('current_product', $this->getProduct());

			$this->getProduct()
				->addData (
					array (
						'can_save_configurable_attributes' => false
						,
						'can_save_custom_options' => true
						,
						'can_save_bundle_selections' => true


						,
						'has_options' => 1

						/**
						 * Обратите внимание, что с связь между bundle_options и bundle_selections
						 * при импорте осуществляется вовсе не через option_id,
						 * а через порядок следования данных.
						 *
						 * Элементу с порядковым номером N в массиве bundle_options_data
						 * соответствует элемент с порядковым номером N в массиве bundle_selections_data.
						 *
						 * Учитывая, что элементы массива bundle_selections_data — это массивы (группы подэлементов),
						 * между bundle_options и bundle_selections получается связь «один ко многим».
						 */
						,
						'bundle_options_data' => $this->getBundleOptions ()
						,
						'bundle_selections_data' => $this->getBundleSelections ()
					)
				)
			;

		}


		return $this;

	}








	/**
	 * @return array
	 */
	private function getBundleOptions () {

		if (!isset ($this->_bundleOptions)) {

			/** @var array $result  */
			$result =
				array ()
			;



			/**
			 * Порядковый номер внутреннего товара в сборном товаре
			 * @var int $innerProductOrdering
			 */
			$innerProductOrdering = 0;

			foreach ($this->getInnerProducts() as $innerProduct) {

				/** @var array $innerProduct */

				df_assert_array ($innerProduct);


				$innerProductOrdering++;


				$result []=
					$this->createBundleOption ($innerProduct, $innerProductOrdering)
				;
			}


			df_assert_array($result);

			$this->_bundleOptions = $result;

		}


		df_result_array ($this->_bundleOptions);

		return $this->_bundleOptions;

	}


	/**
	* @var array
	*/
	private $_bundleOptions;








	/**
	 * @return array
	 */
	private function getBundleSelections () {

		if (!isset ($this->_bundleSelections)) {

			/** @var array $result  */
			$result =
				array ()
			;



			foreach ($this->getInnerProducts() as $innerProduct) {

				/** @var array $innerProduct */

				df_assert_array ($innerProduct);


				$result []=

					array (
						/**
						 * Всего один элемент в массиве,
						 * потому что мы не даёт покупателю выбор составных частей сборного товара
						 */
						$this->createBundleSelection ($innerProduct)
					)
				;
			}



			df_assert_array($result);

			$this->_bundleSelections = $result;

		}


		df_result_array ($this->_bundleSelections);

		return $this->_bundleSelections;

	}


	/**
	* @var array
	*/
	private $_bundleSelections;






	/**
	 * @param array $innerProduct
	 * @return array
	 */
	private function createBundleSelection (array $innerProduct) {

		df_param_array ($innerProduct, 0);


		/** @var string $innerProductSku  */
		$innerProductSku = df_a ($innerProduct, 'material');

		df_assert_string ($innerProductSku);



		/** @var int|null $innerProductId  */
		$innerProductId =
			df_helper ()->catalog()->product()->getIdBySku (
				$innerProductSku
			)
		;


		df_assert (
			!is_null ($innerProductId)
			,
			sprintf (
						'Перед импортом сборного товара «%s» '
					.
						'в системе уже должна прустутствовать его составная часть: простой товар «%s».'
				,

					$this->getBundleOriginalSku()
				,
					$innerProductSku
			)
		)
		;



		/** @var array $result  */
		$result =
			array (
				'product_id' => $innerProductId

				,
				'selection_price_value' => 0

				,
				'selection_price_type' => 0

				,
				'selection_qty' => df_a ($innerProduct, 'Count')

				,
				'selection_can_change_qty' => 0

				/**
				 * Всегда 0, потому что других вариантов выбора у пользователя нет
				 */
				,
				'position' => 0


				/**
				 * Иначе «undefined index»
				 */
				,
				'delete' => NULL
			)
		;



		df_result_array ($result);

		return $result;

	}








	/**
	 * @param array $innerProduct
	 * @param int $innerProductOrdering
	 * @return array
	 */
	private function createBundleOption (array $innerProduct, $innerProductOrdering) {

		df_param_array ($innerProduct, 0);
		df_param_integer ($innerProductOrdering, 1);



		/** @var string $innerProductSku  */
		$innerProductSku = df_a ($innerProduct, 'material');

		df_assert_string ($innerProductSku);



		/** @var int|null $innerProductId  */
		$innerProductId =
			df_helper ()->catalog()->product()->getIdBySku (
				$innerProductSku
			)
		;

		df_assert (
			!is_null ($innerProductId)
			,
			sprintf (
						'Перед импортом сборного товара «%s» '
					.
						'в системе уже должна прустутствовать его составная часть: простой товар «%s».'
				,

					$this->getBundleOriginalSku()
				,
					$innerProductSku
			)
		)
		;



		/** @var array $result  */
		$result =
			array (
				/**
				 * По собственному желанию используем артикул внутреннего товара как заголовок опции.
				 * Система этого не требует
				 */
				'title' => $innerProductSku
				,
				'type' => 'select'
				,
            	'required' => 1
				,
            	'position' => $innerProductOrdering

				/**
				 * Иначе «undefined index»
				 */
				,
				'delete' => NULL
			)
		;



		df_result_array ($result);

		return $result;

	}







	/**
	 * Это тот артикул сборного товара, который известен администратору.
	 * Обратите внимание, что данный артикул в тестовых данных не является уникальным,
	 * поэтому система присвоит сборному товару новый артикул,
	 * добавив к оригинальному пртикулу суффикс  «-bundle».
	 *
	 * @return string
	 */
	private function getBundleOriginalSku () {

		/** @var string $result  */
		$result =
			df_a ($this->getImportedRow(), 'vichy_original_sku')
		;


		df_result_string ($result);

		return $result;

	}



	
	
	
	/**
	 * @return array
	 */
	private function getInnerProducts () {
	
		if (!isset ($this->_innerProducts)) {
	
			/** @var array $result  */
			$result =
				Zend_Json::decode (
					df_a (
						$this->getImportedRow ()
						,
						Df_Dataflow_Model_Import_Product_Row::FIELD__BUNDLE
					)
				)
			;
	
			df_assert_array ($result);
	
			$this->_innerProducts = $result;
	
		}
	
	
		df_result_array ($this->_innerProducts);
	
		return $this->_innerProducts;
	
	}
	
	
	/**
	* @var array
	*/
	private $_innerProducts;	







	/**
	 * @static
	 * @return array
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Importer_Product_Bundle';
	}


	/**
	 * Например, для класса Df_SalesRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return array
	 */
	public static function getNameInMagentoFormat () {
		return
			df()->reflection()->getModelNameInMagentoFormat (
				self::getClass()
			)
		;
	}



}