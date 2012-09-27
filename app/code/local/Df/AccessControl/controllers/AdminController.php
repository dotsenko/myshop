<?php


class Df_AccessControl_AdminController extends Mage_Adminhtml_Controller_Action {



	public function categoriesAction() {

		try {

			/** @var int $roleId  */
			$roleId = intval ($this->getRequest()->getParam ('rid'));

			df_assert_integer ($roleId);



			/** @var int $categoryId  */
			$categoryId = $this->getRequest()->getParam ('category');

			df_assert_integer ($categoryId);



			/** @var Df_AccessControl_Block_Admin_Tab_Tree $treeBlock  */
			$treeBlock =
				$this->getLayout()
					->createBlock(
						Df_AccessControl_Block_Admin_Tab_Tree::getNameInMagentoFormat()
					)
			;

			df_assert ($treeBlock instanceof Df_AccessControl_Block_Admin_Tab_Tree);



			/** @var array $childrenNodes  */
			$childrenNodes = $treeBlock->getChildrenNodes ($categoryId, $roleId);

			df_assert_array ($childrenNodes);


			$this->getResponse()
				->setBody (
					Zend_Json::encode (
						$childrenNodes
					)
				)
			;
		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e, true);
		}

	}


}

