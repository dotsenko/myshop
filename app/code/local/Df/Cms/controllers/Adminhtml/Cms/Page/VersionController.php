<?php

include('Df/Cms/controllers/Adminhtml/Cms/PageController.php');

class Df_Cms_Adminhtml_Cms_Page_VersionController extends Df_Cms_Adminhtml_Cms_PageController
{



    /**
     * Delete action
     *
     * @return Df_Cms_Adminhtml_Cms_Page_VersionController
     */
    public function deleteAction()
    {
        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('version_id')) {
             // init model
            $version = $this->_initVersion();
            $error = false;
            try {
                $version->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(df_helper()->cms()->__('Version was successfully deleted.'));
                $this->_redirect('*/cms_page/edit', array('page_id' => $version->getPageId()));
                return;
            } catch (Mage_Core_Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $error = true;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(df_helper()->cms()->__('Error while deleting version. Please try again later.'));
                $error = true;
            }

            // go back to edit form
            if ($error) {
                $this->_redirect('*/*/edit', array('_current' => true));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(df_helper()->cms()->__('Unable to find a version to delete.'));
        // go to grid
        $this->_redirect('*/cms_page/edit', array('_current' => true));
        return $this;
    }





    /**
     * Edit version of CMS page
     *
     * @return Df_Cms_Adminhtml_Cms_Page_VersionController
     */
    public function editAction()
    {
        $version = $this->_initVersion();

        if (!$version->getId()) {
            Mage::getSingleton('adminhtml/session')->addError(
                df_helper()->cms()->__('Could not load specified version.'));

            $this->_redirect('*/cms_page/edit',
                array('page_id' => $this->getRequest()->getParam('page_id')));
            return;
        }

        $page = $this->_initPage();

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $_data = $version->getData();
            $_data = array_merge($_data, $data);
            $version->setData($_data);
        }

        $this->_initAction()
            ->_addBreadcrumb(df_helper()->cms()->__('Edit Version'),
                df_helper()->cms()->__('Edit Version'));

        $this->renderLayout();

        return $this;
    }




    /**
     * Mass deletion for revisions
     *
     * @return Df_Cms_Adminhtml_Cms_Page_VersionController
     */
    public function massDeleteRevisionsAction()
    {
        $ids = $this->getRequest()->getParam('revision');
        if (!is_array($ids)) {
            $this->_getSession()->addError($this->__('Please select revision(s)'));
        }
        else {
            try {
                $userId = Mage::getSingleton('admin/session')->getUser()->getId();
                $accessLevel = Mage::getSingleton('df_cms/config')->getAllowedAccessLevel();

                foreach ($ids as $id) {
                    $revision = Mage::getSingleton('df_cms/page_revision')
                        ->loadWithRestrictions($accessLevel, $userId, $id);

                    if ($revision->getId()) {
                        $revision->delete();
                    }
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully deleted', count($ids))
                );
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError(df_helper()->cms()->__('Error while deleting revisions. Please try again later.'));
            }
        }
        $this->_redirect('*/*/edit', array('_current' => true, 'tab' => 'revisions'));

        return $this;
    }





    /**
     * New Version
     *
     * @return Df_Cms_Adminhtml_Cms_Page_VersionController
     */
    public function newAction()
    {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            // init model and set data
            $version = $this->_initVersion();

            $version->addData($data)
                ->unsetData($version->getIdFieldName());

            // only if user not specified we set current user as owner
            if (!$version->getUserId()) {
                $version->setUserId(Mage::getSingleton('admin/session')->getUser()->getId());
            }

            if (isset($data['revision_id'])) {
                $data = $this->_filterPostData($data);
                $version->setInitialRevisionData($data);
            }

            // try to save it
            try {
                $version->save();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(df_helper()->cms()->__('New version was successfully created.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if (isset($data['revision_id'])) {
                    $this->_redirect('*/cms_page_revision/edit', array(
                        'page_id' => $version->getPageId(),
                        'revision_id' => $version->getLastRevision()->getId()
                    ));
                } else {
                    $this->_redirect('*/cms_page_version/edit', array(
                        'page_id' => $version->getPageId(),
                        'version_id' => $version->getId()
                    ));
                }
                return;
            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                if ($this->_getRefererUrl()) {
                    // save data in session
                    Mage::getSingleton('adminhtml/session')->setFormData($data);
                }
                // redirect to edit form
                $this->_redirectReferer($this->getUrl('*/cms_page/edit',
                    array('page_id' => $this->getRequest()->getParam('page_id'))));
                return;
            }
        }
        return $this;
    }





