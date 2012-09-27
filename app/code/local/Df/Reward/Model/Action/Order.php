<?php


/**
 * Reward action for using points to purchase order
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Model_Action_Order extends Df_Reward_Model_Action_Abstract
{
    /**
     * Return action message for history log
     *
     * @param array $args Additional history data
     * @return string
     */
    public function getHistoryMessage($args = array())
    {
        $incrementId = isset($args['increment_id']) ? $args['increment_id'] : '';
        return df_helper()->reward()->__('Redeemed for order #%s.', $incrementId);
    }

    /**
     *
     * Setter for $_entity and add some extra data to history
     *
     * @param Varien_Object $entity
     * @return Df_Reward_Model_Action_Abstract
     */
    public function setEntity($entity)
    {
        parent::setEntity($entity);
        $this->getHistory()->addAdditionalData(array(
            'increment_id' => $this->getEntity()->getIncrementId()
        ));
        return $this;
    }
}
