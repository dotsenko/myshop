<?php


class Df_Customer_Block_Account_Navigation extends Mage_Customer_Block_Account_Navigation {


    /**
     * @return string
     */
    public function __ () {

		/** @var array $args  */
        $args = func_get_args();

		df_assert_array ($args);


		/** @var string $result  */
        $result =
			df_helper()->localization()->translation()->translateByParent ($args, $this)
		;

		df_result_string ($result);


	    return $result;
    }





	/**
	 * @param  string $path
	 * @return Df_Customer_Block_Account_Navigation
	 */
	public function removeLinkByPath ($path) {

		$linkNamesToRemove = array ();
		/** @var array $linkNamesToRemove */

		foreach ($this->_links as $name => $link) {
			/** @var Varien_Object $link */

			if ($path == $link->getData ("path")) {
				$linkNamesToRemove []= $name;
			}
		}

		foreach ($linkNamesToRemove as $name) {
			/** @var string $name */
			unset ($this->_links [$name]);
		}

		return $this;
	}


}













