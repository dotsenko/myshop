<?php



/**
 * Increment resource model
 *
 * @category    Df
 * @package     Df_Cms
 *
 */

class Df_Cms_Model_Mysql4_Increment extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init('df_cms/increment', 'increment_id');
    }

    /**
     * Load increment counter by passed node and level
     *
     * @param Mage_Core_Model_Abstract $object
     * @param int $type
     * @param int $node
     * @param int $level
     * @return bool
     */
    public function loadByTypeNodeLevel(Mage_Core_Model_Abstract $object, $type, $node, $level)
    {
        $read = $this->_getReadAdapter();

        $select = $read->select()->from($this->getMainTable())
            ->forUpdate(true)
            ->where('type=?', $type)
            ->where('node=?', $node)
            ->where('level=?', $level);

        $data = $read->fetchRow($select);

        if (!$data) {
            return false;
        }

        $object->setData($data);

        $this->_afterLoad($object);

        return true;
    }

    /**
     * Remove unneeded increment record.
     *
     * @param int $type
     * @param int $node
     * @param int $level
     * @return Df_Cms_Model_Mysql4_Increment
     */
    public function cleanIncrementRecord($type, $node, $level)
    {
        $write = $this->_getWriteAdapter();
        $write->delete($this->getMainTable(),
            array('type=?' => $type,
                'node=?' => $node,
                'level=?' => $level));

        return $this;
    }
}
