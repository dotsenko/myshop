<?php


class Df_Customer_Model_Entity_Group_Collection extends Mage_Customer_Model_Entity_Group_Collection {


	/**
	 * @return array
	 */
    public function toOptionArray()
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
				$this->toOptionArrayDf ()
			:
				parent::toOptionArray ()
		;
    }



	/**
	 * @return array
	 */
    public function toOptionArrayDf()
    {
	    $labelField = "label";
	    $result = parent::toOptionArray ();
	    foreach ($result as &$item) {
		    $label = df_a ($item, $labelField);
		    if (!df_empty ($label)) {
				$item[$labelField] = df_mage()->customerHelper()->__ ($label);
		    }
	    }
	    return $result;
    }
}