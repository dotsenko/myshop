<?php

class Df_Vk_Helper_Settings extends Df_Core_Helper_Settings {

	
	
	/**
	 * @return Df_Vk_Helper_Settings_Widget_Comments
	 */
	public function comments () {

		/** @var Df_Vk_Helper_Settings_Widget_Comments $result  */
		$result =
			Mage::helper (Df_Vk_Helper_Settings_Widget_Comments::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Vk_Helper_Settings_Widget_Comments);

		return $result;

	}	
	
	
	
	
	
	/**
	 * @return Df_Vk_Helper_Settings_Widget_Groups
	 */
	public function groups () {

		/** @var Df_Vk_Helper_Settings_Widget_Groups $result  */
		$result =
			Mage::helper (Df_Vk_Helper_Settings_Widget_Groups::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Vk_Helper_Settings_Widget_Groups);

		return $result;

	}		
	
	

	
	

	/**
	 * @return Df_Vk_Helper_Settings_Widget_Like
	 */
	public function like () {

		/** @var Df_Vk_Helper_Settings_Widget_Like $result  */
		$result =
			Mage::helper (Df_Vk_Helper_Settings_Widget_Like::getNameInMagentoFormat())
		;

		df_assert ($result instanceof Df_Vk_Helper_Settings_Widget_Like);

		return $result;

	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Vk_Helper_Settings';
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