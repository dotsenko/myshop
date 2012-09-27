<?php

class Df_Cms_Model_Resource_Page extends Mage_Cms_Model_Mysql4_Page {

    /**
	 *  @override
     *  @param    Mage_Core_Model_Abstract $object
     *  @return   bool
     */
    protected function isValidPageIdentifier(Mage_Core_Model_Abstract $object)
    {
        return
			preg_match(
				/**
				 * Добавляем поддержку кириллицы.
				 */
				'/^[a-zа-яА-ЯёЁ0-9][a-zа-яА-ЯёЁ0-9_\/-]+(\.[a-zа-яА-ЯёЁ0-9_-]+)?$/u'
				,
				$object->getData('identifier')
			)
		;
    }


}


