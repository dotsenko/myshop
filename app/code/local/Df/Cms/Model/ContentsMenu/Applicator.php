<?php

class Df_Cms_Model_ContentsMenu_Applicator extends Df_Core_Model_Abstract {


	
	
	/**
	 * @return Df_Cms_Model_Hierarchy_Node
	 */
	public function getNode () {

		/** @var Df_Cms_Model_Hierarchy_Node $result  */
		$result =
			$this->cfg (self::PARAM__NODE)
		;

		df_assert ($result instanceof Df_Cms_Model_Hierarchy_Node);

		return $result;

	}





	/**
	 * @return string
	 */
	public function getPosition () {
	
		if (!isset ($this->_position)) {
	
			/** @var string $result  */
			$result = 
				$this->getNodeMenuParam ('position');
			;

			df_assert_string ($result);
	
			$this->_position = $result;
	
		}
	
	
		df_result_string ($this->_position);
	
		return $this->_position;
	
	}
	
	
	/**
	* @var string
	*/
	private $_position;






	/**
	 * @return int
	 */
	public function getVerticalOrdering () {

		if (!isset ($this->_verticalOrdering)) {

			/** @var int $result  */
			$result =
				intval (
					$this->getNodeMenuParam ('vertical_ordering')
				)
			;

			df_assert_integer ($result);

			$this->_verticalOrdering = $result;

		}


		df_result_integer ($this->_verticalOrdering);

		return $this->_verticalOrdering;

	}


	/**
	* @var int
	*/
	private $_verticalOrdering;





	/**
	 * @return bool
	 */
	public function isApplicableToTheCurrentPage () {

		if (!isset ($this->_applicableToTheCurrentPage)) {

			/** @var bool $result  */
			$result =
					df_enabled (Df_Core_Feature::CMS_2)
				&&
					df_cfg()->cms()->versioning()->isEnabled()
				&&
					df_parse_boolean (
						$this->getNodeMenuParam ('enabled')
					)
			;


			df_assert_boolean ($result);

			$this->_applicableToTheCurrentPage = $result;

		}


		df_result_boolean ($this->_applicableToTheCurrentPage);

		return $this->_applicableToTheCurrentPage;

	}


	/**
	* @var bool
	*/
	private $_applicableToTheCurrentPage;



	
	
	
	
	/**
	 * @param string $paramName
	 * @return mixed
	 */
	private function getNodeMenuParam ($paramName) {

		df_param_string ($paramName, 0);
	
		if (!isset ($this->_nodeMenuParam [$paramName])) {
	
			/** @var mixed $result  */
			$result =
				$this->getNode()->getData (
					Df_Cms_Model_Hierarchy_Node::getMetadataKeyForPageType (
						$this->getPageType()
						,
						$paramName
					)
				)
			;

			$this->_nodeMenuParam [$paramName] = $result;
	
		}
	
		return $this->_nodeMenuParam [$paramName];
	
	}
	
	
	/**
	* @var mixed[]
	*/
	private $_nodeMenuParam = array ();


	
	
	
	
	/**
	 * Обратите внимание, что определение этого метода
	 * на уровне класса Df_Cms_Model_ContentsMenu_Applicator
	 * оправдывается тем, что для идентификации типов страниц CMS_FOREIGN / CMS_OWN
	 * требуется информация о конкретной рубрике.
	 *
	 * @return string
	 */
	private function getPageType () {
	
		if (!isset ($this->_pageType)) {
	
			/** @var string $result  */
			$result = Df_Cms_Model_ContentsMenu_PageType::OTHER;

			foreach ($this->getPageTypeMap() as $type => $handle) {

				/** @var string $type */
				/** @var string $handle */

				df_assert_string ($type);
				df_assert_string ($handle);

				if (df_handle_presents($handle)) {
					$result = $type;
					break;
				}
			}


			if (Df_Cms_Model_ContentsMenu_PageType::OTHER === $result) {

				if (
						df_handle_presents('cms_page')
					&&
						!is_null (df_helper()->cms()->getCurrentNode())
				) {

					/**
					 * Самодельная страница.
					 * Надо определить: входит ли данная страница в текущее меню.
					 * Сделать это просто: у страницы есть свойство xpath,
					 * которое хранит информацию о всех её предках.
					 */

					$result =
							df_helper()->cms()->getCurrentNode()->isBelongTo (
								$this->getNode()->getId ()
							)
						?
							Df_Cms_Model_ContentsMenu_PageType::CMS_OWN
						:
							Df_Cms_Model_ContentsMenu_PageType::CMS_FOREIGN
					;

				}

			}

	
			df_assert_string ($result);
	
			$this->_pageType = $result;
	
		}
	
	
		df_result_string ($this->_pageType);
	
		return $this->_pageType;
	
	}
	
	
	/**
	* @var string
	*/
	private $_pageType;	






	/**
	 * @return array
	 */
	private function getPageTypeMap () {

		/** @var array $result  */
		$result =
			array (
 				Df_Cms_Model_ContentsMenu_PageType::ACCOUNT => 'customer_account'
				,
				Df_Cms_Model_ContentsMenu_PageType::CATALOG_PRODUCT_LIST => 'catalog_category_view'
				,
				Df_Cms_Model_ContentsMenu_PageType::CATALOG_PRODUCT_VIEW => 'catalog_product_view'
				,
				Df_Cms_Model_ContentsMenu_PageType::FRONT => 'cms_index_index'
			)
		;


		df_result_array ($result);

		return $result;

	}
	


	
	


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__NODE, Df_Cms_Model_Hierarchy_Node::getClass()
			)
		;
	}



	const PARAM__NODE = 'node';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Cms_Model_ContentsMenu_Applicator';
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

