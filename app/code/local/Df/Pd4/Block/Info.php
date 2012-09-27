<?php

class Df_Pd4_Block_Info extends Df_Payment_Block_Info {




	/**
	 * @return string
	 */
	public function getLinkToDocumentAsHtml () {
	
		if (!isset ($this->_linkToDocumentAsHtml)) {
	
			/** @var string $result  */
			$result =
					!$this->capableLinkToOrder ()
				?
					Df_Core_Const::T_EMPTY
				:
					$this->getLinkBlock()->toHtml()
			;
	
	
			df_assert_string ($result);
	
			$this->_linkToDocumentAsHtml = $result;
	
		}
	
	
		df_result_string ($this->_linkToDocumentAsHtml);
	
		return $this->_linkToDocumentAsHtml;
	
	}
	
	
	/**
	* @var string
	*/
	private $_linkToDocumentAsHtml;





    /**
     * Retrieve payment method model
     *
	 * @override
     * @return Df_Pd4_Model_Payment
     */
	public function getMethod() {

		/** @var Df_Pd4_Model_Payment $result  */
		$result = parent::getMethod();

		df_assert ($result instanceof Df_Pd4_Model_Payment);

		return $result;
	}




	/**
	 * @override
	 * @return string
	 */
	public function getTemplate () {

		/** @var string $result  */
		$result = self::RM__TEMPLATE;

		df_result_string ($result);

		return $result;

	}
	




	/**
	 * @return bool
	 */
	public function capableLinkToOrder () {

		/** @var bool $result  */
		$result =
				($this->getOrder () instanceof Mage_Sales_Model_Order)
			&&

				/**
				 * Инициализируется в методе Mage_Sales_Model_Order::_beforeSave()
				 */
				!df_empty (
					$this->getOrder ()->getData (
						Df_Sales_Const::ORDER_PARAM__PROTECT_CODE
					)
				)
		;


		df_result_boolean ($result);

		return $result;

	}

	
	
	
	/**
	 * @return Df_Pd4_Block_LinkToDocument_ForAnyOrder
	 */
	private function getLinkBlock () {

		df_assert ($this->capableLinkToOrder ());
	
		if (!isset ($this->_linkBlock)) {
	
			/** @var Df_Pd4_Block_LinkToDocument_ForAnyOrder $result  */
			$result =
				df_block (
					Df_Pd4_Block_LinkToDocument_ForAnyOrder::getNameInMagentoFormat()
				)
			;

	
	
			df_assert ($result instanceof Df_Pd4_Block_LinkToDocument_ForAnyOrder);


			$result
				->setOrder(
					$this->getOrder ()
				)
			;


			df_assert ($result instanceof Df_Pd4_Block_LinkToDocument_ForAnyOrder);

	
			$this->_linkBlock = $result;
	
		}
	
	
		df_assert ($this->_linkBlock instanceof Df_Pd4_Block_LinkToDocument_ForAnyOrder);
	
		return $this->_linkBlock;
	
	}
	
	
	/**
	* @var Df_Pd4_Block_LinkToDocument_ForAnyOrder
	*/
	private $_linkBlock;	
	




	const RM__TEMPLATE = 'df/pd4/info.phtml';
}
