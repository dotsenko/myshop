<?php


/**
 * Admin Actions Log Archive grid
 *
 */
class Df_Logging_Block_Adminhtml_Archive_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialize default sorting and html ID
     */
    protected function _construct()
    {
        $this->setId('loggingArchiveGrid');
        $this->setDefaultSort('basename');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Prepare grid collection
     *
     * @return Df_Logging_Block_Events_Archive_Grid
     */
    protected function _prepareCollection()
    {
        $this->setCollection(Mage::getSingleton('df_logging/archive_collection'));
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     *
     * @return Df_Logging_Block_Events_Archive_Grid
     */
    protected function _prepareColumns()
    {
        $downloadUrl = $this->getUrl('*/*/download');

        $this->addColumn('download', array(
            'header'    => Mage::helper('df_logging')->__('Archive File'),
            'format'    => '<a href="' . $downloadUrl .'basename/$basename/">$basename</a>',
            'index'     => 'basename',
        ));

        $this->addColumn('date', array(
            'header'    => Mage::helper('df_logging')->__('Date'),
            'type'      => 'date',
            'index'     => 'time',
            'filter'    => 'df_logging/adminhtml_archive_grid_filter_date'
        ));

        return parent::_prepareColumns();
    }

    /**
     * Row click callback URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/archiveGrid', array('_current' => true));
    }
}
