<?php


class Df_Customer_Model_Group extends Mage_Customer_Model_Group {

    /**
     * Overwrite data in the object.
     *
     * $key can be string or array.
     * If $key is string, the attribute value will be overwritten by $value
     *
     * If $key is an array, it will overwrite all the data in the object.
     *
     * @param string|array $key
     * @param mixed $value
     * @return Varien_Object
     */
    public function setData($key, $value=null)
    {
        return
					df_enabled (Df_Core_Feature::LOCALIZATION)
				&&
					df_area (
						df_cfg()->localization()->translation()->frontend()->needTranslateDropdownOptions()
						,
						df_cfg()->localization()->translation()->admin()->needTranslateDropdownOptions()
					)
		    ?
				$this->setDataDf ($key, $value)
			:
				parent::setData ($key, $value)
		;
    }




    /**
     * Overwrite data in the object.
     *
     * $key can be string or array.
     * If $key is string, the attribute value will be overwritten by $value
     *
     * If $key is an array, it will overwrite all the data in the object.
     *
     * @param string|array $key
     * @param mixed $value
     * @return Varien_Object
     */
    public function setDataDf ($key, $value=null)
    {
        $labelKey = "customer_group_code";
	    if (is_array ($key)) {
			$label = df_a ($key, $labelKey);
		    if (!df_empty ($label)) {
				$key[$labelKey] = df_mage()->customerHelper()->__ ($label);
		    }
	    }
	    else {
			if ($labelKey == $key) {
				$value = df_mage()->customerHelper()->__ ($value);
			}
	    }
        return parent::setData ($key, $value);
    }

}