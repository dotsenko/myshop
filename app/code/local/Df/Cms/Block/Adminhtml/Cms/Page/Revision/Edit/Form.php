<?php



/**
 * Form for revision edit page
 *
 * @category    Df
 * @package     Df_Cms
 *
 */

class Df_Cms_Block_Adminhtml_Cms_Page_Revision_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Preparing from for revision page
     *
     * @return Df_Cms_Block_Adminhtml_Cms_Page_Revision_Edit_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post'));
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
