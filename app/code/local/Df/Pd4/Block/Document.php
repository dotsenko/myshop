<?php


class Df_Pd4_Block_Document extends Df_Core_Block_Template {




	

	/**
	 * @return string
	 */
	public function getRowsHtml () {
	
		if (!isset ($this->_rowsHtml)) {
	
			/** @var string $result  */
			$result =
				$this->getRowsBlock ()->toHtml()
			;
	
	
			df_assert_string ($result);
	
			$this->_rowsHtml = $result;
	
		}
	
	
		df_result_string ($this->_rowsHtml);
	
		return $this->_rowsHtml;
	
	}
	
	
	/**
	* @var string
	*/
	private $_rowsHtml;	
	
	
	
	
	
	/**
	 * @return Df_Pd4_Block_Document_Rows
	 */
	private function getRowsBlock () {

		if (!isset ($this->_rowsBlock)) {

			/** @var Df_Pd4_Block_Document_Rows $result  */
			$result =
				$this->getLayout()->createBlock (
					Df_Pd4_Block_Document_Rows::getNameInMagentoFormat()
				)
			;


			df_assert ($result instanceof Df_Pd4_Block_Document_Rows);

			$this->_rowsBlock = $result;

		}


		df_assert ($this->_rowsBlock instanceof Df_Pd4_Block_Document_Rows);

		return $this->_rowsBlock;

	}


	/**
	* @var Df_Pd4_Block_Document_Rows
	*/
	private $_rowsBlock;
	
	





	
	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Pd4_Block_Document';
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

