<?php

class Df_Banner_Model_Resource_Banner extends Mage_Core_Model_Mysql4_Abstract
{

	/**
	 * @override
	 * @return void
	 */
    public function _construct()
    {

		/**
		 * Нельзя вызывать parent::_construct(),
		 * потому что это метод в родительском классе — абстрактный.
		 */

        // Note that the easybanner_id refers to the key field in your database table.
        $this->_init('df_banner/banner', 'banner_id');
    }
}