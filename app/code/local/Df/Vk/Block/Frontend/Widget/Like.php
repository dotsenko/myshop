<?php


class Df_Vk_Block_Frontend_Widget_Like extends Df_Vk_Block_Frontend_Widget {


	/**
	 * @override
	 * @return string
	 */
	public function getJavaScriptNameSpace () {
		return 'like';
	}




	/**
	 * @override
	 * @return string
	 */
	protected function getJavaScriptObjectName () {
		return 'VK.Widgets.Like';
	}



	/**
	 * @override
	 * @return Df_Vk_Helper_Settings_Widget
	 */
	protected function getSettings () {
		return df_cfg()->vk()->like();
	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Vk_Block_Frontend_Widget_Like';
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


