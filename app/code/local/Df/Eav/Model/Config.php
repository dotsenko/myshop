<?php

class Df_Eav_Model_Config extends Mage_Eav_Model_Config {


    /**
     * Associate object with identifier
     *
     * @param   mixed $obj
     * @param   mixed $id
     * @return  Mage_Eav_Model_Config
     */
    protected function _save ($obj, $id) {


		if (
				/**
				 * Вызывать здесь df_is_admin() нельзя:
				 *
					{main}( )	..\index.php:0
					Mage::run( )	..\index.php:80
					Mage_Core_Model_App->run( )	..\Mage.php:640
					Mage_Core_Model_App->_initModules( )	..\App.php:338
					Mage_Core_Model_Resource_Setup::applyAllUpdates( )	..\App.php:412
					Mage_Core_Model_Resource_Setup->applyUpdates( )	..\Setup.php:235
					Mage_Core_Model_Resource_Setup->_installResourceDb( )	..\Setup.php:327
					Mage_Core_Model_Resource_Setup->_modifyResourceDb( )	..\Setup.php:422
					include( 'C:\work\p\2012\02\16\sandbox-1620-2\code\app\code\local\Df\Customer\sql\df_customer_setup\mysql4-upgrade-1.0.0-1.0.1.php' )	..\Setup.php:624
					Mage_Eav_Model_Config->getAttribute( )	..\mysql4-upgrade-1.0.0-1.0.1.php:74
					Mage_Eav_Model_Config->getEntityType( )	..\Config.php:386
					Df_Eav_Model_Config->_save( )	..\Config.php:332
				 *
				 */
				Mage::app()->getStore()->isAdmin()
			&&
				is_object ($obj)
			&&
				($obj instanceof Mage_Eav_Model_Entity_Attribute)
			&&
				df_enabled (Df_Core_Feature::LOCALIZATION)
			&&
				df_cfg()->localization()->translation()->admin()->isEnabled()
		) {

			/** @var Mage_Eav_Model_Entity_Attribute $obj */

			df_helper()->eav()->translateAttribute ($obj);

		}


		parent::_save ($obj, $id);

        return $this;
    }


}


