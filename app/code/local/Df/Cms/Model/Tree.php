<?php

class Df_Cms_Model_Tree extends Df_Core_Model_Abstract {



	/**
	 * @return Varien_Data_Tree
	 */
	public function getTree () {

		if (!isset ($this->_tree)) {

			/** @var Varien_Data_Tree $result  */
			$result = new Varien_Data_Tree ();

			df_assert ($result instanceof Varien_Data_Tree);


			$this->_nodesMap = array ();


			foreach ($this->getCmsNodes() as $cmsNode) {
				/** @var Df_Cms_Model_Hierarchy_Node $cmsNode */

				/** @var Df_Cms_Varien_Data_Tree_Node $varienNode */
				$varienNode = $this->convertCmsNodeToVarienDataTreeNode ($cmsNode, $result);

				if (0 === $cmsNode->getParentNodeId()) {
					/**
					 * Корневой узел
					 */
					$result
						->addNode (
							$varienNode
						)
					;

					$this->_nodesMap [intval ($cmsNode->getId())] = $varienNode;

				}

				else {

					/**
					 * Некорневой узел.
					 * Надо найти родителя данного узла, и связать данный узел с родителем.
					 * Обратите внимание, что благодаря вызову
					 * Df_Cms_Model_Mysql4_Hierarchy_Node_Collection::setTreeOrder
					 * родительский узел уже должен присутствовать в дереве.
					 */

					/** @var Df_Cms_Varien_Data_Tree_Node|null $parentNode  */
					$parentNode = $this->getParentForCmsNodeInVarienDataTree ($result, $cmsNode);


					if (!is_null ($parentNode)) {

						$parentNode->addChild ($varienNode);

						$this->_nodesMap [intval ($cmsNode->getId())] = $varienNode;

					}

				}

			}


			/**
			 * Дублируем детей в поле «children_nodes»,
			 * потому что это поле использует метод
			 * Mage_Catalog_Block_Navigation::_renderCategoryMenuItemHtml
			 * в случае, если Mage::helper('catalog/category_flat')->isEnabled()
			 */

			foreach ($result->getNodes() as $node) {
				/** @var Df_Cms_Varien_Data_Tree_Node $node */
				df_assert ($node instanceof Df_Cms_Varien_Data_Tree_Node);

				/** @var Varien_Data_Tree_Node_Collection $children */
				$children = $node->getChildren();

				$node->setData ('children_nodes', $children->getNodes());

			}



			$this->_tree = $result;

		}

		df_assert ($this->_tree instanceof Varien_Data_Tree);

		return $this->_tree;

	}


	/**
	* @var Varien_Data_Tree
	*/
	private $_tree;

	/**
	 * @var Df_Cms_Varien_Data_Tree_Node[]
	 */
	private $_nodesMap = array ();





	/**
	 * @param Df_Cms_Model_Hierarchy_Node $cmsNode
	 * @param Varien_Data_Tree $tree
	 * @return Df_Cms_Varien_Data_Tree_Node
	 */
	private function convertCmsNodeToVarienDataTreeNode (
		Df_Cms_Model_Hierarchy_Node $cmsNode
		,
		Varien_Data_Tree $tree
	) {

		/** @var bool $isMagentoGE17 */
		$isMagentoGE17 = df_magento_version ('1.7', '>=');

		/** @var Df_Cms_Varien_Data_Tree_Node $result  */
		$result =
			new Df_Cms_Varien_Data_Tree_Node (
				array (
					'name' => $cmsNode->getLabel()

					,
					/**
					 * Обратите внимание, что Magento 1.7 RC1 трактует флаг is_active иначе,
					 * чем предыдущие версии.
					 * В предыдущих версиях is_active означает, что рубрика подлежит публикации.
					 * В Magento 1.7 is_active означает, что рубрика является текущей
					 */
					'is_active' => !$isMagentoGE17

					,
					'id' => 'df-cms-' . $cmsNode->getId()

					,
					'url' =>
							is_null ($cmsNode->getPageIdentifier())
						?
							'javascript:void(0);'
						:
							$cmsNode->getUrl ()
					,

					/**
					 * привязываем узел CMS к созданному на его основе узлу Varien_Data_Tree_Node
					 */
					'cms_node' => $cmsNode
				)
				,
				'id'

				,
				$tree
			)
		;

		df_assert ($result instanceof Df_Cms_Varien_Data_Tree_Node);

		return $result;

	}






	/**
	 * @return Df_Cms_Model_Mysql4_Hierarchy_Node_Collection
	 */
	private function getCmsNodes () {

		if (!isset ($this->_cmsNodes)) {

			/** @var Df_Cms_Model_Mysql4_Hierarchy_Node_Collection $result  */
			$result =
				Mage::getResourceModel (
					Df_Cms_Model_Mysql4_Hierarchy_Node_Collection::CLASS_MF
				)
			;

			df_assert ($result instanceof Df_Cms_Model_Mysql4_Hierarchy_Node_Collection);


			$result
				->addStoreFilter (
					Mage::app()->getStore()
					,
					false
				)
				->joinCmsPage()
				->joinMetaData()
				->filterExcludedPagesOut()
				->filterUnpublishedPagesOut()
				->setTreeOrder()
			;


			$this->_cmsNodes = $result;

		}


		df_assert ($this->_cmsNodes instanceof Df_Cms_Model_Mysql4_Hierarchy_Node_Collection);

		return $this->_cmsNodes;

	}


	/**
	* @var Df_Cms_Model_Mysql4_Hierarchy_Node_Collection
	*/
	private $_cmsNodes;






	/**
	 * @param Varien_Data_Tree $tree
	 * @param Df_Cms_Model_Hierarchy_Node $cmsNode
	 * @return Df_Cms_Varien_Data_Tree_Node|null
	 */
	private function getParentForCmsNodeInVarienDataTree (
		Varien_Data_Tree $tree
		,
		Df_Cms_Model_Hierarchy_Node $cmsNode
	) {

		/** @var Df_Cms_Varien_Data_Tree_Node|null $result  */
		$result =
			df_a (
				$this->_nodesMap
				,
				$cmsNode->getParentNodeId()
			)
		;

		/**
		 * Результат может быть равен null,
		 * если родительская рубрика по каким-то причинам
		 * не должна отображаться в меню (например, если так указано в настройках рубрики).
		 */

		if (!is_null ($result)) {
			df_assert ($result instanceof Df_Cms_Varien_Data_Tree_Node);
		}

		return $result;

	}








	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Model_Tree';
	}


	/**
	 * Например, для класса Df_SalesRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {

		/** @var string $result */
		static $result;

		if (!isset ($result)) {
			$result = df()->reflection()->getModelNameInMagentoFormat (self::getClass());
		}

		return $result;
	}


}


