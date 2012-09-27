<?php


class Df_Chronopay_Model_Source_Language
{

	/**
	 * @return array
	 */
    public function toOptionArray()
    {
        return array(
            array('value' => 'EN', 'label' => df_helper()->chronopay()->__('English')),
            array('value' => 'RU', 'label' => df_helper()->chronopay()->__('Russian')),
            array('value' => 'NL', 'label' => df_helper()->chronopay()->__('Dutch')),
            array('value' => 'DE', 'label' => df_helper()->chronopay()->__('German')),
        );
    }
}



