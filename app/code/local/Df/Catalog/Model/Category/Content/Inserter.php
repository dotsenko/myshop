<?php


/**
 * Объект данного класса способен добавлять новые блоки на страницу товарного раздела
 * без каких-либо правок, перкрытий и наследований системных файлов
 */
class Df_Catalog_Model_Category_Content_Inserter extends Df_Core_Model_Abstract {



	/**
	 * @return bool
	 */
	public function insert () {

		$result = false;

		if ($this->getTransport ()) {

			if (!df_empty ($this->getMethodOfUpdate())) {
				call_user_func (
					array ($this, $this->getMethodOfUpdate())
					,
					$this->getNewContent ()
				)
				;

				$result = true;
			}
		}

		return $result;
	}





	/**
	 * @var string|null
	 */
	private $_methodOfUpdate;



	/**
	 * @return string|null
	 */
	private function getMethodOfUpdate () {
		if (!isset ($this->_methodOfUpdate)) {

			$this->_methodOfUpdate = NULL;

			$position = df_cfg ()->catalog()->navigation()->getPosition();
			/** @var string $position */

			if ($this->isItProductListBlock ()) {

				switch ($position) {
					case Df_Catalog_Model_System_Config_Source_Category_Content_Position::DF_AFTER_PRODUCTS:
						$this->_methodOfUpdate = self::DF_METHOD_APPEND;
						break;
					case Df_Catalog_Model_System_Config_Source_Category_Content_Position::DF_BEFORE_PRODUCTS:
						$this->_methodOfUpdate = self::DF_METHOD_PREPEND;
						break;
					case Df_Catalog_Model_System_Config_Source_Category_Content_Position::DF_BEFORE_STATIC_BLOCK:
						$this->_methodOfUpdate = null;
						break;
					case Df_Catalog_Model_System_Config_Source_Category_Content_Position::DF_BEFORE_AND_AFTER_PRODUCTS:
						$this->_methodOfUpdate = self::DF_METHOD_APPEND_AND_PREPEND;
						break;
				}

			}
			else if ($this->isItCategoryStaticBlock()) {

				switch ($position) {
					case Df_Catalog_Model_System_Config_Source_Category_Content_Position::DF_AFTER_PRODUCTS:
						$this->_methodOfUpdate = null;
						break;
					case Df_Catalog_Model_System_Config_Source_Category_Content_Position::DF_BEFORE_PRODUCTS:
						$this->_methodOfUpdate = self::DF_METHOD_APPEND;
						break;
					case Df_Catalog_Model_System_Config_Source_Category_Content_Position::DF_BEFORE_STATIC_BLOCK:
						$this->_methodOfUpdate = self::DF_METHOD_PREPEND;
						break;
					case Df_Catalog_Model_System_Config_Source_Category_Content_Position::DF_BEFORE_AND_AFTER_PRODUCTS:
						$this->_methodOfUpdate = null;
						break;
				}
			}
		}
		return $this->_methodOfUpdate;
	}



	/**
	 * @return bool
	 */
	private function isItProductListBlock () {
		return 'product_list' === $this->getBlock ()->getNameInLayout();
	}



	/**
	 * @return bool
	 */
	private function isItCategoryStaticBlock () {
		$result = false;
		if ($this->getBlock () instanceof Mage_Cms_Block_Block) {
			if ($this->getCurrentCategory ()) {

				if (
						(NULL !== $this->getLandingPageId ())
					&&
						(NULL !== $this->getBlockId ())
					&&
						($this->getBlockId () == $this->getLandingPageId ())
				) {
					$result = true;
				}

			}
		}
		return $result;
	}



	/**
	 * @param  string $content
	 * @return Df_Catalog_Model_Category_Content_Inserter
	 */
	public function prependContent ($content) {
		$this
			->setContentByArray (
				array (
					$content
					,
					$this->getContent()
				)
			)
		;
		return $this;
	}



	/**
	 * @param  string $content
	 * @return Df_Catalog_Model_Category_Content_Inserter
	 */
	public function appendAndPrependContent ($content) {
		$this
			->setContentByArray (
				array (
					$content
					,
					$this->getContent()
					,
					$content
				)
			)
		;
		return $this;
	}


