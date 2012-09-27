<?php


/**
 * Cms Hierarchy Pages Node Model
 *
 * @category   Df
 * @package    Df_Cms
 */
class Df_Cms_Model_Hierarchy_Node extends Df_Core_Model_Abstract {

    /**
     * Appending passed page as child node for specified nodes and set it specified sort order.
     * Parent nodes specified as array (parentNodeId => sortOrder)
     *
     * @param Mage_Cms_Model_Page $page
     * @param array $nodes
     * @return Df_Cms_Model_Hierarchy_Node
     */
    public function appendPageToNodes($page, $nodes)
    {
		/** @var Df_Cms_Model_Mysql4_Hierarchy_Node_Collection $parentNodes  */
        $parentNodes = $this->getCollection()
            ->joinPageExistsNodeInfo($page)
            ->applyPageExistsOrNodeIdFilter(array_keys($nodes), $page);

        $pageData = array(
            'page_id' => $page->getId(),
            'identifier' => null,
            'label' => null
        );

        $removeFromNodes = array();

        foreach ($parentNodes as $node) {
            /* @var $node Df_Cms_Model_Hierarchy_Node */
            if (isset($nodes[$node->getId()])) {
                $sortOrder = $nodes[$node->getId()];
                if ($node->getPageExists()) {
                    continue;
                } else {
                    $node->addData($pageData)
                        ->setParentNodeId($node->getId())
                        ->unsetData($this->getIdFieldName())
                        ->setLevel($node->getLevel() + 1)
                        ->setSortOrder($sortOrder)
                        ->setRequestUrl($node->getRequestUrl() . '/' . $page->getIdentifier())
                        ->setXpath($node->getXpath() . '/')
                        ->save();
                }
            } else {
                $removeFromNodes[] = $node->getId();
            }
        }

        if (!empty($removeFromNodes)) {
            $this->_getResource()->removePageFromNodes($page->getId(), $removeFromNodes);
        }

        return $this;
    }






    /**
     * Check identifier
     *
     * If a CMS Page belongs to a tree (binded to a tree node), it should not be accessed standalone
     * only by URL that identifies it in a hierarchy.
     *
     * Return true if a page binded to a tree node
     *
     * @param string $identifier
     * @param int|Mage_Core_Model_Store $storeId
     * @return bool
     */
    public function checkIdentifier($identifier, $storeId = null)
    {
        $storeId = Mage::app()->getStore($storeId)->getId();
        return $this->_getResource()->checkIdentifier($identifier, $storeId);
    }






    /**
     * Collect and save tree
     *
     * @param array $data       modified nodes data array
     * @param array $remove     the removed node ids
     * @return Df_Cms_Model_Hierarchy_Node
     */
    public function collectTree($data, $remove)
    {
        if (!is_array($data)) {
            return $this;
        }

        $nodes = array();
        foreach ($data as $v) {
            $required = array(
                'node_id', 'parent_node_id', 'page_id', 'label', 'identifier', 'level', 'sort_order'
            );
            // validate required node data
            foreach ($required as $field) {
                if (!array_key_exists($field, $v)) {
                    Mage::throwException(
                        df_helper()->cms()->__('Invalid node data')
                    );
                }
            }
            $parentNodeId = empty($v['parent_node_id']) ? 0 : $v['parent_node_id'];
            $pageId = empty($v['page_id']) ? null : intval($v['page_id']);


            $_node = array(
                'node_id'            => strpos($v['node_id'], '_') === 0 ? null : intval($v['node_id']),
                'page_id'            => $pageId,
                'label'              => !$pageId ? $v['label'] : null,
                'identifier'         => !$pageId ? $v['identifier'] : null,
                'level'              => intval($v['level']),
                'sort_order'         => intval($v['sort_order']),
                'request_url'        => $v['identifier']
            );

            $nodes[$parentNodeId][$v['node_id']] = Mage::helper('df_cms/hierarchy')
                ->copyMetaData($v, $_node);
        }

        $this->_getResource()->beginTransaction();
        try {
            // remove deleted nodes
            if (!empty($remove)) {
                $this->_getResource()->dropNodes($remove);
            }
            // recursive node save
            $this->_collectTree($nodes, $this->getId(), $this->getRequestUrl(), $this->getId(), 0);

            $this->_getResource()->commit();
        } catch (Exception $e) {
            $this->_getResource()->rollBack();
            throw $e;
        }

        return $this;
    }





