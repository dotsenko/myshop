<?php


/**
 * Cms page edit form revisions tab
 *
 * @category    Df
 * @package     Df_Cms
 *
 */

class Df_Cms_Block_Adminhtml_Cms_Page_Revision_Edit_Info extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * Currently loaded page model
     *
     * @var Eanterprise_Cms_Model_Page
     */
    protected $_page;

    public function __construct()
    {
        parent::__construct();
        $this->_page = Mage::registry('cms_page');
    }

    /**
     * Prepare version identifier. It should be
     * label or id if first one not assigned.
     * Also can be N/A.
     *
     * @return string
     */
    public function getVersion()
    {
        $version = '';
        if ($this->_page->getLabel()) {
            $version = $this->_page->getLabel();
        } else {
            $version = $this->_page->getVersionId();
        }
        return $version ? $version : df_helper()->cms()->__('N/A');
    }

    /**
     * Prepare version number.
     *
     * @return string
     */
    public function getVersionNumber()
    {
        return $this->_page->getVersionNumber() ? $this->_page->getVersionNumber()
            : df_helper()->cms()->__('N/A');
    }

    /**
     * Prepare version label.
     *
     * @return string
     */
    public function getVersionLabel()
    {
        return $this->_page->getLabel() ? $this->_page->getLabel()
            : df_helper()->cms()->__('N/A');
    }

    /**
     * Prepare revision identifier.
     *
     * @return string
     */
    public function getRevisionId()
    {
        return $this->_page->getRevisionId() ? $this->_page->getRevisionId()
            : df_helper()->cms()->__('N/A');
    }

    /**
     * Prepare revision number.
     *
     * @return string
     */
    public function getRevisionNumber()
    {
        return $this->_page->getRevisionNumber();
    }

    /**
     * Prepare author identifier.
     *
     * @return string
     */
    public function getAuthor()
    {
        $userId = $this->_page->getUserId();
        if (Mage::getSingleton('admin/session')->getUser()->getId() == $userId) {
            return Mage::getSingleton('admin/session')->getUser()->getUsername();
        }

        $user = Mage::getModel('admin/user')
            ->load($userId);

        if ($user->getId()) {
            return $user->getUsername();
        }
        return df_helper()->cms()->__('N/A');
    }

    /**
     * Prepare time of creation for current revision.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        $format = Mage::app()->getLocale()->getDateTimeFormat(
                Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM
            );
        $data = $this->_page->getRevisionCreatedAt();
        try {
            $data = Mage::app()->getLocale()->date($data, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString($format);
        } catch (Exception $e) {
            $data = df_helper()->cms()->__('N/A');
        }
        return  $data;
    }
}
