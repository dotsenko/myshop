<?php



/**
 * Meta tab with cms page attributes and some modifications to CE version
 *
 * @category    Df
 * @package     Df_Cms
 *
 */

class Df_Cms_Block_Adminhtml_Cms_Page_Revision_Edit_Tab_Meta
    extends Mage_Adminhtml_Block_Cms_Page_Edit_Tab_Meta
{
    /**
     * Adding onchange js call
     *
     * @return Df_Cms_Block_Adminhtml_Cms_Page_Revision_Edit_Tab_Meta
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();

        df_helper()->cms()->addOnChangeToFormElements($this->getForm(), 'dataChanged();');

        return $this;
    }

    /**
     * Check permission for passed action
     * Rewrite CE save permission to EE save_revision
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        if ($action == 'save') {
            $action = 'save_revision';
        }
        return parent::_isAllowedAction($action);
    }
}
