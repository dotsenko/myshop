<?php


abstract class Df_Phpquery_Model_Document extends Df_Core_Model_Abstract {


	/**
	 * @param string $text
	 * @return phpQueryObject
	 */
	abstract protected function createPqDocument ($text);





	/**
	 * @return phpQueryObject
	 */
	public function getPq () {

		if (!isset ($this->_pq)) {


			/** @var phpQueryObject $result */
			$result = $this->cfg (self::PARAM_PQ);

			if (is_null ($result)) {

				df_assert (
					!is_null ($this->getDocument ())
					,
					sprintf (
						'При создании объекта %s вы должны указать либо параметр «%s», либо параметр «%s».'
						,
						get_class ($this)
						,
						self::PARAM_PQ
						,
						self::PARAM_DOCUMENT
					)

				)
				;


				$result =
					$this->createPqDocument (
						$this->getDocument ()
					)
				;


			}


			df_assert ($result instanceof phpQueryObject);

			$this->_pq = $result;

		}


		df_assert ($this->_pq instanceof phpQueryObject);

	    return $this->_pq;
	}



	/**
	 * @var phpQueryObject
	 */
	private $_pq;




	/**
	 * @param  string|DOMNode|DOMNodeList|array $selector
	 * @return phpQueryObject|false
	 */
	public function pq ($selector) {

		df_assert (
				is_string ($selector)
			||
				($selector instanceof DOMNode)
			||
				($selector instanceof DOMNodeList)
			||
				is_array($selector)
		)
		;

		/** @var phpQueryObject|false $result  */
		$result = pq ($selector, $this->getPq ());

		df_assert (
			false !== $result
		)
		;


		df_assert ($result instanceof phpQueryObject);


		return $result;
	}




    /**
     * @return string|null
     */
	protected function getDocument () {

		/** @var string|null $result  */
	    $result = $this->cfg (self::PARAM_DOCUMENT);

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}







	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {

		parent::_construct ();

		/** @var Df_Phpquery_Helper_Lib $library  */
		$library = Mage::helper ("df_phpquery/lib");

		df_assert ($library instanceof Df_Phpquery_Helper_Lib);

		$library->init ();

	    $this
	        ->addValidator (
				self::PARAM_DOCUMENT, new Df_Zf_Validate_String(), false
			)
	        ->validateClass (
				self::PARAM_PQ,	'phpQueryObject', false
			)
		;
	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Phpquery_Model_Document';
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





	const PARAM_DOCUMENT = 'document';
	const PARAM_PQ = 'pq';



}