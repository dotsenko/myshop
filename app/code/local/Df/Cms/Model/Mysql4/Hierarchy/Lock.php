<?php


/**
 * Hierarchy Lock Resource Model
 *
 * @category   Df
 * @package    Df_Cms
 */
class Df_Cms_Model_Mysql4_Hierarchy_Lock extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Initialize connection and define main table and field
     *
     */
    protected function _construct()
    {
        $this->_init('df_cms/hierarchy_lock', 'lock_id');
    }

    /**
     * Return last lock information
     *
     * @return array
     */
    public function getLockData()
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable())
            ->order('lock_id DESC')
            ->limit(1);
        $data = $this->_getReadAdapter()->fetchRow($select);
        return is_array($data) ? $data : array();
    }
}
