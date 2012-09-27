<?php



/**
 * Cms Hierarchy Pages Node Resource Model
 *
 * @category   Df
 * @package    Df_Cms
 */
class Df_Cms_Model_Mysql4_Hierarchy_Node extends Mage_Core_Model_Mysql4_Abstract
{



    /**
     * Perform actions after object load
     *
	 * @override
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
		parent::_afterLoad ($object);

		/** @var string|null $additionalSettingsEncoded  */
		$additionalSettingsEncoded =
			$object->getData (Df_Cms_Model_Hierarchy_Node::PARAM__ADDITIONAL_SETTINGS)
		;

		if (!is_null ($additionalSettingsEncoded)) {

			/** @var array|false $additionalSettings */
			$additionalSettings = json_decode ($additionalSettingsEncoded, true);

			df_assert_array ($additionalSettings);

			$object
				->addData (
					$additionalSettings
				)
			;

		}


        return $this;
    }




    /**
     * Prepare xpath after object save
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Df_Cms_Model_Mysql4_Hierarchy_Node
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        if ($object->dataHasChangedFor($this->getIdFieldName())) {
            // update xpath
            $xpath = $object->getXpath() . $object->getId();
            $bind = array('xpath' => $xpath);
            $where = $this->_getWriteAdapter()->quoteInto($this->getIdFieldName() . '=?', $object->getId());
            $this->_getWriteAdapter()->update($this->getMainTable(), $bind, $where);
            $object->setXpath($xpath);
        }

        return $this;
    }




    /**
     * Perform actions before object save
     *
	 * @override
     * @param Mage_Core_Model_Abstract $object
     * @return Mage_Core_Model_Resource_Db_Abstract
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
		parent::_beforeSave ($object);

		/** @var array $additionalSettings */
		$additionalSettings =
			array_intersect_key (
				$object->getData ()
				,
				array_flip (
					Df_Cms_Model_Hierarchy_Node::getMetadataKeysAdditional()
				)
			)
		;

		$object
			->setData (
				Df_Cms_Model_Hierarchy_Node::PARAM__ADDITIONAL_SETTINGS
				,
				json_encode($additionalSettings)
			)
		;

        return $this;
    }





    /**
     * Prepare load select but without where part.
     * So all extra joins to secondary tables will be present.
     *
     * @return Zend_Db_Select
     */
    public function _getLoadSelectWithoutWhere()
    {
        $select = $this->_getLoadSelect(null, null, null)->reset(Zend_Db_Select::WHERE);
        $this->_applyParamFilters($select);
        return $select;
    }




    /**
     * Return object nested childs and its neighbours in Tree Slice
     *
     * @param Df_Cms_Model_Hierarchy_Node $object
     * @param int $down Number of Child Node Levels to Include, if equals zero - no limitation
     * @return array
     */
    protected function _getSliceChildren($object, $down = 0)
    {
        $select = $this->_getLoadSelectWithoutWhere();

        $xpath = $object->getXpath() . '/%';
        $select->where('xpath LIKE ?', $xpath);

        if (max($down, $this->_treeMaxDepth) > 0) {
            $maxLevel = $this->_treeMaxDepth > 0
                      ? min($this->_treeMaxDepth, $object->getLevel() + $down)
                      : $object->getLevel() + $down;
            $select->where('level <= ?', $maxLevel);
        }
        $select->order(array('level', $this->getMainTable().'.sort_order'));
        return $select->query()->fetchAll();
    }




    /**
     * Recursive update Request URL for node and all it's children
     *
     * @param array $nodes
     * @param int $parentNodeId
     * @param string $path
     * @return Df_Cms_Model_Mysql4_Hierarchy_Node
     */
    protected function _updateNodeRequestUrls(array $nodes, $parentNodeId = 0, $path = null)
    {
        foreach ($nodes[$parentNodeId] as $nodeRow) {
            $identifier = $nodeRow['page_id'] ? $nodeRow['page_identifier'] : $nodeRow['identifier'];

            if ($path) {
                $requestUrl = $path . '/' . $identifier;
            } else {
                $route = explode('/', $nodeRow['request_url']);
                array_pop($route);
                $route[] = $identifier;
                $requestUrl = implode('/', $route);
            }

            if ($nodeRow['request_url'] != $requestUrl) {
                $this->_getWriteAdapter()->update($this->getMainTable(), array(
                    'request_url' => $requestUrl
                ), $this->_getWriteAdapter()->quoteInto($this->getIdFieldName().'=?', $nodeRow[$this->getIdFieldName()]));
            }

            if (isset($nodes[$nodeRow[$this->getIdFieldName()]])) {
                $this->_updateNodeRequestUrls($nodes, $nodeRow[$this->getIdFieldName()], $requestUrl);
            }
        }

        return $this;
    }




	/**
	 * Check identifier
	 *
	 * If a CMS Page belongs to a tree (binded to a tree node), it should not be accessed standalone
	 * only by URL that identifies it in a hierarchy.
	 *
	 * @param string $identifier
	 * @param int $storeId
	 * @return bool
	 */
	public function checkIdentifier($identifier, $storeId)
	{
		$adapter = $this->_getReadAdapter();
		$select  = $adapter->select()
			->from(array('main_table' => $this->getTable('cms/page')), array('page_id', 'website_root'))
			->join(
				array('cps' => $this->getTable('cms/page_store')),
				'main_table.page_id = `cps`.page_id',
				array())
			->where('main_table.identifier = ?', $identifier)
			->where('main_table.is_active=1 AND `cps`.store_id in (0, ?) ', $storeId)
			->order('store_id DESC')
			->limit(1);

		$page = $adapter->fetchRow($select);

		if (!$page || $page['website_root'] == 1) {
			return false;
		}

		return true;
	}




	/**
	 * @param int $pageId
	 * @return Df_Cms_Model_Mysql4_Hierarchy_Node
	 */
	public function deleteNodesByPageId ($pageId)
	{
		$write = $this->_getWriteAdapter();
		$whereClause =
			$write->quoteInto('? = page_id', $pageId)
		;
		$write->delete($this->getMainTable(), $whereClause);

		return $this;
	}




	/**
	 * @param int $pageId
	 * @return Df_Cms_Model_Mysql4_Hierarchy_Node
	 */
	public function deleteRootNodesByPageId ($pageId)
	{
		$write = $this->_getWriteAdapter();
		$whereClause = $write->quoteInto('(? = page_id) AND (parent_node_id IS NULL)', $pageId);
		$write->delete($this->getMainTable(), $whereClause);

		return $this;
	}






	/**
	 * Remove nodes defined by id.
	 * Which will also remove their child nodes by foreign key.
	 *
	 * @param int|array $nodeIds
	 * @return Df_Cms_Model_Mysql4_Hierarchy_Node
	 */
	public function dropNodes($nodeIds)
	{
		$write = $this->_getWriteAdapter();
		$whereClause = $write->quoteInto('node_id IN (?)', $nodeIds);
		$write->delete($this->getMainTable(), $whereClause);

		return $this;
	}




	/**
	 * Retrieve tree meta data flags from secondary table.
	 * Filtering by root node of passed node.
	 *
	 * @param Df_Cms_Model_Hierarchy_Node $object
	 * @return array
	 */
	public function getTreeMetaData(Df_Cms_Model_Hierarchy_Node $object) {
		$read = $this->_getReadAdapter();
		$select = $read->select();
		$xpath = explode('/', $object->getXpath());
		$select->from($this->_metadataTable)
			->where('node_id = ?', $xpath[0]);

		return $read->fetchRow($select);
	}





	/**
	 * Retrieve brief/detailed Tree Slice for object
	 * 2 level array
	 *
	 * @param Df_Cms_Model_Hierarchy_Node $object
	 * @param int $up, if equals zero - no limitation
	 * @param int $down, if equals zero - no limitation
	 * @return array
	 */
	public function getTreeSlice($object, $up = 0, $down = 0)
	{
		$tree       = array();
		$parentId   = $object->getParentNodeId();

		if ($this->_treeMaxDepth > 0 && $object->getLevel() > $this->_treeMaxDepth) {
			return $tree;
		}

		$xpath = explode('/', $object->getXpath());
		if (!$this->_treeIsBrief) {
			array_pop($xpath); //remove self node
		}
		$parentIds = array();
		$useUp = $up > 0;
		while (count($xpath) > 0) {
			if ($useUp && $up == 0) {
				break;
			}
			$parentIds[] = array_pop($xpath);
			if ($useUp) {
				$up--;
			}
		}

		/**
		 * Collect childs
		 */
		$children = array();
		if ($this->_treeMaxDepth > 0 && $this->_treeMaxDepth > $object->getLevel() || $this->_treeMaxDepth == 0) {
			$children = $this->_getSliceChildren($object, $down);
		}

		/**
		 * Collect parent and neighbours
		 */
		if ($parentIds) {
			$parentId = $parentIds[count($parentIds) -1];
			if ($this->_treeIsBrief) {
				$where = $this->_getReadAdapter()->quoteInto($this->getMainTable().'.node_id IN (?)', $parentIds);
				// Collect neighbours if there are no children
				if (count($children) == 0) {
					$where.= $this->_getReadAdapter()->quoteInto(' OR parent_node_id=?', $object->getParentNodeId());
				}
			} else {
				$where = $this->_getReadAdapter()->quoteInto('parent_node_id IN (?) OR parent_node_id IS NULL', $parentIds);
			}
		} else {
			$where = 'parent_node_id IS NULL';
		}

		$select = $this->_getLoadSelectWithoutWhere()
			->where($where)
			->order(array('level', $this->getMainTable().'.sort_order'));
		$nodes = $select->query()->fetchAll();
		$tree = $this->_prepareRelatedStructure($nodes, 0, $tree);


		// add children to tree
		if (count($children) > 0) {
			$tree = $this->_prepareRelatedStructure($children, 0, $tree);
		}

		return $tree;
	}





    /**
     * Retrieve xpaths array which contains defined page
     *
     * @param int $pageId
     * @return array
     */
    public function getTreeXpathsByPage($pageId)
    {
        $treeXpaths = array();
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'xpath')
            ->where('page_id=?', $pageId);

        $rowset = $this->_getReadAdapter()->fetchAll($select);
        $treeXpaths = array();
        foreach ($rowset as $row) {
            $treeXpaths[] = $row['xpath'];
        }
        return $treeXpaths;
    }




	/**
	 * Load node by Request Path
	 *
	 * @param Df_Cms_Model_Hierarchy_Node $object
	 * @param string $url
	 * @return Df_Cms_Model_Mysql4_Hierarchy_Node
	 */
	public function loadByRequestUrl($object, $url)
	{
		$read = $this->_getReadAdapter();
		if ($read && !is_null($url)) {
			$select = $this->_getLoadSelect('request_url', $url, $object);
			$data = $read->fetchRow($select);

			if ($data) {
				$object->setData($data);
			}
		}

		$this->_afterLoad($object);
		return $this;
	}




	/**
	 * Load First node by parent node id
	 *
	 * @param Df_Cms_Model_Hierarchy_Node $object
	 * @param int $parentNodeId
	 * @return Df_Cms_Model_Mysql4_Hierarchy_Node
	 */
	public function loadFirstChildByParent($object, $parentNodeId)
	{
		$read = $this->_getReadAdapter();
		if ($read && !is_null($parentNodeId)) {
			$select = $this->_getLoadSelect('parent_node_id', $parentNodeId, $object)
				->order(array($this->getMainTable().'.sort_order'))
				->limit(1);
			$data = $read->fetchRow($select);

			if ($data) {
				$object->setData($data);
			}
		}

		$this->_afterLoad($object);
		return $this;
	}




	/**
	 * Remove children by root node.
	 *
	 * @param Df_Cms_Model_Hierarchy_Node $object
	 * @return Df_Cms_Model_Mysql4_Hierarchy_Node
	 */
	public function removeTreeChilds($object)
	{
		$where = $this->_getWriteAdapter()->quoteInto('parent_node_id=?', $object->getId());
		$this->_getWriteAdapter()->delete($this->getMainTable(), $where);
		return $this;
	}





	/**
	 * Saving meta if such available for node (in case node is root node of three)
	 *
	 * @param Mage_Core_Model_Abstract $object
	 * @return Df_Cms_Model_Mysql4_Hierarchy_Node
	 */
	public function saveMetaData(Mage_Core_Model_Abstract $object)
	{
		// we save to metadata table not only metadata :(
		//if ($object->getParentNodeId()) {
		//    return $this;
		//}
		$preparedData = $this->_prepareDataForTable($object, $this->_metadataTable);
		$this->_getWriteAdapter()->insertOnDuplicate(
			$this->_metadataTable, $preparedData, array_keys($preparedData));
		return $this;
	}





    /**
     * Flag to indicate whether append active pages only or not
     *
     * @param bool $flag
     * @return Df_Cms_Model_Mysql4_Hierarchy_Node
     */
    public function setAppendActivePagesOnly($flag)
    {
        $this->_appendActivePagesOnly = (bool)$flag;
        return $this;
    }




    /**
     * Flag to indicate whether append included pages (menu_excluded=0) only or not
     *
     * @param bool $flag
     * @return Df_Cms_Model_Hierarchy_Node
     */
    public function setAppendIncludedPagesOnly($flag)
    {
        $this->_appendIncludedPagesOnly = (bool)$flag;
        return $this;
    }




	/**
	 * Setter for $_treeIsBrief
	 *
	 * @param bool $brief
	 * @return Df_Cms_Model_Mysql4_Hierarchy_Node
	 */
	public function setTreeIsBrief($brief)
	{
		$this->_treeIsBrief = (bool)$brief;
		return $this;
	}




    /**
     * Setter for $_treeMaxDepth
     *
     * @param int $depth
     * @return Df_Cms_Model_Mysql4_Hierarchy_Node
     */
    public function setTreeMaxDepth($depth)
    {
        $this->_treeMaxDepth = (int)$depth;
        return $this;
    }





	/**
	 * Rebuild URL rewrites for a tree with specified path.
	 *
	 * @param string $xpath
	 * @return Df_Cms_Model_Mysql4_Hierarchy_Node
	 */
	public function updateRequestUrlsForTreeByXpath($xpath)
	{
		$select = $this->_getReadAdapter()->select()
			->from(
				array('node_table' => $this->getMainTable()),
				array($this->getIdFieldName(), 'parent_node_id', 'page_id', 'identifier', 'request_url'))
			->joinLeft(
				array('page_table' => $this->getTable('cms/page')),
				'node_table.page_id=page_table.page_id',
				array(
					'page_identifier' => 'identifier',
				))
			->where('xpath LIKE ?', $xpath. '/%')
			->orWhere('xpath = ?', $xpath)
			->group('node_table.node_id')
			->order(array('level', 'node_table.sort_order'));

		$nodes      = array();
		$rowSet     = $select->query()->fetchAll();
		foreach ($rowSet as $row) {
			$nodes[intval($row['parent_node_id'])][$row[$this->getIdFieldName()]] = $row;
		}

		if (!$nodes) {
			return $this;
		}

		$keys = array_keys($nodes);
		$parentNodeId = array_shift($keys);
		$this->_updateNodeRequestUrls($nodes, $parentNodeId, null);

		return $this;
	}




	/**
	 * Updating nodes sort_order with new value.
	 *
	 * @param int $nodeId
	 * @param int $sortOrder
	 * @return Df_Cms_Model_Mysql4_Hierarchy_Node
	 */
	public function updateSortOrder($nodeId, $sortOrder)
	{
		$this->_getWriteAdapter()->update($this->getMainTable(),
				array('sort_order' => $sortOrder),
				array($this->getIdFieldName() . ' = ? ' => $nodeId));

		return $this;
	}





    /**
     * Return nearest parent params for pagination/menu
     *
     * @param Df_Cms_Model_Hierarchy_Node $object
     * @param string $fieldName Parent metadata field to use in filter
     * @param string $values Values for filter
     * @return array|null
     */
    public function getParentMetadataParams($object, $fieldName, $values)
    {
        $values = is_array($values) ? $values : array($values);

        $parentIds = preg_split('/\/{1}/', $object->getXpath(), 0, PREG_SPLIT_NO_EMPTY);
        array_pop($parentIds); //remove self node
        $select = $this->_getLoadSelectWithoutWhere()
            ->where($this->getMainTable().'.node_id IN (?)', $parentIds)
            ->where('metadata_table.'.$fieldName.' IN (?)', $values)
            ->order(array($this->getMainTable().'.level DESC'))
            ->limit(1);
        $params = $this->_getReadAdapter()->fetchRow($select);

        if (is_array($params) && count($params) > 0) {
            return $params;
        }
        return null;
    }



	/**
	 * Retrieve Parent node children
	 *
	 * @param Df_Cms_Model_Hierarchy_Node $object
	 * @return array
	 */
	public function getParentNodeChildren($object)
	{
		if ($object->getParentNodeId() === null) {
			$where = 'parent_node_id IS NULL';
		} else {
			$where = $this->_getReadAdapter()->quoteInto('parent_node_id=?', $object->getParentNodeId());
		}
		$select = $this->_getLoadSelectWithoutWhere()
			->where($where)
			->order($this->getMainTable().'.sort_order');
		$nodes = $select->query()->fetchAll();

		return $nodes;
	}




    /**
     * Load page data for model if defined page id
     *
     * @param Df_Cms_Model_Hierarchy_Node $object
     * @return Df_Cms_Model_Mysql4_Hierarchy_Node
     */
    public function loadPageData($object)
    {
        $pageId = $object->getPageId();
        if (!empty($pageId)) {
            $columns = array(
                'page_title'        => 'title',
                'page_identifier'   => 'identifier',
                'page_is_active'    => 'is_active'
            );
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('cms/page'), $columns)
                ->where('page_id=?', $pageId)
                ->limit(1);
            $row = $this->_getReadAdapter()->fetchRow($select);
            if ($row) {
                $object->addData($row);
            }
        }
        return $this;
    }




    /**
     * Remove node which are representing specified page from defined nodes.
     * Which will also remove child nodes by foreign key.
     *
     * @param int $pageId
     * @param int|array $nodes
     * @return Df_Cms_Model_Mysql4_Hierarchy_Node
     */
    public function removePageFromNodes($pageId, $nodes)
    {
        $write = $this->_getWriteAdapter();
        $whereClause = $write->quoteInto('page_id = ? AND ', $pageId);
        $whereClause .= $write->quoteInto('parent_node_id IN (?)', $nodes);
        $write->delete($this->getMainTable(), $whereClause);

        return $this;
    }






	/**
	 * Initialize connection and define main table and field
	 *
	 */
	protected function _construct()
	{
		$this->_init('df_cms/hierarchy_node', 'node_id');
		$this->_metadataTable = $this->getTable('df_cms/hierarchy_metadata');
	}




	/**
	 * Add attributes filter to select object based on flags
	 *
	 * @param Zend_Db_Select $select Select object instance
	 * @return Df_Cms_Model_Mysql4_Hierarchy_Node
	 */
	protected function _applyParamFilters($select)
	{
		if ($this->_appendActivePagesOnly) {
			$select->where('page_table.is_active=1 OR ' . $this->getMainTable() . '.page_id IS NULL');
		}
		if ($this->_appendIncludedPagesOnly) {
			$select->where('metadata_table.menu_excluded=0');
		}
		return $this;
	}



	/**
	 * Retrieve select object for load object data.
	 * Join page information if page assigned.
	 * Join secondary table with meta data for root nodes.
	 *
	 * @param string $field
	 * @param mixed $value
	 * @param Df_Cms_Model_Hierarchy_Node $object
	 * @return Varien_Db_Select
	 */
	protected function _getLoadSelect($field, $value, $object)
	{
		$select = parent::_getLoadSelect($field, $value, $object);
		$select->joinLeft(array('page_table' => $this->getTable('cms/page')),
				$this->getMainTable() . '.page_id = page_table.page_id',
				array(
					'page_title'        => 'title',
					'page_identifier'   => 'identifier',
					'page_is_active'    => 'is_active'
				))
			->joinLeft(array('metadata_table' => $this->_metadataTable),
				$this->getMainTable() . '.' . $this->getIdFieldName() . ' = metadata_table.node_id',
				array(
					'pager_visibility',
					'pager_frame',
					'pager_jump',
					'menu_brief',
					'menu_excluded',
					'menu_levels_down',
					'menu_ordered',
					'menu_list_type'
				));

		$this->_applyParamFilters($select);

		return $select;
	}




	/**
	 * Preparing array where all nodes grouped in sub arrays by parent id.
	 *
	 * @param array $nodes source node's data
	 * @param int $startNodeId
	 * @param array $tree Initial array which will modified and returned with new data
	 * @return array
	 */
	protected function _prepareRelatedStructure($nodes, $startNodeId, $tree)
	{
		foreach ($nodes as $row) {
			$parentNodeId = (int)$row['parent_node_id'] == $startNodeId ? 0 : $row['parent_node_id'];
			$tree[$parentNodeId][$row[$this->getIdFieldName()]] = $row;
		}

		return $tree;
	}



    /**
     * Flag to indicate whether append active pages only or not
     * @var bool
     */
    protected $_appendActivePagesOnly = false;



    /**
     * Flag to indicate whether append included in menu pages only or not
     * @var bool
     */
    protected $_appendIncludedPagesOnly = false;



    /**
     * Primary key auto increment flag
     *
     * @var bool
     */
    protected $_isPkAutoIncrement = false;


    /**
     * Secondary table for storing meta data
     * @var string
     */
    protected $_metadataTable;


    /**
     * Tree Detalization, i.e. brief or detailed
     * @var bool
     */
    protected $_treeIsBrief = false;



	/**
	 * Maximum tree depth for tree slice, if equals zero - no limitations
	 * @var int
	 */
	protected $_treeMaxDepth = 0;


}
