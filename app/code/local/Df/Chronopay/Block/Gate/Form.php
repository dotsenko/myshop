<?php


class Df_Chronopay_Block_Gate_Form extends Mage_Payment_Block_Form_Cc {


	/**
	 * @override
	 * @return string
	 */
	public function getArea() {

		/** @var string $result  */
		$result = Df_Core_Const_Design_Area::FRONTEND;

		df_result_string ($result);

		return $result;

	}


	/**
	 * @override
	 * @return void
	 */
    protected function _construct() {
        parent::_construct();
	    $this
			->setTemplate (
		            df_magento_version ("1.1", "<")
	            ?
					self::DF_TEMPLATE_LT_1_1
				:
					self::DF_TEMPLATE_GE_1_1
	        )
	    ;
    }


	/*
	 * Шаблон для Magento версий до 1.1
	 */
	const DF_TEMPLATE_LT_1_1 = 'df/chronopay/gate/form-lt-1.1.phtml';


	/*
	 * Шаблон для Magento от версии 1.1
	 */
	const DF_TEMPLATE_GE_1_1 = 'df/chronopay/gate/form-lt-1.1.phtml';

}