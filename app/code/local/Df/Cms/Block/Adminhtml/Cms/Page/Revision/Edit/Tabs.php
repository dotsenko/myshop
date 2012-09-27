<?php



/**
 * Tab control for revision edit page
 *
 * @category    Df
 * @package     Df_Cms
 *
 */
class Df_Cms_Block_Adminhtml_Cms_Page_Revision_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(df_helper()->cms()->__('Revision Information'));
    }
}
