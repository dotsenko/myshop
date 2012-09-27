<?php


class Df_Pd4_IndexController extends Mage_Core_Controller_Front_Action {




	/**
	 * @return void
	 */
	public function indexAction () {


		try {

			$this
				->loadLayout ()
				->preparePage ()
				->renderLayout()
			;


		}

		catch (Exception $e) {


			df_log_exception ($e);

			df_mage ()->core ()->session()
				->addError (
					$e->getMessage()
				)
			;

			$this->_redirect(Df_Core_Const::T_EMPTY);

		}

	}





    /**
     * Перекрываем метод лишь для того,
	 * чтобы среда разработки знала класс его результата
     *
	 * @override
     * @param   string $handles
     * @param   string $cacheId
     * @param   boolean $generateBlocks
     * @return  Df_Pd4_IndexController
     */
	public function loadLayout ($handles=null, $generateBlocks=true, $generateXml=true) {

		/** @var Df_Pd4_IndexController $result  */
	    $result = parent::loadLayout($handles, $generateBlocks, $generateXml);

		df_assert ($result instanceof Df_Pd4_IndexController);

		return $result;

	}





	/**
	 * @return Df_Pd4_IndexController
	 */
	private function preparePage () {

		$this->getHeadBlock()
			->setTitle (
				$this->getTitle ()
			)
		;

		return $this;
	}

	
	

	/**
	 * @return string
	 */
	private function getTitle () {
	
		if (!isset ($this->_title)) {


	
			/** @var string $result  */
			$result =
				sprintf (
					'Форма ПД-4 для заказа №%s'
					,
					$this->getOrder ()->getDataUsingMethod (
						Df_Sales_Const::ORDER_PARAM__INCREMENT_ID
					)
				)

			;
	
	
			df_assert_string ($result);
	
			$this->_title = $result;
	
		}
	
	
		df_result_string ($this->_title);
	
		return $this->_title;
	
	}
	
	
	/**
	* @var string
	*/
	private $_title;	


	

	

	/**
	 * @return Mage_Page_Block_Html_Head
	 */
	private function getHeadBlock () {

		if (!isset ($this->_headBlock)) {

			/** @var Mage_Page_Block_Html_Head $result  */
			$result =
				$this->getLayout()->getBlock ('head')
			;


			df_assert ($result instanceof Mage_Page_Block_Html_Head);

			$this->_headBlock = $result;

		}


		df_assert ($this->_headBlock instanceof Mage_Page_Block_Html_Head);

		return $this->_headBlock;

	}


	/**
	* @var Mage_Page_Block_Html_Head
	*/
	private $_headBlock;

	
	
	



	/**
	 * @return Mage_Sales_Model_Order
	 */
	private function getOrder () {

		/** @var Mage_Sales_Model_Order $result  */
		$result = $this->getAction ()->getOrder();

		df_assert ($result instanceof Mage_Sales_Model_Order);

		return $result;

	}

	
	
	


	/**
	 * @return Df_Pd4_Model_Request_Document_View
	 */
	private function getAction () {

		/** @var Df_Pd4_Model_Request_Document_View $result  */
		$result =
			df_helper()->pd4()->getDocumentViewAction()
		;

		df_assert ($result instanceof Df_Pd4_Model_Request_Document_View);

		return $result;

	}


}