    /**
     * Retrieve Node or Page identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        $identifier = $this->_getData('identifier');
        if (is_null($identifier)) {
            $identifier = $this->_getData('page_identifier');
        }
        return $identifier;
    }





    /**
     * Retrieve Node label or Page title
     *
     * @return string
     */
    public function getLabel()
    {
        $label = $this->_getData('label');
        if (is_null($label)) {
            $label = $this->_getData('page_title');
        }
        return $label;
    }






	/**
	 * @return int
	 */
	public function getLevel () {

		/** @var int $result  */
		$result =
			intval (
				$this->cfg (
					self::PARAM__LEVEL
				)
			)
		;


		df_result_integer ($result);

		return $result;

	}




	/**
	 * Return nearest parent params for node pagination
	 *
	 * @return array|null
	 */
	public function getMetadataPagerParams()
	{
		$values = array(
			Df_Cms_Helper_Hierarchy::METADATA_VISIBILITY_YES,
			Df_Cms_Helper_Hierarchy::METADATA_VISIBILITY_NO);

		return $this->getResource()->getParentMetadataParams($this, 'pager_visibility', $values);
	}









	/**
	 * @return string|null
	 */
	public function getPageIdentifier () {

		/** @var string|null $result  */
		$result =
			$this->cfg (
				self::PARAM__PAGE_IDENTIFIER
			)
		;


		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}




	/**
	 * @return string
	 */
	public function getPageTitle () {

		/** @var string $result  */
		$result =
			$this->cfg (
				self::PARAM__PAGE_TITLE
			)
		;


		df_result_string ($result);

		return $result;

	}






	/**
	 * Retrieve parent node's children.
	 *
	 * @return array
	 */
	public function getParentNodeChildren()
	{
		$children = $this->_getResource()->getParentNodeChildren($this);
		$blankModel = Mage::getModel('df_cms/hierarchy_node');
		foreach ($children as $childId => $child) {
			$newModel = clone $blankModel;
			$children[$childId] = $newModel->setData($child);
		}
		return $children;
	}







	/**
	 * @return int
	 */
	public function getParentNodeId () {

		/** @var int $result  */
		$result =
			intval (
				$this->cfg (
					self::PARAM__PARENT_NODE_ID
				)
			)
		;


		df_result_integer ($result);

		return $result;

	}




	/**
	 * @return string|null
	 */
	public function getRequestUrl () {

		/** @var string|null $result  */
		$result =
			$this->cfg (
				self::PARAM__REQUEST_URL
			)
		;

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}






	/**
	 * @return int
	 */
	public function getSortOrder () {

		/** @var int $result  */
		$result =
			intval (
				$this->cfg (
					self::PARAM__SORT_ORDER
				)
			)
		;


		df_result_integer ($result);

		return $result;

	}





	/**
	 * Get tree meta data flags for current node's tree.
	 *
	 * @return array|bool
	 */
	public function getTreeMetaData()
	{
		if (is_null($this->_treeMetaData)) {
			$this->_treeMetaData = $this->_getResource()->getTreeMetaData($this);
		}

		return $this->_treeMetaData;
	}



	/**
	 * @var array|bool
	 */
	private $_treeMetaData;





	/**
	 * Retrieve Tree Slice like two level array of node models.
	 *
	 * @param int $up, if equals zero - no limitation
	 * @param int $down, if equals zero - no limitation
	 * @return array
	 */
	public function getTreeSlice($up = 0, $down = 0)
	{
		$data = $this->_getResource()
			->setTreeMaxDepth($this->_getData('tree_max_depth'))
			->setTreeIsBrief($this->_getData('tree_is_brief'))
			->getTreeSlice($this, $up, $down);

		$blankModel = Mage::getModel('df_cms/hierarchy_node');
		foreach ($data as $parentId => $children) {
			foreach ($children as $childId => $child) {
				$newModel = clone $blankModel;
				$data[$parentId][$childId] = $newModel->setData($child);
			}
		}
		return $data;
	}








	/**
	 * @return string|null
	 */
	public function getXPath() {

		/** @var string|null $result  */
		$result =
			$this->cfg (
				self::PARAM__XPATH
			)
		;


		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}






    /**
     * Retrieve Page URL
     *
     * @param mixed $store
     * @return string
     */
    public function getUrl($store = null)
    {
        return Mage::app()->getStore($store)->getUrl('', array(
            '_direct' => trim($this->getRequestUrl())
        ));
    }





	/**
	 * @return bool
	 */
	public function isExcludedFromMenu () {

		/** @var bool $result  */
		$result =
				1
			===
				intval (
					$this->cfg (self::PARAM__MENU_EXCLUDED)
				)
		;


		df_result_boolean ($result);

		return $result;

	}





