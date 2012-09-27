<?php

class Df_Banner_Block_Adminhtml_Banner_Grid extends Mage_Adminhtml_Block_Widget_Grid {


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
      $this->setId('df_banner_grid');
      $this->setDefaultSort('banner_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('df_banner/banner')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('banner_id', array(
          'header'    => df_helper()->banner()->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'banner_id',
      ));

      $this->addColumn('identifier', array(
          'header'    => df_helper()->banner()->__('Внутреннее системное имя (вы потом используете его в макете)'),
          'align'     =>'left',
          'index'     => 'identifier',
      ));

	  $this->addColumn('title', array(
          'header'    => df_helper()->banner()->__('Название'),
          'align'     =>'left',
          'index'     => 'title',
      ));

      $this->addColumn('show_title', array(
          'header'    => df_helper()->banner()->__('Показывать название посетителям?'),
          'align'     => 'left',
          'width'     => '40px',
          'index'     => 'show_title',
          'type'      => 'options',
          'options'   => array(
              1 => 'Yes',
              2 => 'No',
          ),
      ));

	  $this->addColumn('width', array(
          'header'    => df_helper()->banner()->__('Ширина (в пикселях)'),
          'align'     =>'right',
          'width'     => '40px',
          'index'     => 'width',
      ));

	  	  $this->addColumn('height', array(
          'header'    => df_helper()->banner()->__('Высота (в пикселях)'),
          'align'     =>'right',
          'width'     => '40px',
          'index'     => 'height',
      ));

	  $this->addColumn('delay', array(
          'header'    => df_helper()->banner()->__('Продолжительность показа одного объявления (в милисекундах)'),
          'align'     =>'right',
          'width'     => '40px',
          'index'     => 'delay',
      ));
	  /*
      $this->addColumn('content', array(
			'header'    => df_helper()->banner()->__('Item Content'),
			'width'     => '150px',
			'index'     => 'content',
      ));
	  */

      $this->addColumn('status', array(
          'header'    => df_helper()->banner()->__('Включен?'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Да',
              2 => 'Нет',
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
        $this->setMassactionIdField('banner_id');
        $this->getMassactionBlock()->setFormFieldName('df_banner');

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