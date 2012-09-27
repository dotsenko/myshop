<?php



/**
 * Cms page version collection
 *
 * @category    Df
 * @package     Df_Cms
 *
 */

class Df_Cms_Model_Mysql4_Page_Version_Collection  extends Df_Cms_Model_Mysql4_Page_Collection_Abstract
{
    /**
     * Constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('df_cms/page_version');
    }

    /**
     * Add access level filter.
     * Can take parameter array or one level.
     *
     * @param mixed $level
     * @return Df_Cms_Model_Mysql4_Version_Collection
     */
    public function addAccessLevelFilter($level)
    {
        if (is_array($level)) {
            $this->addFieldToFilter('access_level', array('in' => $level));
        } else {
            $this->addFieldToFilter('access_level', $level);
        }

        return $this;
    }

    /**
     * Prepare two dimensional array basing on version_id as key and
     * version label as value data from collection.
     *
     * @return array
     */
    public function getIdLabelArray()
    {
        return $this->_toOptionHash('version_id', 'version_label');
    }

    /**
     * Prepare two dimensional array basing on key and value field.
     *
     * @param string $keyField
     * @param string $valueField
     * @return array
     */
    public function getAsArray($keyField, $valueField)
    {
        $data = $this->_toOptionHash($keyField, $valueField);
        return array_filter($data);
    }

    /**
     * Join revision data by version id
     *
     * @return Df_Cms_Model_Mysql4_Version_Collection
     */
    public function joinRevisions()
    {
        if (!$this->getFlag('revisions_joined')) {
            $this->getSelect()->joinLeft(
                array('rev_table' => $this->getTable('df_cms/page_revision')),
                'rev_table.version_id=main_table.version_id', '*');

            $this->setFlag('revisions_joined');
        }
        return $this;
    }

    /**
     * Add order by version number in specified direction.
     *
     * @param string $dir
     * @return Df_Cms_Model_Mysql4_Page_Version_Collection
     */
    public function addNumberSort($dir = 'desc')
    {
        $this->setOrder('version_number', $dir);

        return $this;
    }
}
