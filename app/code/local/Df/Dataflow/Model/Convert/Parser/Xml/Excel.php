<?php

class Df_Dataflow_Model_Convert_Parser_Xml_Excel extends Mage_Dataflow_Model_Convert_Parser_Xml_Excel
{

	/**
	 * @param array $fields
	 * @return string
	 */
	protected function _getXmlString(array $fields = array())
    {
		return
				(
						df_enabled (Df_Core_Feature::DATAFLOW)
					&&
						df_cfg()->dataflow()->common()->getSupportHtmlTagsInExcel()
				)
			?
				$this->_getXmlStringDf ($fields)
			:
				parent::_getXmlString ($fields)
		;
    }



	/**
	 * @param array $fields
	 * @return string
	 */
    protected function _getXmlStringDf (array $fields = array())
    {
        $xmlData = array();
        $xmlData[] = '<Row>';
        foreach ($fields as $value) {
            $dataType = "String";
            if (is_numeric($value)) {
                $dataType = "Number";
            }

            $value =
				sprintf (
					"<![CDATA[%s]]>"
					,
					$value
				)
            ;
            
            $xmlData[] = '<Cell><Data ss:Type="'.$dataType.'">'.$value.'</Data></Cell>';
        }

        
        $xmlData[] = '</Row>';

        return join('', $xmlData);
    }
}
