<?php


class Df_Seo_Model_Product_Url_Key_Processor extends Mage_Dataflow_Model_Convert_Container_Abstract
{





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Model_Product_Url_Key_Processor';
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











	public function process () {

		/** @var array $messagesToReport  */
		$messagesToReport = array ();

		foreach ($this->getItems () as $item) {
			/** @var Mage_Catalog_Model_Product $item */
			$item
				->setUrlKey (
					df_helper()->catalog()->product()->url()->extendedFormat (
						$item->getName ()
					)
				)
				->setIsMassupdate(true)
				->setExcludeUrlRewrite (true)
				->save ()
			;

			$messageToReport =
				sprintf (
					"«%s»: «%s»"
					,
					$item->getName ()
					,
					$item->getUrlKey ()
				)
			;

			$messagesToReport []= $messageToReport;

			$this
				->addException(
					$messageToReport
				)
			;
		}


		df_log (implode ("\n\n", $messagesToReport));

		$this
			->addException(
				df_helper()->seo()->__("Все товары обработаны.")
			)
		;
		df_mage()->catalog()->urlSingleton()->refreshRewrites();


		/** @var Mage_Catalog_Model_Indexer_Url $indexer  */
		$indexer = df_model ('catalog/indexer_url');

		df_assert ($indexer instanceof Mage_Catalog_Model_Indexer_Url);

		$indexer->reindexAll();
	}



	/**
	 * @return array
	 */
	private function getItems () {
        return
			Mage::getResourceModel('catalog/product_collection')
				->addAttributeToSelect ("url_key")
				->addAttributeToSelect ("name")
				//->setPageSize (10)
				//->setCurPage (1)
				->load ()
		;
	}
}