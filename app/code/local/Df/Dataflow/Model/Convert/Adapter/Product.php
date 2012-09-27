<?php


/**
 * Здесь я буду развивать свою собственную новую технология импорта
 */


class Df_Dataflow_Model_Convert_Adapter_Product
	extends Df_Dataflow_Model_Convert_Adapter_Abstract {



    /**
     * Process after import data
     * Init indexing process after catalog product import
     *
     */
    public function finish() {
        /**
         * Back compatibility event
         */
        Mage::dispatchEvent('catalog_product_import_after', array());

        $entity = new Varien_Object();

        df_mage()->index()->indexer()
			->processEntityAction(
            	$entity
				,
				Mage_Catalog_Model_Convert_Adapter_Product::ENTITY
				,
				Mage_Index_Model_Event::TYPE_SAVE
        	)
		;
    }






	/**
	 * @param array $rowAsArray
	 * @param int $rowOrdering
	 * @return Df_Dataflow_Model_Convert_Adapter_Product
	 */
    protected function saveRowInternal (array $rowAsArray, $rowOrdering) {

        df_param_array ($rowAsArray, 0);
		df_param_integer ($rowOrdering, 1);


		/** @var Df_Dataflow_Model_Import_Product_Row $row  */
		$row =
			df_model (
				Df_Dataflow_Model_Import_Product_Row::getNameInMagentoFormat()
				,
				array (
					Df_Dataflow_Model_Import_Product_Row::PARAM_ROW_AS_ARRAY => $rowAsArray
					,
					Df_Dataflow_Model_Import_Product_Row::PARAM_ORDERING => $rowOrdering
				)
			)
		;

		df_assert ($row instanceof Df_Dataflow_Model_Import_Product_Row);



		/**
		 * Поручаем задачу импорта конкретного пакета отдельному объекту.
		 *
		 * @var Df_Dataflow_Model_Importer_Product $importer
		 */
		$importer =
			df_model (
				Df_Dataflow_Model_Importer_Product::getNameInMagentoFormat()
				,
				array (
					Df_Dataflow_Model_Importer_Product::PARAM_ROW => $row
				)
			)
		;


		$importer->import ();


	    return $this;
    }


}