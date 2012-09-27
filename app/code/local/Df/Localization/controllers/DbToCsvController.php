<?php

class Df_Localization_DbToCsvController extends Mage_Adminhtml_Controller_Action {

	/**
	 * @return void
	 */
	public function indexAction() {

		$this
			->_title($this->__('System'))
			->_title($this->__('Локализация'))
			->_title('Запись переводов из БД в CSV')
			->loadLayout()
		;

		$this
			->_setActiveMenu('system/df_localization')
			->renderLayout()
		;
	}


	/**
	 * @return void
	 */
	public function exportAction() {

		/** @var Df_Localization_Model_Exporter $processor */
		$processor =
			df_model (
				Df_Localization_Model_Exporter::getNameInMagentoFormat()
			)
		;

		df_assert ($processor instanceof Df_Localization_Model_Exporter);

		$processor->process ();

        $this->_redirect('*/*/*');
	}



	
	/**
	 * @return bool
	 */
    protected function _isAllowed() {

		/** @var bool $result */
        $result =
				df_enabled (Df_Core_Feature::LOCALIZATION)
			&&
				df_mage()->admin()->session()->isAllowed('system/df_localization/dbToCsv')
		;

		df_result_boolean ($result);

		return $result;
    }

}