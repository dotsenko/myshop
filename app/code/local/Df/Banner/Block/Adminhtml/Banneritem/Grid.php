<?php

class Df_Banner_Block_Adminhtml_Banneritem_Grid extends Mage_Adminhtml_Block_Widget_Grid {

	/**
	 * @override
	 * @return string|null
	 */
	public function getTemplate () {


		/** @var string|null $result  */
		$result =

				/**
				 * В отличие от витрины, шаблоны административной части будут отображаться
				 * даже если модуль отключен (но модуль должен быть лицензирован)
				 */
				!(df_enabled (Df_Core_Feature::BANNER))
			?
				NULL
			:
				parent::getTemplate ()
		;


		if (!is_null ($result)) {
			df_assert_string ($result);
		}


		return $result;

	}










  public function __construct()
  {
      parent::__construct();
      $this->setId('df_banner_itemGrid');
      $this->setDefaultSort('banner_item_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('df_banner/banneritem')->getCollection()->setOrder('banner_id','DESC')->setOrder('banner_order','ASC');
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
  	  $this->setTemplate('df/banner/grid.phtml');
      $this->addColumn('banner_item_id', array(
          'header'    => df_helper()->banner()->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'banner_item_id',
      ));

	  $banners = array();
	  $collection = Mage::getModel('df_banner/banner')->getCollection();
	  foreach ($collection as $banner) {
		 $banners[$banner->getId()] = $banner->getTitle();
	  }
	  
	  $this->addColumn('banner_id', array(
          'header'    => df_helper()->banner()->__('Banner'),
          'align'     =>'left',
          'index'     => 'banner_id',
		  'type'      => 'options',
          'options'   => $banners,
      ));

	  $this->addColumn('image',
			array(
				'header'=> df_helper()->banner()->__('Image'),
				'type'  => 'image',
				'width' => 64,
				'index' => 'image',
		));

      $this->addColumn('title', array(
          'header'    => df_helper()->banner()->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));

      $this->addColumn('url', array(
          'header'    => df_helper()->banner()->__('Url'),
          'align'     =>'left',
          'index'     => 'url',
      ));
      
 	  $this->addColumn('banner_order', array(
          'header'    => df_helper()->banner()->__('Order'),
          'align'     =>'left',
 	  	  'width' 	  => 64,
          'index'     => 'banner_order',
      ));
	  
      $this->addColumn('status', array(
		  'header'    => df_helper()->banner()->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  df_helper()->banner()->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => df_helper()->banner()->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', df_helper()->banner()->__('CSV'));
		$this->addExportType('*/*/exportXml', df_helper()->banner()->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('banner_item_id');
        $this->getMassactionBlock()->setFormFieldName('df_banner_item');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => df_helper()->banner()->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => df_helper()->banner()->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('df_banner/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> df_helper()->banner()->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => df_helper()->banner()->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}