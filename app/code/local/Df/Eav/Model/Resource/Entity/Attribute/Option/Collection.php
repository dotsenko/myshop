<?php


class Df_Eav_Model_Resource_Entity_Attribute_Option_Collection extends Mage_Eav_Model_Mysql4_Entity_Attribute_Option_Collection {


	/**
	 * @param string $valueKey
	 * @return array
	 */
    public function toOptionArray($valueKey = 'value')
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
				$this->toOptionArrayDf ($valueKey)
			:
				parent::toOptionArray ($valueKey)
		;
    }



	/**
	 * @param string $valueKey
	 * @return array
	 */
    private function toOptionArrayDf ($valueKey = 'value')
    {
        $labelField = "label";
	    $result = parent::toOptionArray ($valueKey);
	    foreach ($result as &$item) {
		    $label = df_a ($item, $labelField);
		    if (!df_empty ($label)) {
				$item[$labelField] = df_mage()->eavHelper()->__ ($label);
		    }
	    }
	    return $result;
    }
}