	/**
	 * @param int $nodeId
	 * @return bool
	 */
	public function isBelongTo ($nodeId) {

		df_param_integer ($nodeId, 0);

		if (!isset ($this->_belongTo [$nodeId])) {

			/** @var bool $result  */
			$result =
				in_array (
					$nodeId
					,
					explode (
						'/'
						,
						$this->getXPath()
					)
				)
			;


			df_assert_boolean ($result);

			$this->_belongTo [$nodeId] = $result;

		}


		df_result_boolean ($this->_belongTo [$nodeId]);

		return $this->_belongTo [$nodeId];

	}


	/**
	* @var bool[]
	*/
	private $_belongTo = array ();









	/**
	 * Is Node used original Page Identifier
	 *
	 * @return bool
	 */
	public function isUseDefaultIdentifier()
	{
		return is_null($this->_getData('identifier'));
	}





	/**
	 * Is Node used original Page Label
	 *
	 * @return bool
	 */
	public function isUseDefaultLabel()
	{
		return is_null($this->_getData('label'));
	}







    /**
     * Load node by Request Url
     *
     * @param string $url
     * @return Df_Cms_Model_Hierarchy_Node
     */
    public function loadByRequestUrl($url)
    {
        $this->_getResource()->loadByRequestUrl($this, $url);
        $this->_afterLoad();
        $this->setOrigData();
        return $this;
    }





    /**
     * Retrieve first child node
     *
     * @param int $parentNodeId
     * @return Df_Cms_Model_Hierarchy_Node
     */
    public function loadFirstChildByParent($parentNodeId)
    {
        $this->_getResource()->loadFirstChildByParent($this, $parentNodeId);
        $this->_afterLoad();
        $this->setOrigData();
        return $this;
    }





    /**
     * Load page data for model if defined page id end undefined page data
     *
     * @return Df_Cms_Model_Hierarchy_Node
     */
    public function loadPageData()
    {
        if ($this->getPageId() && !$this->getPageIdentifier()) {
            $this->_getResource()->loadPageData($this);
        }

        return $this;
    }





    /**
     * Flag to indicate whether append active pages only or not
     *
     * @param bool $flag
     * @return Df_Cms_Model_Hierarchy_Node
     */
    public function setCollectActivePagesOnly($flag)
    {
        $flag = (bool)$flag;
        $this->setData('collect_active_pages_only', $flag);
        $this->_getResource()->setAppendActivePagesOnly($flag);
        return $this;
    }





    /**
     * Flag to indicate whether append included pages (menu_excluded=0) only or not
     *
     * @param bool $flag
     * @return Df_Cms_Model_Hierarchy_Node
     */
    public function setCollectIncludedPagesOnly($flag)
    {
        $flag = (bool)$flag;
        $this->setData('collect_included_pages_only', $flag);
        $this->_getResource()->setAppendIncludedPagesOnly($flag);
        return $this;
    }





    /**
     * Setter for tree_max_depth data
     * Maximum tree depth for tree slice, if equals zero - no limitations
     *
     * @param int $depth
     * @return Df_Cms_Model_Hierarchy_Node
     */
    public function setTreeMaxDepth($depth)
    {
        $this->setData('tree_max_depth', (int)$depth);
        return $this;
    }

    /**
     * Setter for tree_is_brief data
     * Tree Detalization, i.e. brief or detailed
     *
     * @param bool $brief
     * @return Df_Cms_Model_Hierarchy_Node
     */
    public function setTreeIsBrief($brief)
    {
        $this->setData('tree_is_brief', (bool)$brief);
        return $this;
    }





