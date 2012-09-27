<?php



/**
 * Cms page version model
 *
 * @category    Df
 * @package     Df_Cms
 *
 */

class Df_Cms_Model_Page_Version extends Mage_Core_Model_Abstract
{
    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'df_cms_version';

    /**
     * Parameter name in event.
     * In observe method you can use $observer->getEvent()->getObject() in this case.
     *
     * @var string
     */
    protected $_eventObject = 'version';

    /**
     * Access level constants
     */
    const ACCESS_LEVEL_PRIVATE = 'private';
    const ACCESS_LEVEL_PROTECTED = 'protected';
    const ACCESS_LEVEL_PUBLIC = 'public';

    /**
     * Constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('df_cms/page_version');
    }

    /**
     * Preparing data before save
     *
     * @return Df_Cms_Model_Page_Version
     */
    protected function _beforeSave()
    {
        if (!$this->getId()) {
            $incrementNumber = Mage::getModel('df_cms/increment')
                ->getNewIncrementId(Df_Cms_Model_Increment::TYPE_PAGE,
                        $this->getPageId(), Df_Cms_Model_Increment::LEVEL_VERSION);

            $this->setVersionNumber($incrementNumber);
            $this->setCreatedAt(Mage::getSingleton('core/date')->gmtDate());
        }

        if (!$this->getLabel()) {
            Mage::throwException(df_helper()->cms()->__('Label for version is required field.'));
        }

        // We can not allow changing access level for some versions
        if ($this->getAccessLevel() != $this->getOrigData('access_level')) {
            if ($this->getOrigData('access_level') == Df_Cms_Model_Page_Version::ACCESS_LEVEL_PUBLIC) {
                $resource = $this->_getResource();
                /* @var $resource Df_Cms_Model_Mysql4_Page_Version */

                if ($resource->isVersionLastPublic($this)) {
                    Mage::throwException(
                        df_helper()->cms()->__('Cannot change version access level because it is last public version for its page.')
                    );
                }

//                if ($resource->isVersionHasPublishedRevision($this)) {
//                    Mage::throwException(
//                        df_helper()->cms()->__('Can not change version access level because its revision has been published.')
//                    );
//                }
            }
        }

        return parent::_beforeSave();
    }

    /**
     * Processing some data after version saved
     *
     * @return Df_Cms_Model_Page_Version
     */
    protected function _afterSave()
    {
        // If this was a new version we should create initial revision for it
        // from specified revision or from latest for parent version
        if ($this->getOrigData($this->getIdFieldName()) != $this->getId()) {
            $revision = Mage::getModel('df_cms/page_revision');

            // setting data for load
            $userId = $this->getUserId();
            $accessLevel = Mage::getSingleton('df_cms/config')->getAllowedAccessLevel();

            if ($this->getInitialRevisionData()) {
                $revision->setData($this->getInitialRevisionData());
            } else {
                $revision->loadWithRestrictions($accessLevel, $userId, $this->getOrigData($this->getIdFieldName()), 'version_id');
            }

            $revision->setVersionId($this->getId())
                ->setUserId($userId)
                ->save();

            $this->setLastRevision($revision);
        }
        return parent::_afterSave();
    }

    /**
     * Checking some moments before we can actually delete version
     *
     * @return Df_Cms_Model_Page_Version
     */
    protected function _beforeDelete()
    {
        $resource = $this->_getResource();
        /* @var $resource Df_Cms_Model_Mysql4_Page_Version */
        if ($this->isPublic()) {
            if ($resource->isVersionLastPublic($this)) {
                Mage::throwException(
                    df_helper()->cms()->__('Version "%s" could not be removed because it is last public version for its page.', $this->getLabel())
                );
            }
        }

        if ($resource->isVersionHasPublishedRevision($this)) {
            Mage::throwException(
                df_helper()->cms()->__('Version "%s" could not be removed because its revision has been published.', $this->getLabel())
            );
        }

        return parent::_beforeDelete();
    }

    /**
     * Removing unneeded data from increment table after version was removed.
     *
     * @param $observer
     * @return Df_Cms_Model_Observer
     */
    protected function _afterDelete()
    {
        Mage::getResourceSingleton('df_cms/increment')
            ->cleanIncrementRecord(Df_Cms_Model_Increment::TYPE_PAGE,
                $this->getId(),
                Df_Cms_Model_Increment::LEVEL_REVISION);

        return parent::_afterDelete();
    }

    /**
     * Check if this version public or not.
     *
     * @return bool
     */
    public function isPublic()
    {
        return $this->getAccessLevel() == Df_Cms_Model_Page_Version::ACCESS_LEVEL_PUBLIC;
    }

    /**
     * Loading version with extra access level checking.
     *
     * @param array|string $accessLevel
     * @param int $userId
     * @param int|string $value
     * @param string|null $field
     * @return Df_Cms_Model_Page_Version
     */
    public function loadWithRestrictions($accessLevel, $userId, $value, $field = null)
    {
        $this->_getResource()->loadWithRestrictions($this, $accessLevel, $userId, $value, $field = null);
        $this->_afterLoad();
        $this->setOrigData();
        return $this;
    }
}
