<?php



/**
 * Tool block with buttons
 *
 * @category   Df
 * @package    Df_Cms
 *
 */
class Df_Cms_Block_Adminhtml_Cms_Page_Preview_Buttons extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * Adding two main buttons
     *
     * @return Df_Cms_Block_Adminhtml_Cms_Page_Preview_Buttons
     */
    public function __construct()
    {
        parent::__construct();

        $this->_addButton('preview', array(
                'id' => 'preview-buttons-preview',
                'label' => $this->__ ('Preview'),
                'class' => 'preview',
                'onclick' => 'preview()'
            ));

        if (Mage::getSingleton('df_cms/config')->canCurrentUserPublishRevision()) {
            $this->_addButton('publish', array(
                'id' => 'preview-buttons-publish',
                'label' => $this->__ ('Publish'),
                'class' => 'publish',
                'onclick' => 'publish()'
            ));
        }
    }

    /**
     * Override parent method to produce only button's html in result
     *
     * @return string
     */
    protected function _toHtml()
    {
        parent::_toHtml();
        return $this->getButtonsHtml();
    }
}
