<?php

class Df_1C_Model_Cml2_Action_Catalog_Import extends Df_1C_Model_Cml2_Action_Catalog {


	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Action
	 */
	protected function processInternal () {

		switch ($this->getFileRelativePath()) {

			case Df_1C_Model_Cml2_Registry_Import_Files::FILE__IMPORT:

				$this->importCategories();

				$this->importReferenceLists();

				break;



			case Df_1C_Model_Cml2_Registry_Import_Files::FILE__OFFERS:

				$this->importProductsSimple();

				$this->importProductsSimplePartImages();

				$this->importProductsConfigurable();

				$this->importProductsConfigurablePartImages ();



				/** @var Mage_Index_Model_Indexer $indexer  */
				$indexer = Mage::getSingleton('index/indexer');

				df_assert ($indexer instanceof Mage_Index_Model_Indexer);

				foreach ($indexer->getProcessesCollection() as $process) {

					/** @var Mage_Index_Model_Process $process */
					df_assert ($process instanceof Mage_Index_Model_Process);

					/**
					 * @todo лучше перестраивать расчётные таблицы только для обрабатываемого магазина
					 * сейчас же мы перестраиваем расчётные таблицы для всех магазинов системы
					 */
					$process->reindexEverything();
				}


				break;



			default:
				df_error (
					sprintf (
						'Непредусмотренный файл: %s'
						,
						$this->getFileRelativePath()
					)
				);
				break;
		}



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
	 * @return Varien_Simplexml_Element
	 */
	protected function getSimpleXmlElement () {

		/** @var Varien_Simplexml_Element $result  */
		$result =
			$this->getFiles()->getByRelativePath (
				$this->getFileRelativePath()
			)
		;

		df_assert ($result instanceof Varien_Simplexml_Element);

		return $result;

	}





	/**
	 * @return Df_1C_Model_Cml2_Action_Catalog_Import
	 */
	private function importCategories () {

		df_helper()->_1c()->log ('Импорт товарных разделов начат.');


		foreach ($this->getRegistry()->import()->collections()->getCategories() as $category) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity_Category $category */
			df_assert ($category instanceof Df_1C_Model_Cml2_Import_Data_Entity_Category);

			/** @var Df_1C_Model_Cml2_Import_Processor_Category $processor  */
			$processor =
				df_model (
					Df_1C_Model_Cml2_Import_Processor_Category::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Processor_Category
							::PARAM__PARENT => $this->getRegistry()->import()->getRootCategory()
						,
						Df_1C_Model_Cml2_Import_Processor_Category::PARAM__ENTITY => $category
					)
				)
			;

			df_assert ($processor instanceof Df_1C_Model_Cml2_Import_Processor_Category);


			$processor->process();

		}


		df_helper()->_1c()->log ('Импорт товарных разделов завершён.');