	/**
	 * @param  string $content
	 * @return Df_Catalog_Model_Category_Content_Inserter
	 */
	public function appendContent ($content) {
		$this
			->setContentByArray (
				array (
					$this->getContent()
					,
					$content
				)
			)
		;
		return $this;
	}



	/**
	 * @param  array $content
	 * @return Df_Catalog_Model_Category_Content_Inserter
	 */
	private function setContentByArray (array $content) {
		$this
			->setContent (
				implode (
					Df_Core_Const::T_EMPTY
					,
					$content
				)
			)
		;
		return $this;
	}



	/**
	 * @param  string $content
	 * @return Df_Catalog_Model_Category_Content_Inserter
	 */
	private function setContent ($content) {
		$this->getTransport()->setData(self::DF_CONTENT_KEY, $content);
		return $this;
	}



	/**
	 * @return string
	 */
	private function getContent () {
		return $this->getTransport()->getData(self::DF_CONTENT_KEY);
	}
	

	
	/**
	 * @return Mage_Catalog_Model_Category|null
	 */
	private function getCurrentCategory () {
		return Mage::registry ('current_category');
	}
	
	
	
	/**
	 * @return int|null
	 */
	private function getLandingPageId () {
		return 
				!$this->getCurrentCategory () 
			? 
				NULL 
			: 
				$this->getCurrentCategory ()->getData ('landing_page')
			;
	}	
	
	
	
	/**
	 * @return int|null
	 */
	private function getBlockId () {
		return $this->getBlock ()->getData ('block_id');
	}	





	/**
	 * @return Mage_Core_Block_Abstract
	 */
	private function getBlock () {
		return $this->_block;
	}
	

	/**
	 * @var Mage_Core_Block_Abstract
	 */
	private $_block;
	
	


	
	/**
	 * @return Varien_Object
	 */
	private function getTransport () {
		return $this->_transport;
	}	
	
	/**
	 * @var Varien_Object
	 */
	private $_transport;

	
	



	/**
	 * Возвращает HTML нового блока
	 *
	 * @return string
	 */
	private function getNewContent () {
		if (!isset ($this->_newContent)) {
			// Mage_Core_Block_Abstract::$_transportObject — глобальный объект.
			// Его содержимое перетирается в методе Mage_Core_Block_Abstract::toHtml
			// Поэтому перед вызовом toHtml мы сохраняем состояние объекта Mage_Core_Block_Abstract::$_transportObject
			//$currentBlockContent = $this->getContent ();
			$transportObjectState = $this->getTransport()->getData ();
			$this->_newContent = $this->getBlockToInsert ()->toHtml ();
			$this->getTransport()->setData ($transportObjectState);
		}
		return $this->_newContent;
	}



	/** @var string */
	private $_newContent;




	/**
	 * @return Mage_Core_Block_Abstract
	 */
	private function getBlockToInsert () {
		return $this->cfg (self::PARAM_BLOCK_TO_INSERT);
	}



	/**
	 * @return Varien_Event_Observer
	 */
	private function getObserver () {
		return $this->cfg (self::PARAM_OBSERVER);
	}




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this

			/**
			 * Удаление  $this->validateClass (self::PARAM_OBSERVER, self::PARAM_OBSERVER_TYPE)
			 * ускорило загрузку главной страницы с 1.078 сек до 1.067 сек
			 */

			->validateClass (self::PARAM_BLOCK_TO_INSERT, self::PARAM_BLOCK_TO_INSERT_TYPE)
		;

		$this->_block = $this->getObserver()->getData ('block');
		$this->_transport = $this->getObserver()->getData ('transport');
	}




	const DF_CONTENT_KEY = 'html';
	const DF_METHOD_PREPEND = 'prependContent';
	const DF_METHOD_APPEND = 'appendContent';
	const DF_METHOD_APPEND_AND_PREPEND = 'appendAndPrependContent';




	const PARAM_OBSERVER = 'observer';
	const PARAM_OBSERVER_TYPE = 'Varien_Event_Observer';

	const PARAM_BLOCK_TO_INSERT = 'blockToInsert';
	const PARAM_BLOCK_TO_INSERT_TYPE = 'Mage_Core_Block_Abstract';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Category_Content_Inserter';
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