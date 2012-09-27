<?php

class Df_Checkout_Helper_Settings_OrderComments extends Df_Core_Helper_Settings {



	/**
	 * @return string
	 */
	public function getPositionRelativeToTheTerms () {

		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				'df_checkout/order_comments/position_relative_to_terms'
			)
		;

		if (is_null ($result)) {
			$result =
				Df_Admin_Model_Config_Source_Layout_Position_AboveOrBelow::BELOW
			;
		}

		df_result_string ($result);

		return $result;
	}




	/**
	 * @return boolean
	 */
	public function specifyTextareaWidth () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_checkout/order_comments/specify_textarea_width'
			)
		;

		df_result_boolean ($result);

		return $result;
	}





	/**
	 * @return int
	 */
	public function getTextareaWidth () {

		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					'df_checkout/order_comments/textarea_width'
				)
			)
		;

		df_result_integer ($result);

		return $result;
	}




	/**
	 * @return boolean
	 */
	public function specifyTextareaPosition () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_checkout/order_comments/specify_textarea_position'
			)
		;

		df_result_boolean ($result);

		return $result;
	}



	/**
	 * @return string
	 */
	public function getTextareaFloat () {

		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				'df_checkout/order_comments/textarea_float'
			)
		;

		if (is_null ($result)) {
			$result =
				Df_Admin_Model_Config_Source_Layout_Float::NONE
			;
		}

		df_result_string ($result);

		return $result;
	}



	/**
	 * @return boolean
	 */
	public function specifyTextareaHoriziontalShift () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_checkout/order_comments/specify_textarea_horizontal_shift'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @return string
	 */
	public function getTextareaHoriziontalShiftDirection () {

		/** @var string $result  */
		$result =
			Mage::getStoreConfig (
				'df_checkout/order_comments/textarea_horizontal_shift_direction'
			)
		;

		if (is_null ($result)) {
			$result =
				Df_Admin_Model_Config_Source_Layout_Shift_Direction_Horizontal::LEFT
			;
		}

		df_result_string ($result);

		return $result;
	}




	/**
	 * @return int
	 */
	public function getTextareaHoriziontalShiftLength () {

		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					'df_checkout/order_comments/textarea_horizontal_shift_length'
				)
			)
		;

		df_result_integer ($result);

		return $result;
	}






	/**
	 * @return int
	 */
	public function getTextareaVisibleRows () {

		/** @var int $result  */
		$result =
			intval (
				Mage::getStoreConfig (
					'df_checkout/order_comments/textarea_rows'
				)
			)
		;

		df_result_integer ($result);

		return $result;
	}





	/**
	 * @return boolean
	 */
	public function isEnabled () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_checkout/order_comments/enabled'
			)
		;

		df_result_boolean ($result);

		return $result;
	}





	/**
	 * @return boolean
	 */
	public function needShowInCustomerAccount () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_checkout/order_comments/show_in_customer_account'
			)
		;

		df_result_boolean ($result);

		return $result;
	}



	/**
	 * @return boolean
	 */
	public function needShowInOrderEmail () {

		/** @var bool $result  */
		$result =
			$this->getYesNo (
				'df_checkout/order_comments/show_in_order_email'
			)
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Checkout_Helper_Settings_OrderComments';
	}


	/**
	 * Например, для класса Df_checkoutRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_checkout_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return
			df()->reflection()->getModelNameInMagentoFormat (
				self::getClass()
			)
		;
	}


}