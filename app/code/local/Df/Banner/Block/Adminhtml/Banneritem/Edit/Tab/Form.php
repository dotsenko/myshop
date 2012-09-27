<?php

class Df_Banner_Block_Adminhtml_Banneritem_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

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














  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('df_banner_item_form', array('legend'=>df_helper()->banner()->__('Настройки')));
     
	  $banners = array(''=>'-- На каком рекламном щите разместить? --');
	  $collection = Mage::getModel('df_banner/banner')->getCollection();
	  foreach ($collection as $banner) {
		 $banners[$banner->getId()] = $banner->getTitle();
	  }

	  $fieldset->addField('banner_id', 'select', array(
          'label'     => df_helper()->banner()->__('Щит'),
          'name'      => 'banner_id',
          'required'  => true,
          'values'    => $banners,
      ));

      $fieldset->addField('title', 'text', array(
          'label'     => df_helper()->banner()->__('Заголовок'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
      
	  $fieldset->addField('banner_order', 'text', array(
          'label'     => df_helper()->banner()->__('Порядок показа'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'banner_order',
      ));
      
      $fieldset->addField('image', 'image', array(
          'label'     => df_helper()->banner()->__('Загрузить рекламную картинку:'),
          'required'  => false,
          'name'      => 'image',
	  ));

      $fieldset->addField('image_url', 'text', array(
          'label'     => df_helper()->banner()->__('Указать веб-адрес картинки (вместо загрузки):'),
          'required'  => false,
          'name'      => 'image_url',
	  ));

      $fieldset->addField('thumb_image', 'image', array(
          'label'     => df_helper()->banner()->__('Мини-картинка'),
          'required'  => false,
          'name'      => 'thumb_image',
	  ));

      $fieldset->addField('thumb_image_url', 'text', array(
          'label'     => df_helper()->banner()->__('Веб-адрес мини-картинки (вместо загрузки):'),
          'required'  => false,
          'name'      => 'thumb_image_url',
	  ));
		
      $fieldset->addField('link_url', 'text', array(
          'label'     => df_helper()->banner()->__('На какой веб-адрес ведёт посетителя объявление?'),
          'required'  => false,
          'name'      => 'link_url',
	  ));

	  $fieldset->addField('status', 'select', array(
          'label'     => df_helper()->banner()->__('Опубликовано?'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => df_helper()->banner()->__('Да'),
              ),

              array(
                  'value'     => 2,
                  'label'     => df_helper()->banner()->__('Нет'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => df_helper()->banner()->__('Дополнительный блок текста'),
          'title'     => df_helper()->banner()->__('Дополнительный блок текста'),
          'style'     => 'width:600px; height:300px;',
          'wysiwyg'   => false,
          'required'  => false,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getDfBannerItemData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getDfBannerItemData());
          Mage::getSingleton('adminhtml/session')->setDfBannerItemData(null);
      } elseif ( Mage::registry('df_banner_item_data') ) {
          $form->setValues(Mage::registry('df_banner_item_data')->getData());
      }
      return parent::_prepareForm();
  }
}