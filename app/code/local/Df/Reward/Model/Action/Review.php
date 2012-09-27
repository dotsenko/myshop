<?php


/**
 * Reward action for review submission
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Model_Action_Review extends Df_Reward_Model_Action_Abstract
{
    /**
     * Retrieve points delta for action
     *
     * @param int $websiteId
     * @return int
     */
    public function getPoints($websiteId)
    {
        return (int)df_helper()->reward()->getPointsConfig('review', $websiteId);
    }

    /**
     * Return pre-configured limit of rewards for action
     *
     * @return int|string
     */
    public function getRewardLimit()
    {
        return df_helper()->reward()->getPointsConfig('review_limit', $this->getReward()->getWebsiteId());
    }

    /**
     * Return action message for history log
     *
     * @param array $args Additional history data
     * @return string
     */
    public function getHistoryMessage($args = array())
    {
        return df_helper()->reward()->__('For submitting a product review.');
    }
}
