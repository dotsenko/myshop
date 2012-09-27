<?php

class Df_Banner_Block_Adminhtml_Banner_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {


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
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'df_banner';
        $this->_controller = 'adminhtml_banner';
        
        $this->_updateButton('save', 'label', df_helper()->banner()->__('Утвердить и вернуться'));
        $this->_updateButton('delete', 'label', df_helper()->banner()->__('Удалить'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => df_mage()->adminhtml()->__('Утвердить и остаться'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('df_banner_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'df_banner_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'df_banner_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('df_banner_data') && Mage::registry('df_banner_data')->getId() ) {
            return df_helper()->banner()->__("Настройка щита «%s»", $this->escapeHtml(Mage::registry('df_banner_data')->getTitle()));
        } else {
            return df_helper()->banner()->__('Повесить новый щит...');
        }
    }
}