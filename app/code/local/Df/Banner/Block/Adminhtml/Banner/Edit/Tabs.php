<?php

class Df_Banner_Block_Adminhtml_Banner_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {


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
      $this->setId('df_banner_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(df_helper()->banner()->__('Настройки'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => df_helper()->banner()->__('Основные'),
          'title'     => df_helper()->banner()->__('Основные'),
          'content'   => $this->getLayout()->createBlock('df_banner/adminhtml_banner_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}