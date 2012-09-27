<?php

class Df_Page_Block_Template_Links extends Mage_Page_Block_Template_Links {



	/**
	 * @param  string $blockType
	 * @return Df_Page_Block_Template_Links
	 */
	public function removeLinkByBlockType ($blockType) {

		$keysToUnset = array ();
		/** @var array $keysToUnset */

		foreach ($this->getLinks () as $key => $link) {
			/** @var Varien_Object $link */

			if ($link instanceof Mage_Core_Block_Abstract) {
				/** @var Mage_Core_Block_Abstract $link */

				if ($blockType == $link->getData ("type")) {
 	                $keysToUnset []= $key;
				}
			}
		}

		foreach ($keysToUnset as $keyToUnset) {
			unset ($this->_links [$keyToUnset]);
		}

		return $this;
	}



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



}