<?php


class Df_Sitemap_Model_Resource_Catalog_Product extends Mage_Sitemap_Model_Mysql4_Catalog_Product {


	/**
	 * @param  int $storeId
	 * @return array
	 */
    public function getCollection ($storeId)
    {
	    return
				!(df_enabled (Df_Core_Feature::SEO, $storeId) && df_cfg ()->seo()->urls()->getFixAddCategoryToProductUrl())
			?
				parent::getCollection ($storeId)
			:
				$this->getCollectionDf ($storeId)						
		;
    }


	/**
	 * @param  int $storeId
	 * @return array
	 */
    public function getCollectionDf ($storeId)
    {
        $products = array();

        $store = Mage::app()->getStore($storeId);
        /* @var $store Mage_Core_Model_Store */

        if (!$store) {
            return false;
        }

        $urCondions = array(
            'e.entity_id=ur.product_id',

				Mage::getStoreConfig(
					Mage_Catalog_Helper_Product::XML_PATH_PRODUCT_URL_USE_CATEGORY
					,
					$storeId
				)
	        ?
				'ur.category_id IS NOT NULL'
		    :
				'ur.category_id IS NULL'
			,


            $this->_getWriteAdapter()->quoteInto('ur.store_id=?', $store->getId()),
            $this->_getWriteAdapter()->quoteInto('ur.is_system=?', 1),
        );
        $this->_select = $this->_getWriteAdapter()->select()
            ->from(array('e' => $this->getMainTable()), array($this->getIdFieldName()))
            ->join(
                array('w' => $this->getTable('catalog/product_website')),
                'e.entity_id=w.product_id',
                array()
            )
            ->where('w.website_id=?', $store->getWebsiteId())
            ->joinLeft(
                array('ur' => $this->getTable('core/url_rewrite')),
                join(' AND ', $urCondions),
                array('url' => 'request_path')
            )
            ;

        $this->_addFilter($storeId, 'visibility', Mage::getSingleton('catalog/product_visibility')->getVisibleInSiteIds(), 'in');
        $this->_addFilter($storeId, 'status', Mage::getSingleton('catalog/product_status')->getVisibleStatusIds(), 'in');

        $query = $this->_getWriteAdapter()->query($this->_select);
        while ($row = $query->fetch()) {
            $product = $this->_prepareProduct($row);
            $products[$product->getId()] = $product;
        }

        return $products;
    }


	
}