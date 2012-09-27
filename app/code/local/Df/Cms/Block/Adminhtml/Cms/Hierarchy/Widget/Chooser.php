<?php



/**
 * Cms Pages Hierarchy Grid Block
 *
 * @category   Df
 * @package    Df_Cms
 */
class Df_Cms_Block_Adminhtml_Cms_Hierarchy_Widget_Chooser extends Mage_Adminhtml_Block_Template
{
    /**
     * Prepare chooser element HTML
     *
     * @param Varien_Data_Form_Element_Abstract $element Form Element
     * @return Varien_Data_Form_Element_Abstract
     */
    public function prepareElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $uniqId = df_mage()->coreHelper()->uniqHash($element->getId());
        $sourceUrl = $this->getUrl('*/cms_hierarchy_widget/chooser', array('uniq_id' => $uniqId));

        $chooser = $this->getLayout()->createBlock('widget/adminhtml_widget_chooser')
            ->setElement($element)
            ->setTranslationHelper($this->getTranslationHelper())
            ->setConfig($this->getConfig())
            ->setFieldsetId($this->getFieldsetId())
            ->setSourceUrl($sourceUrl)
            ->setUniqId($uniqId);


        if ($element->getValue()) {
            $node = Mage::getModel('df_cms/hierarchy_node')->load($element->getValue());
            if ($node->getId()) {
                $chooser->setLabel($node->getLabel());
            }
        }

        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * Return JS+HTML to initialize tree
     *
     * @return string
     */
    public function getTreeHtml()
    {
        $chooserJsObject = $this->getId();
        $html = '
            <div id="tree'.$this->getId().'" class="cms-tree tree x-tree"></div>
            <script type="text/javascript">

            function clickNode(node) {
                $("tree-container").insert({before: node.text});
                $("'.$this->getId().'").value = node.id;
                treeRoot.collapse();
            }

            var nodes = '.$this->getNodesJson().';

            if (nodes.length > 0) {

                tree'.$this->getId().' = new Ext.tree.TreePanel("tree'.$this->getId().'", {
                    animate: false,
                    enableDD: false,
                    containerScroll: true,
                    rootVisible: false,
                    lines: true
                });

                treeRoot'.$this->getId().' = new Ext.tree.AsyncTreeNode({
                    text: "'. $this->__("Root") .'",
                    id: "root",
                    allowDrop: true,
                    allowDrag: false,
                    expanded: true,
                    cls: "cms_node_root",
                });
                tree'.$this->getId().'.setRootNode(treeRoot'.$this->getId().');

                for (var i = 0; i < nodes.length; i++) {
                    var cls = nodes[i].page_id ? "cms_page" : "cms_node";
                    var node = new Ext.tree.TreeNode({
                        id: nodes[i].node_id,
                        text: nodes[i].label,
                        cls: cls,
                        expanded: nodes[i].page_exists,
                        allowDrop: false,
                        allowDrag: false,
                        page_id: nodes[i].page_id,
                    });
                    if (parentNode = tree'.$this->getId().'.getNodeById(nodes[i].parent_node_id)) {
                        parentNode.appendChild(node);
                    } else {
                        treeRoot'.$this->getId().'.appendChild(node);
                    }
                }

                tree'.$this->getId().'.addListener("click", function (node, event) {
                    '.$chooserJsObject.'.setElementValue(node.id);
                    '.$chooserJsObject.'.setElementLabel(node.text);
                    '.$chooserJsObject.'.close();
                });
                tree'.$this->getId().'.render();
                treeRoot'.$this->getId().'.expand();
            }
            else {
                $("tree'.$this->getId().'").innerHTML = "'.$this->__('No Nodes available').'";
            }
            </script>
        ';
        return $html;
    }

    /**
     * Retrieve Hierarchy JSON string
     *
     * @return string
     */
    public function getNodesJson()
    {
        return df_mage()->coreHelper()->jsonEncode($this->getNodes());
    }

    /**
     * Prepare hierarchy nodes for tree building
     *
     * @return array
     */
    public function getNodes()
    {
        $nodes = array();
        $collection = Mage::getModel('df_cms/hierarchy_node')->getCollection()
            ->joinCmsPage()
            ->setTreeOrder();

        foreach ($collection as $item) {
            /* @var $item Df_Cms_Model_Hierarchy_Node */
            $node = array(
                'node_id'               => $item->getId(),
                'parent_node_id'        => $item->getParentNodeId(),
                'label'                 => $item->getLabel(),
                'page_exists'           => (bool)$item->getPageExists(),
                'page_id'               => $item->getPageId(),
            );
            $nodes[] = $node;
        }
        return $nodes;
    }
}