		return $this;

	}





	/**
	 * @return Df_1C_Model_Cml2_Action_Catalog_Import
	 */
	private function importProductsConfigurable () {

		df_helper()->_1c()->log ('Импорт настраиваемых товаров начат.');


		foreach ($this->getRegistry()->import()->collections()->getOffers() as $offer) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity_Offer $offer */
			df_assert ($offer instanceof Df_1C_Model_Cml2_Import_Data_Entity_Offer);


			/** @var Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable $processor */
			$processor =
				df_model (
					Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable::PARAM__ENTITY => $offer
					)
				)
			;

			df_assert ($processor instanceof Df_1C_Model_Cml2_Import_Processor_Product_Type_Configurable);


			$processor->process();

		}


		df_helper()->_1c()->log ('Импорт настраиваемых товаров завершён.');

		return $this;

	}





	/**
	 * @return Df_1C_Model_Cml2_Action_Catalog_Import
	 */
	private function importProductsConfigurablePartImages () {

		foreach ($this->getRegistry()->import()->collections()->getOffers() as $offer) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity_Offer $offer */
			df_assert ($offer instanceof Df_1C_Model_Cml2_Import_Data_Entity_Offer);


			if ($offer->isTypeConfigurableParent()) {

				/** @var Df_1C_Model_Cml2_Import_Processor_Product_Part_Images $processor */
				$processor =
					df_model (
						Df_1C_Model_Cml2_Import_Processor_Product_Part_Images::getNameInMagentoFormat()
						,
						array (
							Df_1C_Model_Cml2_Import_Processor_Product_Part_Images::PARAM__ENTITY => $offer
						)
					)
				;

				df_assert ($processor instanceof Df_1C_Model_Cml2_Import_Processor_Product_Part_Images);


				$processor->process();

			}

		}


		return $this;

	}






	/**
	 * @return Df_1C_Model_Cml2_Action_Catalog_Import
	 */
	private function importProductsSimple () {

		df_helper()->_1c()->log ('Импорт простых товаров начат.');


		foreach ($this->getRegistry()->import()->collections()->getOffers() as $offer) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity_Offer $offer */
			df_assert ($offer instanceof Df_1C_Model_Cml2_Import_Data_Entity_Offer);

			/** @var Df_1C_Model_Cml2_Import_Processor_Product_Type_Simple $processor */
			$processor =
				df_model (
					Df_1C_Model_Cml2_Import_Processor_Product_Type_Simple::getNameInMagentoFormat()
					,
					array (
						Df_1C_Model_Cml2_Import_Processor_Product_Type_Simple::PARAM__ENTITY => $offer
					)
				)
			;

			df_assert ($processor instanceof Df_1C_Model_Cml2_Import_Processor_Product_Type_Simple);


			$processor->process();

		}


		df_helper()->_1c()->log ('Импорт простых товаров завершён.');

		return $this;

	}





	/**
	 * @return Df_1C_Model_Cml2_Action_Catalog_Import
	 */
	private function importProductsSimplePartImages () {

		foreach ($this->getRegistry()->import()->collections()->getOffers() as $offer) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity_Offer $offer */
			df_assert ($offer instanceof Df_1C_Model_Cml2_Import_Data_Entity_Offer);


			if ($offer->isTypeSimple()) {

				/** @var Df_1C_Model_Cml2_Import_Processor_Product_Part_Images $processor */
				$processor =
					df_model (
						Df_1C_Model_Cml2_Import_Processor_Product_Part_Images::getNameInMagentoFormat()
						,
						array (
							Df_1C_Model_Cml2_Import_Processor_Product_Part_Images::PARAM__ENTITY => $offer
						)
					)
				;

				df_assert ($processor instanceof Df_1C_Model_Cml2_Import_Processor_Product_Part_Images);


				$processor->process();

			}

		}


		return $this;

	}





	/**
	 * @return Df_1C_Model_Cml2_Action_Catalog_Import
	 */
	private function importReferenceLists () {

		df_helper()->_1c()->log ('Импорт справочников начат.');


		foreach ($this->getRegistry()->import()->collections()->getAttributes() as $attribute) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity_Attribute $attribute */
			df_assert ($attribute instanceof Df_1C_Model_Cml2_Import_Data_Entity_Attribute);

			/**
			 * Обратываем только свойства, у которых тип значений — «Справочник».
			 */
			if ($attribute instanceof Df_1C_Model_Cml2_Import_Data_Entity_Attribute_ReferenceList) {

				/** @var Df_1C_Model_Cml2_Import_Processor_ReferenceList $processor */
				$processor =
					df_model (
						Df_1C_Model_Cml2_Import_Processor_ReferenceList::getNameInMagentoFormat()
						,
						array (
							Df_1C_Model_Cml2_Import_Processor_ReferenceList::PARAM__ENTITY => $attribute
						)
					)
				;

				df_assert ($processor instanceof Df_1C_Model_Cml2_Import_Processor_ReferenceList);


				$processor->process();

			}

		}



		df_helper()->_1c()->log ('Импорт справочников завершён.');

		return $this;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Action_Catalog_Import';
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
