<?php


/**
 * Customer balance model
 *
 */
class Df_CustomerBalance_Model_Balance extends Mage_Core_Model_Abstract
{
    /**
     * @var Mage_Customer_Model_Customer
     */
    protected $_customer;

    protected $_eventPrefix = 'customer_balance';
    protected $_eventObject = 'balance';

    /**
     * Initialize resource
     *
     */
    protected function _construct()
    {
        $this->_init('df_customerbalance/balance');
    }

    /**
     * @deprecated after 1.3.2.3
     * @param Mage_Customer_Model_Customer $customer
     * @return bool
     */
    public function shouldCustomerHaveOneBalance($customer)
    {
        return false;
    }

    /**
     * Get balance amount
     *
     * @return float
     */
    public function getAmount()
    {
        return (float)$this->getData('amount');
    }

    /**
     * Load balance by customer
     * Website id should either be set or not admin
     *
     * @return Df_CustomerBalance_Model_Balance
     * @throws Mage_Core_Exception
     */
    public function loadByCustomer()
    {
        $this->_ensureCustomer();
        if ($this->hasWebsiteId()) {
            $websiteId = $this->getWebsiteId();
        }
        else {
            if (df_is_admin()) {
                Mage::throwException(df_helper()->customer()->balance()->__('Website ID must be set.'));
            }
            $websiteId = Mage::app()->getStore()->getWebsiteId();
        }
        $this->getResource()->loadByCustomerAndWebsiteIds($this, $this->getCustomerId(), $websiteId);
        return $this;
    }

    /**
     * Specify whether email notification should be sent
     *
     * @param bool $shouldNotify
     * @param int $storeId
     * @return Df_CustomerBalance_Model_Balance
     * @throws Mage_Core_Exception
     */
    public function setNotifyByEmail($shouldNotify, $storeId = null)
    {
        $this->setData('notify_by_email', $shouldNotify);
        if ($shouldNotify) {
            if (null === $storeId) {
                Mage::throwException(df_helper()->customer()->balance()->__('Set Store ID as well.'));
            }
            $this->setStoreId($storeId);
        }
        return $this;

    }

    /**
     * Validate before saving
     *
     * @return Df_CustomerBalance_Model_Balance
     */
    protected function _beforeSave()
    {
        $this->_ensureCustomer();

        // make sure appropriate website was set. Admin website is disallowed
        if ((!$this->hasWebsiteId()) && $this->shouldCustomerHaveOneBalance($this->getCustomer())) {
            $this->setWebsiteId($this->getCustomer()->getWebsiteId());
        }
        if (0 == $this->getWebsiteId()) {
            Mage::throwException(df_helper()->customer()->balance()->__('Website ID must be set.'));
        }

        // check history action
        if (!$this->getId()) {
            $this->loadByCustomer();
            if (!$this->getId()) {
                $this->setHistoryAction(Df_CustomerBalance_Model_Balance_History::ACTION_CREATED);
            }
        }
        if (!$this->hasHistoryAction()) {
            $this->setHistoryAction(Df_CustomerBalance_Model_Balance_History::ACTION_UPDATED);
        }

        // check balance delta and email notification settings
        $delta = $this->_prepareAmountDelta();
        if (0 == $delta) {
            $this->setNotifyByEmail(false);
        }
        if ($this->getNotifyByEmail() && !$this->hasStoreId()) {
            Mage::throwException(df_helper()->customer()->balance()->__('In order to send email notification, the Store ID must be set.'));
        }

        return parent::_beforeSave();
    }

    /**
     * Update history after saving
     *
     * @return Df_CustomerBalance_Model_Balance
     */
    protected function _afterSave()
    {
        parent::_afterSave();

        // save history action
        if (abs($this->getAmountDelta())) {
            $history = Mage::getModel('df_customerbalance/balance_history')
                ->setBalanceModel($this)
                ->save();
        }

        return $this;
    }

    /**
     * Make sure proper customer information is set. Load customer if required
     *
     * @throws Mage_Core_Exception
     */
    protected function _ensureCustomer()
    {
        if ($this->getCustomer() && $this->getCustomer()->getId()) {
            $this->setCustomerId($this->getCustomer()->getId());
        }
        if (!$this->getCustomerId()) {
            Mage::throwException(df_helper()->customer()->balance()->__('Customer ID must be specified.'));
        }
        if (!$this->getCustomer()) {
            $this->setCustomer(Mage::getModel('customer/customer')->load($this->getCustomerId()));
        }
        if (!$this->getCustomer()->getId()) {
            Mage::throwException(df_helper()->customer()->balance()->__('Customer is not set or does not exist.'));
        }
    }

    /**
     * Validate & adjust amount change
     *
     * @return float
     */
    protected function _prepareAmountDelta()
    {
        $result = 0;
        if ($this->hasAmountDelta()) {
            $result = (float)$this->getAmountDelta();
            if ($this->getId()) {
                if (($result < 0) && (($this->getAmount() + $result) < 0)) {
                    $result = -1 * $this->getAmount();
                }
            }
            elseif ($result <= 0) {
                $result = 0;
            }
        }
        $this->setAmountDelta($result);
        if (!$this->getId()) {
            $this->setAmount($result);
        }
        else {
            $this->setAmount($this->getAmount() + $result);
        }
        return $result;
    }

    /**
     * Check whether balance completely covers specified quote
     *
     * @param Mage_Sales_Model_Quote $quote
     * @return bool
     */
    public function isFullAmountCovered(Mage_Sales_Model_Quote $quote, $isEstimation = false)
    {
        if (!$isEstimation && !$quote->getUseCustomerBalance()) {
            return false;
        }
        return $this->getAmount() >=
            ((float)$quote->getBaseGrandTotal() + (float)$quote->getBaseCustomerBalanceAmountUsed());
    }

    /**
     * @deprecated after 1.3.2.3
     */
    public function isFulAmountCovered(Mage_Sales_Model_Quote $quote)
    {
        return $this->isFullAmountCovered($quote);
    }

    /**
     * Update customers balance currency code per website id
     *
     * @param int $websiteId
     * @param string $currencyCode
     * @return Df_CustomerBalance_Model_Balance
     */
    public function setCustomersBalanceCurrencyTo($websiteId, $currencyCode)
    {
        $this->getResource()->setCustomersBalanceCurrencyTo($websiteId, $currencyCode);
        return $this;
    }

    /**
     * Delete customer orphan balances
     *
     * @param int $customerId
     * @return Df_CustomerBalance_Model_Balance
     */
    public function deleteBalancesByCustomerId($customerId)
    {
        $this->getResource()->deleteBalancesByCustomerId($customerId);
        return $this;
    }

    /**
     * Get customer orphan balances count
     *
     * @return Df_CustomerBalance_Model_Balance
     */
    public function getOrphanBalancesCount($customerId)
    {
        return $this->getResource()->getOrphanBalancesCount($customerId);
    }

    /**
     * Public version of afterLoad
     *
     * @return Mage_Core_Model_Abstract
     */
    public function afterLoad()
    {
        return $this->_afterLoad();
    }
}