    /**
     * Update rewrite for page (if identifier changed)
     *
     * @param Mage_Cms_Model_Page $page
     * @return Df_Cms_Model_Hierarchy_Node
     */
    public function updateRewriteUrls(Mage_Cms_Model_Page $page)
    {
        $xpaths = $this->_getResource()->getTreeXpathsByPage($page->getId());
        foreach ($xpaths as $xpath) {
            $this->_getResource()->updateRequestUrlsForTreeByXpath($xpath);
        }
        return $this;
    }







    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('df_cms/hierarchy_node');
    }






    /**
     * Process additional data after save.
     *
	 * @override
     * @return Df_Cms_Model_Hierarchy_Node
     */
    protected function _afterSave()
    {
        parent::_afterSave();
        // we save to metadata table not only metadata :(
        //if (Mage::helper('df_cms/hierarchy')->isMetadataEnabled()) {
            $this->_getResource()->saveMetaData($this);
        //}

        return $this;
    }




    /**
     * Recursive save nodes
     *
     * @param array $nodes
     * @param int $parentNodeId
     * @param string $path
     * @param int $level
     * @return Df_Cms_Model_Hierarchy_Node
     */
    protected function _collectTree(array $nodes, $parentNodeId, $path = '', $xpath = '', $level = 0)
    {
        if (!isset($nodes[$level])) {
            return $this;
        }
        foreach ($nodes[$level] as $k => $v) {
            $v['parent_node_id'] = $parentNodeId;
            if ($path != '') {
                $v['request_url'] = $path . '/' . $v['request_url'];
            } else {
                $v['request_url'] = $v['request_url'];
            }

            if ($xpath != '') {
                $v['xpath'] = $xpath . '/';
            } else {
                $v['xpath'] = '';
            }

            $object = clone $this;
            $object->setData($v)->save();

            if (isset($nodes[$k])) {
                $this->_collectTree($nodes, $object->getId(), $object->getRequestUrl(), $object->getXpath(), $k);
            }
        }
        return $this;
    }




    /**
     * Retrieve Resource instance wrapper
     *
     * @return Df_Cms_Model_Mysql4_Hierarchy_Node
     */
    protected function _getResource()
    {
        return parent::_getResource();
    }





	/**
	 * @var array
	 */
	 protected $_metaNodes = array();


	/**
	 * Meta node's types
	 */
	 const META_NODE_TYPE_CHAPTER = 'chapter';
	 const META_NODE_TYPE_SECTION = 'section';
	 const META_NODE_TYPE_FIRST = 'start';
	 const META_NODE_TYPE_NEXT = 'next';
	 const META_NODE_TYPE_PREVIOUS = 'prev';


	const PARAM__LEVEL = 'level';
	const PARAM__MENU_EXCLUDED = 'menu_excluded';
	const PARAM__PAGE_IDENTIFIER = 'page_identifier';
	const PARAM__PAGE_TITLE = 'page_title';
	const PARAM__PARENT_NODE_ID = 'parent_node_id';
	const PARAM__REQUEST_URL = 'request_url';
	const PARAM__SORT_ORDER = 'sort_order';
	const PARAM__XPATH = 'xpath';





	/**
	 * @return array
	 */
	public static function getMetadataKeys () {

		/** @var array $result  */
		$result =
			array_merge (
				array (
					'pager_visibility',
					'pager_frame',
					'pager_jump',
					'menu_brief',
					'menu_excluded',
					'menu_levels_down',
					'menu_ordered',
					'menu_list_type'
				)
				,
				self::getMetadataKeysAdditional()
			)
		;

		df_result_array ($result);

		return $result;

	}



	/**
	 * @return array
	 */
	public static function getMetadataKeysAdditional () {

		/** @var array $result  */
		$result =
			array_merge (
				self::getMetadataKeysForPageType (Df_Cms_Model_ContentsMenu_PageType::FRONT)
				,
				self::getMetadataKeysForPageType (Df_Cms_Model_ContentsMenu_PageType::CATALOG_PRODUCT_LIST)
				,
				self::getMetadataKeysForPageType (Df_Cms_Model_ContentsMenu_PageType::CATALOG_PRODUCT_VIEW)
				,
				self::getMetadataKeysForPageType (Df_Cms_Model_ContentsMenu_PageType::ACCOUNT)
				,
				self::getMetadataKeysForPageType (Df_Cms_Model_ContentsMenu_PageType::CMS_OWN)
				,
				self::getMetadataKeysForPageType (Df_Cms_Model_ContentsMenu_PageType::CMS_FOREIGN)
				,
				self::getMetadataKeysForPageType (Df_Cms_Model_ContentsMenu_PageType::OTHER)
			)
		;


		df_result_array ($result);

		return $result;

	}



	/**
	 * @param string $pageType
	 * @return array
	 */
	public static function getMetadataKeysForPageType ($pageType) {

		/** @var array $result  */
		$result =
			array (
				self::getMetadataKeyForPageType ($pageType, 'enabled')
				,
				self::getMetadataKeyForPageType ($pageType, 'position')
				,
				self::getMetadataKeyForPageType ($pageType, 'vertical_ordering')
			)
		;


		df_result_array ($result);

		return $result;

	}




	/**
	 * @param string $pageType
	 * @param string $keyName
	 * @return string
	 */
	public static function getMetadataKeyForPageType ($pageType, $keyName) {

		/** @var array $result  */
		$result =
			implode (
				'__'
				,
				array (
					'contents_menu'
					,
					$pageType
					,
					$keyName
				)
			)
		;


		df_result_string ($result);

		return $result;

	}





	const PARAM__ADDITIONAL_SETTINGS = 'additional_settings';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Model_Hierarchy_Node';
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
