<?php

class Df_Cms_Model_ContentsMenu extends Df_Core_Model_Abstract {



	/**
	 * @return Df_Cms_Model_ContentsMenu_Applicator_Collection
	 */
	public function getApplicators () {

		if (!isset ($this->_applicators)) {

			/** @var Df_Cms_Model_ContentsMenu_Applicator_Collection $result  */
			$result =
				df_model (
					Df_Cms_Model_ContentsMenu_Applicator_Collection::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_Cms_Model_ContentsMenu_Applicator_Collection);

			$this->_applicators = $result;

		}


		df_assert ($this->_applicators instanceof Df_Cms_Model_ContentsMenu_Applicator_Collection);

		return $this->_applicators;

	}


	/**
	* @var Df_Cms_Model_ContentsMenu_Applicator_Collection
	*/
	private $_applicators;




	/**
	 * @return string
	 */
	public function getPosition () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__POSITION);

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return array
	 */
	public function getRootNodeIds () {

		if (!isset ($this->_rootNodeIds)) {

			/** @var array $result  */
			$result = array ();


			foreach ($this->getApplicators() as $applicator) {

				/** @var Df_Cms_Model_ContentsMenu_Applicator $applicator */
				df_assert ($applicator instanceof Df_Cms_Model_ContentsMenu_Applicator);


				/** @var Df_Cms_Model_Hierarchy_Node $node */
				$node = $applicator->getNode();

				df_assert ($node instanceof Df_Cms_Model_Hierarchy_Node);


				$result []= $node->getId();

			}


			df_assert_array ($result);

			$this->_rootNodeIds = $result;

		}


		df_result_array ($this->_rootNodeIds);

		return $this->_rootNodeIds;

	}


	/**
	* @var array
	*/
	private $_rootNodeIds;







	/**
	 * @return int
	 */
	public function getVerticalOrdering () {

		/** @var int $result  */
		$result = $this->cfg (self::PARAM__VERTICAL_ORDERING);

		df_result_integer ($result);

		return $result;

	}





	/**
	 * @return Df_Cms_Model_ContentsMenu
	 */
	public function insertIntoLayout () {

		if (!is_null ($this->getBlockParent())) {
			$this->getBlockParent()
				->insert (
					$this->getBlockMenu()
					,
					df_convert_null_to_empty_string (
						$this->getBlockSiblingName()
					)

				)
			;
		}

		return $this;

	}





	/**
	 * @param Df_Cms_Model_ContentsMenu $menu
	 * @return Df_Cms_Model_ContentsMenu
	 */
	public function merge (Df_Cms_Model_ContentsMenu $menu) {

		foreach ($menu->getApplicators() as $applicator) {

			/** @var Df_Cms_Model_ContentsMenu_Applicator $applicator */
			df_assert ($applicator instanceof Df_Cms_Model_ContentsMenu_Applicator);

			$this->getApplicators()->addItem ($applicator);

		}

		return $this;

	}




	
	
	
	/**
	 * @return Df_Cms_Block_Frontend_Menu_Contents
	 */
	private function getBlockMenu () {
	
		if (!isset ($this->_blockMenu)) {
	
			/** @var Df_Cms_Block_Frontend_Menu_Contents $result  */
			$result = 
				$this->getLayout()->createBlock (
					Df_Cms_Block_Frontend_Menu_Contents::getNameInMagentoFormat()
					,
					$this->getId()
					,
					array (
						Df_Cms_Block_Frontend_Menu_Contents::PARAM__MENU => $this
					)
				)
			;
	
	
			df_assert ($result instanceof Df_Cms_Block_Frontend_Menu_Contents);
	
			$this->_blockMenu = $result;
	
		}
	
	
		df_assert ($this->_blockMenu instanceof Df_Cms_Block_Frontend_Menu_Contents);
	
		return $this->_blockMenu;
	
	}
	
	
	/**
	* @var Df_Cms_Block_Frontend_Menu_Contents
	*/
	private $_blockMenu;	





	
	/**
	 * @return Mage_Core_Block_Abstract|null
	 */
	private function getBlockParent () {
	
		if (!isset ($this->_blockParent) && !$this->_blockParentIsNull) {
	
			/** @var Mage_Core_Block_Abstract|null $result  */
			$result = 
				$this->getLayout()->getBlock (
					$this->getPosition()
				)
			;

			if (false === $result) {
				$result = null;
			}
	
	
			if (!is_null ($result)) {
				df_assert ($result instanceof Mage_Core_Block_Abstract);
			}
			else {
				$this->_blockParentIsNull = true;
			}
	
			$this->_blockParent = $result;
	
		}
	
	
		if (!is_null ($this->_blockParent)) {
			df_assert ($this->_blockParent instanceof Mage_Core_Block_Abstract);
		}		
		
	
		return $this->_blockParent;
	
	}
	
	
	/**
	* @var Mage_Core_Block_Abstract|null
	*/
	private $_blockParent;	
	
	/**
	 * @var bool
	 */
	private $_blockParentIsNull = false;	
	







	/**
	 * @return string|null
	 */
	private function getBlockSiblingName () {

		if (!isset ($this->_blockSiblingName) && !$this->_blockSiblingNameIsNull) {

			/** @var string|null $result  */
			$result = null;

			if (!is_null ($this->getBlockParent())) {

				/** @var int $childrenCount */
				$childrenCount = count ($this->getBlockParent()->getSortedChildren());

				df_assert_integer ($childrenCount);


				/** @var int $insertionIndex */
				$insertionIndex =
					max (
						0
						,
						min (
							$childrenCount - 1
							,
							/**
							 * Вычитает единицу,
							 * потому что в административном интерфейсе
							 * нумерация начинается с 1
							 */
							$this->getVerticalOrdering() - 1
						)
					)
				;

				df_assert_integer ($insertionIndex);

				/** @var int $siblingIndex  */
				$siblingIndex =
					min (
						$childrenCount - 1
						,
						/**
						 * Вычитает единицу,
						 * потому что в административном интерфейсе
						 * нумерация начинается с 1
						 */
						$insertionIndex + 1
					)
				;

				df_assert_integer ($siblingIndex);


				/** @var string|null $result  */
				$result =
					df_a (
						$this->getBlockParent()->getSortedChildren()
						,
						$siblingIndex
					)
				;
			}

			if (!is_null ($result)) {
				df_assert_string ($result);
			}
			else {
				$this->_blockSiblingNameIsNull = true;
			}

			$this->_blockSiblingName = $result;

		}


		if (!is_null ($this->_blockSiblingName)) {
			df_result_string ($this->_blockSiblingName);
		}


		return $this->_blockSiblingName;

	}


	/**
	* @var string|null
	*/
	private $_blockSiblingName;

	/**
	 * @var bool
	 */
	private $_blockSiblingNameIsNull = false;







	/**
	 * @return Mage_Core_Model_Layout
	 */
	private function getLayout () {

		/** @var Mage_Core_Model_Layout $result  */
		$result = df_mage()->core()->layout();

		df_assert ($result instanceof Mage_Core_Model_Layout);

		return $result;

	}






	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__POSITION, new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__VERTICAL_ORDERING, new Df_Zf_Validate_Int()
			)
		;
	}







	const PARAM__POSITION = 'position';
	const PARAM__VERTICAL_ORDERING = 'vertical_ordering';



	const POSITION__CONTENT = 'content';
	const POSITION__LEFT = 'left';
	const POSITION__RIGHT = 'right';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Model_ContentsMenu';
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

