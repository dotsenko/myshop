<?php



/**
 * Cms Hierarchy Node Widget Block
 *
 * @category   Df
 * @package    Df_Cms
 */
class Df_Cms_Block_Widget_Node
    extends Mage_Core_Block_Html_Link
    implements Mage_Widget_Block_Interface
{
    /**
     * Current Hierarchy Node Page Instance
     *
     * @var Df_Cms_Model_Hierarchy_Node
     */
    protected $_node;

    /**
     * Retrieve specified anchor text
     *
     * @return string
     */
    public function getAnchorText()
    {
        if ($this->getData('anchor_text')) {
            return $this->getData('anchor_text');
        }
        return $this->_node->getLabel();
    }

    /**
     * Retrieve link specified title
     *
     * @return string
     */
    public function getTitle()
    {
        if ($this->getData('title')) {
            return $this->getData('title');
        }
        return $this->_node->getLabel();
    }

    /**
     * Retrieve Node URL
     *
     * @return string
     */
    public function getHref()
    {
        return $this->_node->getUrl();
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->getNodeId()) {
            $this->_node = Mage::getModel('df_cms/hierarchy_node')
                ->load($this->getNodeId());
        } else {
            $this->_node = Mage::registry('current_cms_hierarchy_node');
        }

        if (!$this->_node) {
            return '';
        }

        return parent::_toHtml();
    }
}
