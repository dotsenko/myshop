<?php


/**
 * Base helper
 *
 * @category   Df
 * @package    Df_Cms
 */

class Df_Cms_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	
	
	/**
	 * @return Df_Cms_Model_Hierarchy_Node|null
	 */
	public function getCurrentNode () {
	
		if (!isset ($this->_currentNode) && !$this->_currentNodeIsNull) {
	
			/** @var Df_Cms_Model_Hierarchy_Node|null $result  */
			$result = 
				Mage::registry('current_cms_hierarchy_node');
			;
	
			if (!is_null ($result)) {
				df_assert ($result instanceof Df_Cms_Model_Hierarchy_Node);
			}
			else {
				$this->_currentNodeIsNull = true;
			}
	
			$this->_currentNode = $result;
	
		}
	
	
		if (!is_null ($this->_currentNode)) {
			df_assert ($this->_currentNode instanceof Df_Cms_Model_Hierarchy_Node);
		}		
		
	
		return $this->_currentNode;
	
	}
	
	
	/**
	* @var Df_Cms_Model_Hierarchy_Node|null
	*/
	private $_currentNode;	
	
	/**
	 * @var bool
	 */
	private $_currentNodeIsNull = false;		
	
	
	
	
	
	


	/**
	 * @return Mage_Cms_Model_Page|null
	 */
	public function getCurrentPage () {
	
		if (!isset ($this->_currentPage) && !$this->_currentPageIsNull) {
	
			/** @var Mage_Cms_Model_Page|null $result  */
			$result = 
				Mage::registry('cms_page');
			;
	
			if (!is_null ($result)) {
				df_assert ($result instanceof Mage_Cms_Model_Page);
			}
			else {
				$this->_currentPageIsNull = true;
			}
	
			$this->_currentPage = $result;
	
		}
	
	
		if (!is_null ($this->_currentPage)) {
			df_assert ($this->_currentPage instanceof Mage_Cms_Model_Page);
		}		
		
	
		return $this->_currentPage;
	
	}
	
	
	/**
	* @var Mage_Cms_Model_Page|null
	*/
	private $_currentPage;	
	
	/**
	 * @var bool
	 */
	private $_currentPageIsNull = false;	





	/**
	 * @return Df_Cms_Model_Tree
	 */
	public function getTree () {

		if (!isset ($this->_tree)) {

			/** @var Df_Cms_Model_Tree $result  */
			$result =
				df_model (
					Df_Cms_Model_Tree::getNameInMagentoFormat()
				)
			;


			df_assert ($result instanceof Df_Cms_Model_Tree);

			$this->_tree = $result;

		}


		df_assert ($this->_tree instanceof Df_Cms_Model_Tree);

		return $this->_tree;

	}


	/**
	* @var Df_Cms_Model_Tree
	*/
	private $_tree;








    /**
     * Array of admin users in system
     * @var array
     */
    protected $_usersHash = null;

    /**
     * Retrieve array of admin users in system
     *
     * @return array
     */
    public function getUsersArray($addEmptyUser = false)
    {
        if (!$this->_usersHash) {
            $collection = Mage::getModel('admin/user')->getCollection();
            $this->_usersHash = array();

            if ($addEmptyUser) {
                $this->_usersHash[''] = '';
            }

            foreach ($collection as $user) {
                $this->_usersHash[$user->getId()] = $user->getUsername();
            }
        }

        return $this->_usersHash;
    }

    /**
     * Get version's access levels with labels.
     *
     * @return array
     */
    public function getVersionAccessLevels()
    {
        return array(
            Df_Cms_Model_Page_Version::ACCESS_LEVEL_PRIVATE => $this->__('Private'),
            Df_Cms_Model_Page_Version::ACCESS_LEVEL_PROTECTED => $this->__('Protected'),
            Df_Cms_Model_Page_Version::ACCESS_LEVEL_PUBLIC => $this->__('Public')
        );
    }

    /**
     * Recursively walk through container (form or fieldset)
     * and add to each element new onChange method.
     * Element will be skipped if its type passed in $excludeTypes parameter.
     *
     * @param Varien_Data_Form_Abstract $container
     * @param string $onChange
     * @param string|array $excludeTypes
     */
    public function addOnChangeToFormElements($container, $onChange, $excludeTypes = array('hidden'))
    {
        if (!is_array($excludeTypes)) {
            $excludeTypes = array($excludeTypes);
        }

        foreach ($container->getElements()as $element) {
            if ($element->getType() == 'fieldset') {
                $this->addOnChangeToFormElements($element, $onChange, $excludeTypes);
            } else {
                if (!in_array($element->getType(), $excludeTypes)) {
                    if ($element->hasOnchange()) {
                        $onChangeBefore = $element->getOnchange() . ';';
                    } else {
                        $onChangeBefore = '';
                    }
                    $element->setOnchange($onChangeBefore . $onChange);
                }
            }
        }
    }



    /**
	 * @param array $args
     * @return string
     */
    public function translateByParent (array $args) {

		/** @var string $result  */
        $result =
			df_helper()->localization()->translation()->translateByModule (
				$args, self::DF_PARENT_MODULE
			)
		;

		df_result_string ($result);

	    return $result;
    }



	const DF_PARENT_MODULE = 'Mage_Cms';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Helper_Data';
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
