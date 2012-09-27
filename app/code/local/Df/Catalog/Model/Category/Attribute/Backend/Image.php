<?php


class Df_Catalog_Model_Category_Attribute_Backend_Image extends Mage_Catalog_Model_Category_Attribute_Backend_Image {


    /**
     * Save uploaded file and set its name to category
     *
     * @param Varien_Object $object
     */
    public function afterSave ($object)
    {
        // Fix for «exception 'Exception' with message '$_FILES array is empty'»
	    if (
				(
						is_array($object->getData($this->getAttribute()->getName()))
					&&
						!empty($value['delete'])
				)
	        ||
				!df_empty ($_FILES)
        ) {
			parent::afterSave ($object);
        }
    }


}