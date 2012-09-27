<?php


class Df_Pel_Model_Observer {

	/**
	 * @return void
	 */
	public function controller_front_init_before () {
		Mage::helper ('df_pel/lib');
	}
	
}