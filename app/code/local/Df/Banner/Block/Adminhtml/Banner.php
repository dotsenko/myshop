<?php

class Df_Banner_Block_Adminhtml_Banner extends Mage_Adminhtml_Block_Widget_Grid_Container {


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
    $this->_controller = 'adminhtml_banner';
    $this->_blockGroup = 'df_banner';
    $this->_headerText = df_helper()->banner()->__('Рекламные щиты');
    $this->_addButtonLabel = df_helper()->banner()->__('Повесить новый щит...');
    parent::__construct();
  }
}