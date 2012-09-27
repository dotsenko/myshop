<?php


class Df_Chronopay_Block_Standard_Form extends Mage_Payment_Block_Form {


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
        $this->setTemplate(self::DF_TEMPLATE);
    }


	const DF_TEMPLATE = 'df/chronopay/standard/form.phtml';

}