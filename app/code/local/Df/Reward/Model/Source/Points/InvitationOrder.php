<?php



/**
 * Source model for Acquiring frequency when Order processed after Invitation
 */
class Df_Reward_Model_Source_Points_InvitationOrder
{
    public function toOptionArray()
    {
        return array(
            array('value' => '*', 'label' => df_helper()->reward()->__('Each')),
            array('value' => '1', 'label' => df_helper()->reward()->__('First')),
        );
    }
}