	/**
	 * Controller predispatch method
	 *
	 * @return Mage_Adminhtml_Controller_Action
	 */
	public function preDispatch()
	{
		parent::preDispatch();

        if (
			!(
					df_enabled (Df_Core_Feature::CMS_2)
				&&
					df_cfg()->cms()->versioning()->isEnabled()
			)
		) {
            if ($this->getRequest()->getActionName() != 'denied') {
				$this->_forward('denied');
				$this->setFlag ('', self::FLAG_NO_DISPATCH, true);
            }
        }

		return $this;

	}






    /**
     * Action for ajax grid with revisions
     *
     * @return Df_Cms_Adminhtml_Cms_Page_VersionController
     */
    public function revisionsAction()
    {
        $this->_initVersion();
        $this->_initPage();

        $this->loadLayout();
        $this->renderLayout();

        return $this;
    }





    /**
     * Save Action
     *
     * @return Df_Cms_Adminhtml_Cms_Page_VersionController
     */
    public function saveAction()
    {
        // check if data sent
        if ($data = $this->getRequest()->getPost()) {
            // init model and set data
            $version = $this->_initVersion();

            // if current user not publisher he can't change owner
            if (!Mage::getSingleton('df_cms/config')->canCurrentUserPublishRevision()) {
                unset($data['user_id']);
            }
            $version->addData($data);

            // try to save it
            try {
                // save the data
                $version->save();

                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(df_helper()->cms()->__('Version was successfully saved.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/' . $this->getRequest()->getParam('back'),
                        array(
                            'page_id' => $version->getPageId(),
                            'version_id' => $version->getId()
                        ));
                    return;
                }
                // go to grid
                $this->_redirect('*/cms_page/edit', array('page_id' => $version->getPageId()));
                return;

            } catch (Exception $e) {
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // save data in session
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                // redirect to edit form
                $this->_redirect('*/*/edit',
                    array(
                        'page_id' => $this->getRequest()->getParam('page_id'),
                        'version_id' => $this->getRequest()->getParam('version_id'),
                        ));
                return;
            }
        }
        return $this;
    }






    /**
     * Init actions
     *
     * @return Df_Cms_Adminhtml_Cms_Page_VersionController
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        $this->loadLayout()
            ->_setActiveMenu('cms/page')
            ->_addBreadcrumb(Mage::helper('cms')->__('CMS'), Mage::helper('cms')->__('CMS'))
            ->_addBreadcrumb(Mage::helper('cms')->__('Manage Pages'), Mage::helper('cms')->__('Manage Pages'))
        ;
        return $this;
    }





    /**
     * Prepare and place version's model into registry
     * with loaded data if id parameter present
     *
     * @param int $versionId
     * @return Df_Cms_Model_Page_Version
     */
    protected function _initVersion($versionId = null)
    {
        if (is_null($versionId)) {
            $versionId = (int) $this->getRequest()->getParam('version_id');
        }

        $version = Mage::getModel('df_cms/page_version');
        /* @var $version Df_Cms_Model_Page_Version */

        if ($versionId) {
            $userId = Mage::getSingleton('admin/session')->getUser()->getId();
            $accessLevel = Mage::getSingleton('df_cms/config')->getAllowedAccessLevel();
            $version->loadWithRestrictions($accessLevel, $userId, $versionId);
        }

        Mage::register('cms_page_version', $version);
        return $version;
    }




    /**
     * Check the permission to run it
     * May be in future there will be separate permissions for operations with version
     *
     * @return boolean
     */
    protected function _isAllowed()
    {

		/** @var bool $result  */
		$result = false;

        if (
				df_enabled (Df_Core_Feature::CMS_2)
			&&
				df_cfg()->cms()->versioning()->isEnabled()
		) {

			switch ($this->getRequest()->getActionName()) {
				case 'new':
				case 'save':
					$result = Mage::getSingleton('df_cms/config')->canCurrentUserSaveVersion();
					break;
				case 'delete':
					$result = Mage::getSingleton('df_cms/config')->canCurrentUserDeleteVersion();
					break;
				case 'massDeleteRevisions':
					$result = Mage::getSingleton('df_cms/config')->canCurrentUserDeleteRevision();
					break;
				default:
					$result = Mage::getSingleton('admin/session')->isAllowed('cms/page');
					break;
			}
        }

		df_result_boolean ($result);

		return $result;

    }
}
