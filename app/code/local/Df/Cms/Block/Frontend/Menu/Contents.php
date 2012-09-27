<?php

class Df_Cms_Block_Frontend_Menu_Contents extends Mage_Core_Block_Abstract {



	/**
	 * @return string
	 */
	protected function _toHtml () {

		/** @var string $result  */
		$result = Df_Core_Const::T_EMPTY;

		if (
				df_cfg()->cms()->hierarchy()->isEnabled()
			&&
				df_enabled (Df_Core_Feature::CMS_2)
			&&
				count ($this->getMenu()->getApplicators())
		) {

			/** @var array $renderedNodes  */
			$renderedNodes = array ();


			foreach (df_helper()->cms()->getTree()->getTree()->getNodes() as $node) {

				/** @var Df_Cms_Varien_Data_Tree_Node $node */
				df_assert ($node instanceof Df_Cms_Varien_Data_Tree_Node);


				/** @var Df_Cms_Model_Hierarchy_Node $cmsNode  */
				$cmsNode = $node->getCmsNode();

				df_assert ($cmsNode instanceof Df_Cms_Model_Hierarchy_Node);


				if (
						(0 === $node->getCmsNode()->getParentNodeId())
					&&
						in_array ($cmsNode->getId(), $this->getMenu()->getRootNodeIds())
				) {

					$renderedNodes []=
						$this->createHtmlListItem (
							$this->renderNode ($node)
							,
							df_clean (
								array (
									'class' => count ($node->getChildren()) ? 'parent' : null
								)
							)

						)
					;

				}
			}


			if (count ($renderedNodes)) {

				$result =
					Df_Core_Model_Format_Html_Tag::output (
						$this->createHtmlList (
							implode (
								Df_Core_Const::T_EMPTY
								,
								$renderedNodes
							)
							,
							array (
								'class' => 'cms-menu'
							)
						)
						,
						'div'
						,
						array (
							'class' => 'df-cms-menu-wrapper'
						)
					)
				;

			}

		}

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return Df_Cms_Model_ContentsMenu
	 */
	public function getMenu () {

		/** @var Df_Cms_Model_ContentsMenu $result  */
		$result = $this->_getData (self::PARAM__MENU);

		df_assert ($result instanceof Df_Cms_Model_ContentsMenu);

		return $result;

	}





	/**
	 * @param string $content
	 * @param array $attributes [optional]
	 * @return string
	 */
	private function createHtmlList ($content, array $attributes = array ()) {

		df_param_string ($content, 0);

		/** @var string $result  */
		$result =
			Df_Core_Model_Format_Html_Tag::output (
				$content
				,
				'ul'
				,
				$attributes
			)
		;


		df_result_string ($result);

		return $result;

	}





	/**
	 * @param string $content
	 * @param array $attributes [optional]
	 * @return string
	 */
	private function createHtmlListItem ($content, array $attributes = array ()) {

		df_param_string ($content, 0);

		/** @var string $result  */
		$result =
			Df_Core_Model_Format_Html_Tag::output (
				$content
				,
				'li'
				,
				$attributes
			)
		;


		df_result_string ($result);

		return $result;

	}




	/**
	 * @param Df_Cms_Varien_Data_Tree_Node $parent
	 * @return string
	 */
	private function renderChildren (Df_Cms_Varien_Data_Tree_Node $parent) {

		/** @var array $renderedNodes  */
		$renderedNodes = array ();


		foreach ($parent->getChildren() as $childNode) {

			/** @var Df_Cms_Varien_Data_Tree_Node $childNode */
			df_assert ($childNode instanceof Df_Cms_Varien_Data_Tree_Node);

			$renderedNodes []=
				$this->createHtmlListItem (
					$this->renderNode ($childNode)
					,
					df_clean (
						array (
							'class' => 0 < $childNode->getChildren()->count() ? 'parent' : null
						)
					)

				)
			;
		}


		$result =
				!count ($renderedNodes)
			?
				Df_Core_Const::T_EMPTY
			:
				$this->createHtmlList (
					implode (
						Df_Core_Const::T_EMPTY
						,
						$renderedNodes
					)
				)
		;


		df_result_string ($result);

		return $result;

	}






	/**
	 * @param Df_Cms_Varien_Data_Tree_Node $node
	 * @return string
	 */
	private function renderLabel (Df_Cms_Varien_Data_Tree_Node $node) {

		/** @var Df_Cms_Model_Hierarchy_Node $cmsNode  */
		$cmsNode = $node->getCmsNode();

		df_assert ($cmsNode instanceof Df_Cms_Model_Hierarchy_Node);

		/** @var string $result  */
		$result =
				(
						!is_null (
							df_helper()->cms()->getCurrentNode()
						)
					&&
						(
								$cmsNode->getId ()
							===
								df_helper()->cms()->getCurrentNode()->getId()
						)
				)
			?
				Df_Core_Model_Format_Html_Tag::output (
					Df_Core_Model_Format_Html_Tag::output (
						$cmsNode->getLabel()
						,
						'strong'
					)
					,
					'span'
				)
			:
				Df_Core_Model_Format_Html_Tag::output (
					$cmsNode->getLabel()
					,
					'a'
					,
					array (
						'href' => $cmsNode->getUrl()
					)
				)
		;


		df_result_string ($result);

		return $result;

	}







	/**
	 * @param Df_Cms_Varien_Data_Tree_Node $node
	 * @return string
	 */
	private function renderNode (Df_Cms_Varien_Data_Tree_Node $node) {

		/** @var string $result  */
		$result =
			implode (
				Df_Core_Const::T_EMPTY
				,
				array (
					$this->renderLabel ($node)
					,
					$this->renderChildren ($node)
				)
			)
		;

		df_result_string ($result);

		return $result;

	}




	const PARAM__MENU = 'menu';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Block_Frontend_Menu_Contents';
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

