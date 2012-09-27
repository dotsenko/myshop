<?php

/**
 * Admihtml Manage Cms Hierarchy Controller
 *
 * @category   Df
 * @package    Df_Cms
 */
class Df_Cms_Adminhtml_Cms_HierarchyController extends Mage_Adminhtml_Controller_Action {



    /**
     * Edit Page Tree
     *
     */
    public function indexAction()
    {
        $this->_title($this->__('CMS'))
             ->_title($this->__('Pages'))
             ->_title($this->__('Manage Hierarchy'));

        $this->_getLockModel()->revalidate();

        if ($this->_getLockModel()->isLockedByMe()) {
            $this->_getSession()->addNotice(
                df_helper()->cms()->__('This Page is locked by you.')
            );
        }

        if ($this->_getLockModel()->isLockedByOther()) {
            $this->_getSession()->addNotice(
                df_helper()->cms()->__("This Page is locked by '%s'.", $this->_getLockModel()->getUserName())
            );
        }

        $node = Mage::getModel('df_cms/hierarchy_node');

        $data = $this->_getSession()->getFormData(true);
        if (!empty($data)) {
            $node->addData($data);
        }

        Mage::register('current_hierarchy_node', $node);

        $this->_initAction()
            ->renderLayout();
    }




    /**
     * Lock page
     */
    public function lockAction()
    {
        $this->_getLockModel()->lock();
        $this->_redirect('*/*/');
    }




    /**
     * Cms Pages Ajax Grid
     *
     */
    public function pageGridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }




	/**
     * Controller pre dispatch method
     *
	 * @override
     * @return Df_Cms_Adminhtml_Cms_HierarchyController
     */
    public function preDispatch()
    {
        parent::preDispatch();
        if (
			!(
					df_enabled (Df_Core_Feature::CMS_2)
				&&
					df_cfg()->cms()->hierarchy()->isEnabled()
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
     * Save changes
     *
     */
    public function saveAction()
    {
        if ($this->getRequest()->isPost()) {
            if (Mage::getModel('df_cms/hierarchy_lock')->isLockedByOther()) {
                $this->_getSession()->addError(
                    df_helper()->cms()->__('This page is currently locked.')
                );
                $this->_redirect('*/*/');
                return $this;
            }

            /** @var $node Df_Cms_Model_Hierarchy_Node */
            $node       = Mage::getModel('df_cms/hierarchy_node');
            $data       = $this->getRequest()->getPost();
            $hasError   = true;

            try {
                if (!empty($data['nodes_data'])) {
                    $nodesData = df_mage()->coreHelper()->jsonDecode($data['nodes_data']);
                } else {
                    $nodesData = array();
                }
                if (!empty($data['removed_nodes'])) {
                    $removedNodes = explode(',', $data['removed_nodes']);
                } else {
                    $removedNodes = array();
                }


                $node->collectTree($nodesData, $removedNodes);

                $hasError = false;
                $this->_getSession()->addSuccess(
                    df_helper()->cms()->__('Hierarchy has been successfully saved.')
                );
            }
            catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addException($e,
                    df_helper()->cms()->__('Error in saving hierarchy.')
                );
                Mage::logException($e);
            }

            if ($hasError) {
                //save data in session
                $this->_getSession()->setFormData($data);
            }
        }

        $this->_redirect('*/*/');
    }







    /**
     * Return lock model instance
     *
     * @return Df_Cms_Model_Hierarchy_Lock
     */
    protected function _getLockModel()
    {
        return Mage::getSingleton('df_cms/hierarchy_lock');
    }




    /**
     * Check is allowed access to action
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return
				Mage::getSingleton('admin/session')->isAllowed('cms/hierarchy')
			&&
				df_enabled (Df_Core_Feature::CMS_2)
			&&
				df_cfg()->cms()->hierarchy()->isEnabled()
		;
    }




    /**
     * Load layout, set active menu and breadcrumbs
     *
     * @return Df_Cms_Adminhtml_Cms_HierarchyController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('cms/hierarchy')
            ->_addBreadcrumb(df_helper()->cms()->__('CMS'),
                df_helper()->cms()->__('CMS'))
            ->_addBreadcrumb(df_helper()->cms()->__('CMS Page Trees'),
                df_helper()->cms()->__('CMS Page Trees'));
        return $this;
    }

}
