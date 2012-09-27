<?php



/**
 * Cms Page Tree Edit Form Container Block
 *
 * @category   Df
 * @package    Df_Cms
 */
class Df_Cms_Block_Adminhtml_Cms_Hierarchy_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize Form Container
     *
     */
    public function __construct()
    {
        $this->_objectId   = 'node_id';
        $this->_blockGroup = 'df_cms';
        $this->_controller = 'adminhtml_cms_hierarchy';

        parent::__construct();

        $this->_updateButton('save', 'onclick', 'hierarchyNodes.save()');
        $this->_updateButton('save', 'label', df_helper()->cms()->__('Save Pages Hierarchy'));
        $this->_removeButton('back');

        if (Mage::getSingleton('df_cms/hierarchy_lock')->isLockedByOther()) {
            $confirmMessage = df_helper()->cms()->__('Are you sure you want to break current lock?');
            $this->addButton('break_lock', array(
                'label'     => df_helper()->cms()->__('Unlock This Page'),
                'onclick'   => "confirmSetLocation('{$confirmMessage}', '{$this->getUrl('*/*/lock')}')"
            ));
            $this->_updateButton('save', 'disabled', true);
            $this->_updateButton('save', 'class', 'disabled');
        }
    }

    /**
     * Retrieve text for header element
     *
     * @return string
     */
    public function getHeaderText()
    {
        return df_helper()->cms()->__('Manage Pages Hierarchy');
    }
}
