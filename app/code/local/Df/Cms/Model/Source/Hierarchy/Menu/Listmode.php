<?php



/**
 * CMS Hierarchy Navigation Menu source model for Display list mode
 *
 * @category   Df
 * @package    Df_Cms
 */
class Df_Cms_Model_Source_Hierarchy_Menu_Listmode
{
    /**
     * Retrieve options array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            ''          => df_helper()->cms()->__('Default'),
            '1'         => df_helper()->cms()->__('Numbers (1, 2, 3, ...)'),
            'a'         => df_helper()->cms()->__('Lower Alpha (a, b, c, ...)'),
            'A'         => df_helper()->cms()->__('Upper Alpha (A, B, C, ...)'),
            'i'         => df_helper()->cms()->__('Lower Roman (i, ii, iii, ...)'),
            'I'         => df_helper()->cms()->__('Upper Roman (I, II, III, ...)'),
            'circle'    => df_helper()->cms()->__('Circle'),
            'disc'      => df_helper()->cms()->__('Disc'),
            'square'    => df_helper()->cms()->__('Square'),
        );
    }
}
