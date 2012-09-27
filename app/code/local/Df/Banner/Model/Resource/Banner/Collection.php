<?php

class Df_Banner_Model_Resource_Banner_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('df_banner/banner');
    }
}