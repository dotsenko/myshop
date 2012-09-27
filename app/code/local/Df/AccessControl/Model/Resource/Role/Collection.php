<?php

class Df_AccessControl_Model_Resource_Role_Collection
	extends Mage_Core_Model_Mysql4_Collection_Abstract {




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct();
		$this
			->_init (
				Df_AccessControl_Model_Role::getNameInMagentoFormat()
			)
		;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_AccessControl_Model_Resource_Role_Collection';
	}


	/**
	 * Например, для класса Df_SalesRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return
			self::CLASS_NAME_MF
		;
	}



    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'df_access_control_role_collection';


    /**
     * Event object name
     *
     * @var string
     */
    protected $_eventObject = 'role_collection';


	const CLASS_NAME_MF = 'df_access_control/role_collection';


}
