<?php



/**
 * Df Cms Resource Setup model
 *
 * @category   Df
 * @package    Df_Cms
 */
class Df_Cms_Model_Mysql4_Setup extends Mage_Core_Model_Resource_Setup
{
    /**
     * Fix xpath for hierarchy node table
     *
     * @return Df_Cms_Model_Mysql4_Setup
     */
    public function fixXpathForHierarchyNode()
    {
        $connection = $this->getConnection();
        $nodes  = array();
        $select = $connection->select()->from(
            $this->getTable('df_cms/hierarchy_node'),
            array('node_id', 'parent_node_id')
        );
        $rowSet = $select->query()->fetchAll();
        foreach ($rowSet as $k => $row) {
            $nodes[(int)$row['parent_node_id']][] = (int)$row['node_id'];
            unset($rowSet[$k]);
        }

        $this->_updateXpathCallback($nodes, null, 0);

        return $this;
    }

    /**
     * Update Hierarchy nodes Xpath Callback method
     *
     * @param array $nodes
     * @param string $xpath
     * @param int $parentNodeId
     * @return Df_Cms_Model_Mysql4_Setup
     */
    protected function _updateXpathCallback(array $nodes, $xpath = '', $parentNodeId = 0)
    {
        if (!isset($nodes[$parentNodeId])) {
            return $this;
        }
        foreach ($nodes[$parentNodeId] as $nodeId) {
            $nodeXpath = $xpath ? $xpath . '/' . $nodeId : $nodeId;

            $bind  = array(
                'xpath' => $nodeXpath
            );
            $where = $this->getConnection()->quoteInto('node_id=?', $nodeId);

            $this->getConnection()->update($this->getTable('df_cms/hierarchy_node'), $bind, $where);
            if (isset($nodes[$nodeId])) {
                $this->_updateXpathCallback($nodes, $nodeXpath, $nodeId);
            }
        }

        return $this;
    }
}
