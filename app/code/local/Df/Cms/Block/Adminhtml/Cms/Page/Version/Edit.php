<?php



/**
 * Edit version page
 *
 * @category    Df
 * @package     Df_Cms
 *
 */
class Df_Cms_Block_Adminhtml_Cms_Page_Version_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected $_objectId   = 'version_id';
    protected $_blockGroup = 'df_cms';
    protected $_controller = 'adminhtml_cms_page_version';

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
        $version = Mage::registry('cms_page_version');

        $config = Mage::getSingleton('df_cms/config');
        /* @var $config Df_Cms_Model_Config */

        // Add 'new button' depending on permission
        if ($config->canCurrentUserSaveVersion()) {
            $this->_addButton('new', array(
                    'label'     => df_helper()->cms()->__('Save as New Version'),
                    'onclick'   => "editForm.submit('" . $this->getNewUrl() . "');",
                    'class'     => 'new',
                ));

            $this->_addButton('new_revision', array(
                    'label'     => df_helper()->cms()->__('New Revision...'),
                    'onclick'   => "setLocation('" . $this->getNewRevisionUrl() . "');",
                    'class'     => 'new',
                ));
        }

        $isOwner = $config->isCurrentUserOwner($version->getUserId());
        $isPublisher = $config->canCurrentUserPublishRevision();

        // Only owner can remove version if he has such permissions
        if (!$isOwner || !$config->canCurrentUserDeleteVersion()) {
            $this->removeButton('delete');
        }

        // Only owner and publisher can save version
        if (($isOwner || $isPublisher) && $config->canCurrentUserSaveVersion()) {
            $this->_addButton('saveandcontinue', array(
                'label'     => df_helper()->cms()->__('Save and Continue Edit'),
                'onclick'   => "editForm.submit($('edit_form').action+'back/edit/');",
                'class'     => 'save',
            ), 1);
        } else {
            $this->removeButton('save');
        }
    }

    /**
     * Retrieve text for header element depending
     * on loaded page and version
     *
     * @return string
     */
    public function getHeaderText()
    {
        $versionLabel = $this->htmlEscape(Mage::registry('cms_page_version')->getLabel());
        $title = $this->htmlEscape(Mage::registry('cms_page')->getTitle());

        if (!$versionLabel) {
            $versionLabel = df_helper()->cms()->__('N/A');
        }

        return df_helper()->cms()->__("Edit Page '%s' Version '%s'", $title, $versionLabel);
    }

    /**
     * Get URL for back button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/cms_page/edit',
             array(
                'page_id' => Mage::registry('cms_page')->getPageId(),
                'tab' => 'versions'
             ));
    }

    /**
     * Get URL for delete button
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', array('_current' => true));
    }

    /**
     * Get URL for new button
     *
     * @return string
     */
    public function getNewUrl()
    {
        return $this->getUrl('*/*/new', array('_current' => true));
    }

    /**
     * Get Url for new revision button
     *
     * @return string
     */
    public function getNewRevisionUrl()
    {
        return $this->getUrl('*/cms_page_revision/new', array('_current' => true));
    }
}
