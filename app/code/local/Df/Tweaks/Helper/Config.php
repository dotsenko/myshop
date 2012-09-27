<?php


class Df_Tweaks_Helper_Config extends Mage_Core_Helper_Abstract {


	/**
	 * @var array
	 */
	private $_strategyNames;

	/**
	 * @return array
	 */
	public function getStrategyNames () {
		if (!$this->_strategyNames) {
			$this->_strategyNames =
					!$this->getNode()
				?
					array ()
				:
					array_values (
						$this->getNode()->asArray()
					)
			;
		}
		return $this->_strategyNames;
	}



	/**
	 * @var Mage_Core_Model_Config_Element
	 */
	private $_node;


	/**
	 * @return Mage_Core_Model_Config_Element|null
	*/
	private function getNode () {
		if (!isset ($this->_node)) {
			$this->_node =
				df()->config()->getNodeByKey (
					self::CONFIG_ROOT
				)
			;
		}
		return $this->_node;
	}




	const CONFIG_ROOT = 'df/tweaks/strategies';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Tweaks_Helper_Config';
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