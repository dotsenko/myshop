<?php

class Df_Cms_Varien_Data_Tree_Node extends Varien_Data_Tree_Node {


	/**
	 * @return Df_Cms_Model_Hierarchy_Node
	 */
	public function getCmsNode () {

		/** @var Df_Cms_Model_Hierarchy_Node $result  */
		$result =
			$this->_getData (self::PARAM__CMS_NODE)
		;

		df_assert ($result instanceof Df_Cms_Model_Hierarchy_Node);

		return $result;

	}



	const PARAM__CMS_NODE = 'cms_node';


}


