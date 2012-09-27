<?php


/**
 * Позволяет другим модулям добавлять свои пункты в товарное меню
 */
class Df_Catalog_Block_Navigation extends Mage_Catalog_Block_Navigation {


    /**
     * @return string
     */
    public function __ () {

		/** @var array $args  */
        $args = func_get_args();

		df_assert_array ($args);


		/** @var string $result  */
        $result =
			df_helper()->localization()->translation()->translateByParent ($args, $this)
		;

		df_result_string ($result);


	    return $result;
    }











    /**
     * Get catagories of current store
     *
     * @return Varien_Data_Tree_Node_Collection|Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection|Varien_Data_Collection|array
     */
    public function getStoreCategories()
    {
		/** @var Varien_Data_Tree_Node_Collection|Mage_Catalog_Model_Resource_Category_Collection|Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection|Varien_Data_Collection|array $result */
        $result = parent::getStoreCategories();

		df_assert (
				is_array ($result)
			||
				($result instanceof Varien_Data_Tree_Node_Collection)
			||
				df_helper()->catalog()->check()->categoryCollection ($result)
			||
				($result instanceof Varien_Data_Collection)
		)
		;


		Mage
			::dispatchEvent (
				'rm_menu_top_add_submenu'
				,
				array (
					 'menu' => $this->getAdditionalRoot ()
				)
			)
		;


		foreach ($this->getAdditionalRoot ()->getNodes () as $node) {
			/** @var Varien_Data_Tree_Node $node */

			if ($result instanceof Varien_Data_Tree_Node_Collection) {
				$result->add ($node);
			}
			else if (is_array ($result)) {
				$result[]= $node;
			}
		}



		df_assert (
				is_array ($result)
			||
				($result instanceof Varien_Data_Tree_Node_Collection)
			||
				df_helper()->catalog()->check()->categoryCollection ($result)
			||
				($result instanceof Varien_Data_Collection)
		)
		;

		return $result;
    }





	/**
	 * @return Varien_Data_Tree
	 */
	protected function getAdditionalRoot () {
		if (!isset ($this->_additionalRoot)) {

			$this->_additionalRoot =
				new Varien_Data_Tree ()
			;

			df_assert ($this->_additionalRoot instanceof Varien_Data_Tree);

		}
		return $this->_additionalRoot;
	}



	/**
	 * @var Varien_Data_Tree
	 */
	private $_additionalRoot;

}