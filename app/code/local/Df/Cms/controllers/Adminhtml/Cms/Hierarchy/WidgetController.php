<?php



/**
 * Admihtml Widget Controller for Hierarchy Node Link plugin
 *
 * @category   Df
 * @package    Df_Cms
 */
class Df_Cms_Adminhtml_Cms_Hierarchy_WidgetController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Chooser Source action
     */
    public function chooserAction()
    {
        $this->getResponse()->setBody(
            $this->_getTreeBlock()->getTreeHtml()
        );
    }

    /**
     * Tree block instance
     */
    protected function _getTreeBlock()
    {
        return $this->getLayout()->createBlock('df_cms/adminhtml_cms_hierarchy_widget_chooser', '', array(
            'id' => $this->getRequest()->getParam('uniq_id')
        ));
    }
}
