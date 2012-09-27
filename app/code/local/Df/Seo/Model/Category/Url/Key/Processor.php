<?php


class Df_Seo_Model_Category_Url_Key_Processor extends Mage_Dataflow_Model_Convert_Container_Abstract
{

	public function process () {

		foreach ($this->getCategories () as $category) {
			/** @var Mage_Catalog_Model_Category $category */
			$category
				->setUrlKey (
					df_helper()->catalog()->product()->url()->extendedFormat (
						$category->getName ()
					)
				)
				->setExcludeUrlRewrite (true)
				->save ()
			;
			$this
				->addException(
					sprintf (
						"«%s»: «%s»"
						,
						$category->getName ()
						,
						$category->getUrlKey ()
					)
				)
			;
		}
		$this
			->addException(
				df_helper()->seo()->__("Все товарные разделы обработаны.")
			)
		;
	}



	/**
	 * @return array
	 */
	private function getCategories () {
        return
			Mage::getResourceModel('catalog/category_collection')
				->addAttributeToSelect ("url_key")
				->addAttributeToSelect ("name")
				->load ()
		;
	}
}