<?php



/**
 * Source model for list of Expiry Calculation algorythms
 */
class Df_Reward_Model_Source_Points_ExpiryCalculation
{
    public function toOptionArray()
    {
        return array(
            array('value' => 'static', 'label' => df_helper()->reward()->__('Static')),
            array('value' => 'dynamic', 'label' => df_helper()->reward()->__('Dynamic')),
        );
    }
}
