<?php



/**
 * Revision selector
 *
 * @category   Df
 * @package    Df_Cms
 *
 */
class Df_Cms_Block_Adminhtml_Cms_Page_Preview_Revision extends Mage_Adminhtml_Block_Template
{
    /**
     * Retrieve id of currently selected revision
     *
     * @return int
     */
    public function getRevisionId()
    {
        if (!$this->hasRevisionId()) {
            $this->setData('revision_id', (int)$this->getRequest()->getPost('preview_selected_revision'));
        }
        return $this->getData('revision_id');
    }

    /**
     * Prepare array with revisions sorted by versions
     *
     * @return array
     */
    public function getRevisions()
    {
        /* var $collection Df_Cms_Model_Mysql4_Revision_Collection */
        $collection = Mage::getModel('df_cms/page_revision')->getCollection()
            ->addPageFilter($this->getRequest()->getParam('page_id'))
            ->joinVersions()
            ->addNumberSort()
            ->addVisibilityFilter(Mage::getSingleton('admin/session')->getUser()->getId(),
                Mage::getSingleton('df_cms/config')->getAllowedAccessLevel());

        $revisions = array();

        foreach ($collection->getItems() as $item) {
            if (isset($revisions[$item->getVersionId()])) {
                $revisions[$item->getVersionId()]['revisions'][] = $item;
            } else {
                $revisions[$item->getVersionId()] = array(
                    'revisions' => array($item),
                    'label' => ($item->getLabel() ? $item->getLabel() : $this->__('N/A'))
                );
            }
        }
        krsort($revisions);
        reset($revisions);
        return $revisions;
    }
}
