<?php

class Df_PageCache_Block_Catalog_Product extends Mage_Core_Block_Template
{
    /**
     * Get currently viewed product id
     * @return int
     */
    public function getProductId()
    {
        $product = Mage::registry('current_product');
        if ($product) {
            return $product->getId();
        }
        return false;
    }
}
