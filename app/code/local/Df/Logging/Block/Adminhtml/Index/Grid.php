<?php


/**
 * Admin Actions Log Grid
 */
class Df_Logging_Block_Adminhtml_Index_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('loggingLogGrid');
        $this->setDefaultSort('time');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * PrepareCollection method.
     */
    protected function _prepareCollection()
    {
        $this->setCollection(Mage::getResourceModel('df_logging/event_collection'));
        return parent::_prepareCollection();
    }

    /**
     * Return grids url
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    /**
     * Grid URL
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/details', array('event_id'=>$row->getId()));
    }

    /**
     * Configuration of grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('time', array(
            'header'    => Mage::helper('df_logging')->__('Time'),
            'index'     => 'time',
            'type'      => 'datetime',
            'width'     => 160,
        ));

        $this->addColumn('event', array(
            'header'    => Mage::helper('df_logging')->__('Action Group'),
            'index'     => 'event_code',
            'type'      => 'options',
            'sortable'  => false,
            'options'   => Mage::getSingleton('df_logging/config')->getLabels(),
        ));

        $actions = array();
        foreach (Mage::getResourceSingleton('df_logging/event')->getAllFieldValues('action') as $action) {
            $actions[$action] = df_helper()->logging()->__ ($action);
        }
        $this->addColumn('action', array(
            'header'    => Mage::helper('df_logging')->__('Action'),
            'index'     => 'action',
            'type'      => 'options',
            'options'   => $actions,
            'sortable'  => false,
            'width'     => 75,
        ));

        $this->addColumn('ip', array(
            'header'    => Mage::helper('df_logging')->__('IP-address'),
            'index'     => 'ip',
            'type'      => 'text',
            'filter'    => 'df_logging/adminhtml_grid_filter_ip',
            'renderer'  => 'adminhtml/widget_grid_column_renderer_ip',
            'sortable'  => false,
            'width'     => 125,
        ));

        $this->addColumn('user', array(
            'header'    => Mage::helper('df_logging')->__('Username'),
            'index'     => 'user',
            'type'      => 'text',
            'sortable'  => false,
            'filter'    => 'df_logging/adminhtml_grid_filter_user',
            'width'     => 150,
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('df_logging')->__('Result'),
            'index'     => 'status',
            'sortable'  => false,
            'type'      => 'options',
            'options'   => array(
                Df_Logging_Model_Event::RESULT_SUCCESS => Mage::helper('df_logging')->__('Success'),
                Df_Logging_Model_Event::RESULT_FAILURE => Mage::helper('df_logging')->__('Failure'),
            ),
            'width'     => 100,
        ));

        $this->addColumn('fullaction', array(
            'header'   => Mage::helper('df_logging')->__('Full Action Name'),
            'index'    => 'fullaction',
            'sortable' => false,
            'type'     => 'text'
        ));

        $this->addColumn('info', array(
            'header'    => Mage::helper('df_logging')->__('Short Details'),
            'index'     => 'info',
            'type'      => 'text',
            'sortable'  => false,
            'filter'    => 'adminhtml/widget_grid_column_filter_text',
            'width'     => 100,
        ));

        $this->addColumn('view', array(
            'header'  => Mage::helper('df_logging')->__('Full Details'),
            'width'   => 50,
            'type'    => 'action',
            'getter'  => 'getId',
            'actions' => array(array(
                'caption' => Mage::helper('df_logging')->__('View Details'),
                'url'     => array(
                    'base'   => '*/*/details',
                ),
                'field'   => 'event_id'
            )),
            'filter'    => false,
            'sortable'  => false,
        ));

        $this->addExportType('*/*/exportCsv', 'CSV');
        $this->addExportType('*/*/exportXml', 'MSXML');
        return $this;
    }


}
