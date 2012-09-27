<?php


/**
 * Cms page edit form revisions tab
 *
 * @category    Df
 * @package     Df_Cms
 *
 */

class Df_Cms_Block_Adminhtml_Cms_Page_Edit
    extends Mage_Adminhtml_Block_Template
{
    /**
     * Adding js to CE blocks to implement special functionality which
     * will allow go back to edit page with pre loaded tab passed through query string.
     * Added permission checking to remove some buttons if needed.
     *
     * @return Df_Cms_Block_Adminhtml_Cms_Page_Edit
     */
    protected function _prepareLayout()
    {
		if (
				df_cfg()->cms()->versioning()->isEnabled()
			&&
				df_enabled (Df_Core_Feature::CMS_2)
		) {

			$tabsBlock = $this->getLayout()->getBlock('cms_page_edit_tabs');
			/* @var $tabBlock Mage_Adminhtml_Block_Cms_Page_Edit_Tabs */
			if ($tabsBlock) {
				$editBlock = $this->getLayout()->getBlock('cms_page_edit');
				/* @var $editBlock Mage_Adminhtml_Block_Cms_Page_Edit */
				if ($editBlock) {

					$page = Mage::registry('cms_page');

					if ($page) {
						if ($page->getId()) {
							$editBlock->addButton('preview', array(
								'label'     => df_helper()->cms()->__('Preview'),
								'onclick'   => 'pagePreviewAction()',
								'class'     => 'preview',
							));
						}

						$formBlock = $editBlock->getChild('form');
						if ($formBlock) {
							$formBlock->setTemplate('df/cms/page/edit/form.phtml');
							if ($page->getUnderVersionControl()) {
								$tabId = $this->getRequest()->getParam('tab');
								if ($tabId) {
									$formBlock->setSelectedTabId($tabsBlock->getId() . '_' . $tabId)
										->setTabJsObject($tabsBlock->getJsObjectName());
								}
							}
						}
						// If user non-publisher he can save page only if it has disabled status
						if ($page->getUnderVersionControl()) {
							if ($page->getId() && $page->getIsActive() == Mage_Cms_Model_Page::STATUS_ENABLED) {
								if (!Mage::getSingleton('df_cms/config')->canCurrentUserPublishRevision()) {
									$editBlock->removeButton('delete');
									$editBlock->removeButton('save');
									$editBlock->removeButton('saveandcontinue');
								}
							}
						}
					}
				}
			}

		}

        return $this;
    }
}
