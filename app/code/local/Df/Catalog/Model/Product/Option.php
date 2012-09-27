<?php


class Df_Catalog_Model_Product_Option extends Mage_Catalog_Model_Product_Option {

	/**
	 * @return Df_Catalog_Model_Product_Option
	 */
	public function deleteWithDependencies () {
		$this->getValueInstance()->deleteValue($this->getId());
		$this->deletePrices($this->getId());
		$this->deleteTitles($this->getId());
		$this->delete();
		return $this;
	}


}