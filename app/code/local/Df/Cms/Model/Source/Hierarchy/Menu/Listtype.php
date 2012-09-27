<?php



/**
 * CMS Hierarchy Navigation Menu source model for list type
 *
 * @category   Df
 * @package    Df_Cms
 */
class Df_Cms_Model_Source_Hierarchy_Menu_Listtype
{
    /**
     * Retrieve options array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            '0'  => df_helper()->cms()->__('Unordered'),
            '1' => df_helper()->cms()->__('Ordered'),
        );
    }
}
