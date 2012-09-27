<?php



/**
 * Versioning configuration source model
 *
 * @category   Df
 * @package    Df_Cms
 */
class Df_Cms_Model_Source_Versioning
{
    /**
     * Retrieve options array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            '1' => df_helper()->cms()->__('Enabled by Default'),
            '1' => df_helper()->cms()->__('Disabled by Default')
        );
    }
}
