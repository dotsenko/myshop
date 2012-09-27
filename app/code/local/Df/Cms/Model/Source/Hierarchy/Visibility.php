<?php



/**
 * Visibility option source model for Hierarchy metadata
 *
 * @category   Df
 * @package    Df_Cms
 */
class Df_Cms_Model_Source_Hierarchy_Visibility
{
    /**
     * Retrieve options array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            Df_Cms_Helper_Hierarchy::METADATA_VISIBILITY_PARENT => df_helper()->cms()->__('Use Parent'),
            Df_Cms_Helper_Hierarchy::METADATA_VISIBILITY_YES => df_helper()->cms()->__('Yes'),
            Df_Cms_Helper_Hierarchy::METADATA_VISIBILITY_NO => df_helper()->cms()->__('No'),
        );
    }
}
