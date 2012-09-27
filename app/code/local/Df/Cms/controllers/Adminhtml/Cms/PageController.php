<?php

include('Mage/Adminhtml/controllers/Cms/PageController.php');

class Df_Cms_Adminhtml_Cms_PageController extends Mage_Adminhtml_Cms_PageController {


    /**
     * Edit CMS page
     */
    public function editAction()
    {
        $page = $this->_initPage();

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (! empty($data)) {
            $page->setData($data);
        }

        if ($page->getId()){
            if ($page->getUnderVersionControl()) {
                $this->_handles[] = 'adminhtml_cms_page_edit_changes';
            }
        } else if (!$page->hasUnderVersionControl()) {
            $page->setUnderVersionControl((int)Mage::getSingleton('df_cms/config')->getDefaultVersioningStatus());
        }

        $this->_title($page->getId() ? $page->getTitle() : $this->__('New Page'));

        $this->_initAction()
            ->_addBreadcrumb($page->getId() ? Mage::helper('cms')->__('Edit Page')
                    : Mage::helper('cms')->__('New Page'),
                $page->getId() ? Mage::helper('cms')->__('Edit Page')
                    : Mage::helper('cms')->__('New Page'));

        $this->renderLayout();
    }




    /**
     * Mass deletion for versions
     *
     */
    public function massDeleteVersionsAction()
    {

		if (
			! (
					df_enabled (Df_Core_Feature::CMS_2)
				&&
					df_cfg()->cms()->versioning()->isEnabled()
			)
		) {
            $this->_forward('denied');
            $this->setFlag ('', self::FLAG_NO_DISPATCH, true);
		}
		else {

			$ids = $this->getRequest()->getParam('version');
			if (!is_array($ids)) {
				$this->_getSession()->addError($this->__('Please select version(s)'));
			}
			else {
				try {
					$userId = Mage::getSingleton('admin/session')->getUser()->getId();
					$accessLevel = Mage::getSingleton('df_cms/config')->getAllowedAccessLevel();

					foreach ($ids as $id) {
						$version = Mage::getSingleton('df_cms/page_version')
							->loadWithRestrictions($accessLevel, $userId, $id);

						if ($version->getId()) {
							$version->delete();
						}
					}
					$this->_getSession()->addSuccess(
						$this->__('Total of %d record(s) were successfully deleted', count($ids))
					);
				} catch (Mage_Core_Exception $e) {
					$this->_getSession()->addError($e->getMessage());
				} catch (Exception $e) {
					Mage::logException($e);
					$this->_getSession()->addError(df_helper()->cms()->__('Error while deleting versions. Please try again later.'));
				}
			}
			$this->_redirect('*/*/edit', array('_current' => true, 'tab' => 'versions'));

		}


        return $this;
    }






    /**
     * Action for versions ajax tab
     *
     * @return Df_Cms_Adminhtml_Cms_Page_RevisionController
     */
    public function versionsAction()
    {

		if (
			! (
					df_enabled (Df_Core_Feature::CMS_2)
				&&
					df_cfg()->cms()->versioning()->isEnabled()
			)
		) {
            $this->_forward('denied');
            $this->setFlag ('', self::FLAG_NO_DISPATCH, true);
		}
		else {

			$this->_initPage();

			$this->loadLayout();
			$this->renderLayout();

		}

        return $this;
    }





    /**
     * Init actions
     *
	 * @override
     * @return Df_Cms_Adminhtml_Cms_PageController
     */
    protected function _initAction()
    {
		if (
			!(
					df_enabled (Df_Core_Feature::CMS_2)
				&&
					(
							df_cfg()->cms()->versioning()->isEnabled()
						||
							df_cfg()->cms()->hierarchy()->isEnabled()
					)
			)
		) {
			parent::_initAction();
		}
		else {

			$update = $this->getLayout()->getUpdate();
			$update->addHandle('default');

			// add default layout handles for this action
			$this->addActionLayoutHandles();
			$update->addHandle($this->_handles);

			$this->loadLayoutUpdates()
				->generateLayoutXml()
				->generateLayoutBlocks();

			$this->_initLayoutMessages('adminhtml/session');

			//load layout, set active menu and breadcrumbs
			$this->_setActiveMenu('cms/page')
				->_addBreadcrumb(Mage::helper('cms')->__('CMS'), Mage::helper('cms')->__('CMS'))
				->_addBreadcrumb(Mage::helper('cms')->__('Manage Pages'), Mage::helper('cms')->__('Manage Pages'));

			$this->_isLayoutLoaded = true;

		}

        return $this;
    }





    /**
     * Prepare and place cms page model into registry
     * with loaded data if id parameter present
     *
     * @param string $idFieldName
     * @return Mage_Cms_Model_Page
     */
    protected function _initPage()
    {
        $this->_title($this->__('CMS'))->_title($this->__('Pages'));

        $pageId = (int) $this->getRequest()->getParam('page_id');
        $page = Mage::getModel('cms/page');

        if ($pageId) {
            $page->load($pageId);
        }

        Mage::register('cms_page', $page);
        return $page;
    }








    /**
     * Check the permission to run action.
     *
	 * @override
     * @return boolean
     */
    protected function _isAllowed()
    {
        switch ($this->getRequest()->getActionName()) {
            case 'massDeleteVersions':
                return Mage::getSingleton('df_cms/config')->canCurrentUserDeleteVersion();
                break;
            default:
                return parent::_isAllowed();
                break;
        }
    }



	protected $_handles = array();
}
