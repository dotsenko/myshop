<?php

class Df_Banner_Block_Adminhtml_Banneritem extends Mage_Adminhtml_Block_Widget_Grid_Container {


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
    $this->_controller = 'adminhtml_banneritem';
    $this->_blockGroup = 'df_banner';
    $this->_headerText = df_helper()->banner()->__('Рекламные объявления');
    $this->_addButton('save', array(
            'label'     => df_helper()->banner()->__('Утвердить порядок показа'),
            'onclick'   => 'save_order()',
			'id'		=> 'save_cat',
        ));
    $this->_addButtonLabel = df_helper()->banner()->__('Составить новое объявление...');
    parent::__construct();
  }
  
	public function getSaveOrderUrl()
    {
        return $this->getUrl('*/*/setOrder');
    }
}