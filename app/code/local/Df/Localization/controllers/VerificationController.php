<?php

class Df_Localization_VerificationController extends Mage_Adminhtml_Controller_Action {

	/**
	 * @return void
	 */
	public function indexAction() {

		$this
			->_title($this->__('System'))
			->_title($this->__('Локализация'))
			->_title('Проверка полноты перевода')
			->loadLayout()
		;

		$this
			->_setActiveMenu('system/df_localization')
			->renderLayout()
		;
	}



	/**
	 * @return bool
	 */
    protected function _isAllowed() {

		/** @var bool $result */
        $result =
				df_enabled (Df_Core_Feature::LOCALIZATION)
			&&
				df_mage()->admin()->session()->isAllowed('system/df_localization/verification')
		;

		df_result_boolean ($result);

		return $result;
    }

}