<?php


/**
 * Customer balance history block
 *
 */
class Df_CustomerBalance_Block_Account_History extends Mage_Core_Block_Template
{
    /**
     * Balance history action names
     *
     * @var array
     */
    protected $_actionNames = null;

    /**
     * Check if history can be shown to customer
     *
     * @return bool
     */
    public function canShow()
    {
        return
				df_cfg()->customer()->balance()->isEnabled()
			&&
				df_cfg()->customer()->balance()->needShowHistory()
		;
    }

    /**
     * Retreive history events collection
     *
     * @return mixed
     */
    public function getEvents()
    {
        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        if (!$customerId) {
            return false;
        }

        $collection = Mage::getModel('df_customerbalance/balance_history')
                ->getCollection()
                ->addFieldToFilter('customer_id', $customerId)
                ->addFieldToFilter('website_id', Mage::app()->getStore()->getWebsiteId())
                ->setOrder('updated_at');

        return $collection;
    }

    /**
     * Retreive action labels
     *
     * @return array
     */
    public function getActionNames()
    {
        if (is_null($this->_actionNames)) {
            $this->_actionNames = Mage::getSingleton('df_customerbalance/balance_history')->getActionNamesArray();
        }
        return $this->_actionNames;
    }

    /**
     * Retreive action label
     *
     * @param mixed $action
     * @return string
     */
    public function getActionLabel($action)
    {
        $names = $this->getActionNames();
        if (isset($names[$action])) {
            return $names[$action];
        }
        return '';
    }
}
