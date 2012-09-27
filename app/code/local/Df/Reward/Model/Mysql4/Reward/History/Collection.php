<?php



/**
 * Reward history collection
 *
 * @category    Df
 * @package     Df_Reward
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Df_Reward_Model_Mysql4_Reward_History_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_expiryConfig = array();

    /**
     * Internal constructor
     */
    protected function _construct()
    {
        $this->_init('df_reward/reward_history');
    }

    /**
     * Join reward table and retrieve total balance total with customer_id
     *
     * @return Df_Reward_Model_Mysql4_Reward_History_Collection
     */
    protected function _joinReward()
    {
        if ($this->getFlag('reward_joined')) {
            return $this;
        }
        $this->getSelect()->joinInner(
            array('reward_table' => $this->getTable('df_reward/reward')),
            'reward_table.reward_id = main_table.reward_id',
            array('customer_id', 'points_balance_total' => 'points_balance')
        );
        $this->setFlag('reward_joined', true);
        return $this;
    }

    /**
     * Getter for $_expiryConfig
     *
     * @param int $websiteId Specified Website Id
     * @return array|Varien_Object
     */
    protected function _getExpiryConfig($websiteId = null)
    {
        if ($websiteId !== null && isset($this->_expiryConfig[$websiteId])) {
            return $this->_expiryConfig[$websiteId];
        }
        return $this->_expiryConfig;
    }

    /**
     * Setter for $_expiryConfig
     *
     * @param array $config
     * @return Df_Reward_Model_Mysql4_Reward_History_Collection
     */
    public function setExpiryConfig($config)
    {
        if (!is_array($config)) {
            return $this;
        }
        $this->_expiryConfig = $config;
        return $this;
    }

    /**
     * Join reward table to filter history by customer id
     *
     * @param string $customerId
     * @return Df_Reward_Model_Mysql4_Reward_History_Collection
     */
    public function addCustomerFilter($customerId)
    {
        if ($customerId) {
            $this->_joinReward();
            $this->getSelect()->where('reward_table.customer_id = ?', $customerId);
        }
        return $this;
    }

    /**
     * Skip Expired duplicates records (with action = -1)
     *
     * @return Df_Reward_Model_Mysql4_Reward_History_Collection
     */
    public function skipExpiredDuplicates()
    {
        $this->getSelect()->where('main_table.is_duplicate_of IS NULL');
        return $this;
    }

    /**
     * Add filter by website id
     *
     * @param integer|array $websiteId
     * @return Df_Reward_Model_Mysql4_Reward_History_Collection
     */
    public function addWebsiteFilter($websiteId)
    {
        $this->getSelect()->where(is_array($websiteId) ? 'main_table.website_id IN (?)' : 'main_table.website_id = ?', $websiteId);
        return $this;
    }

    /**
     * Join additional customer information, such as email, name etc.
     *
     * @return Df_Reward_Model_Mysql4_Reward_History_Collection
     */
    public function addCustomerInfo()
    {
        if ($this->getFlag('customer_added')) {
            return $this;
        }

        $this->_joinReward();

        $customer = Mage::getModel('customer/customer');
        /* @var $customer Mage_Customer_Model_Customer */
        $firstname  = $customer->getAttribute('firstname');
        $lastname   = $customer->getAttribute('lastname');

        $connection = $this->getConnection();
        /* @var $connection Zend_Db_Adapter_Abstract */

        $this->getSelect()
            ->joinInner(
                array('ce' => $customer->getAttribute('email')->getBackend()->getTable()),
                'ce.entity_id=reward_table.customer_id',
                array('customer_email' => 'email')
             )
            ->joinLeft(
                array('clt' => $lastname->getBackend()->getTable()),
                $connection->quoteInto('clt.entity_id=reward_table.customer_id AND clt.attribute_id = ?', $lastname->getAttributeId()),
                array('customer_lastname' => 'value')
             )
             ->joinLeft(
                array('cft' => $firstname->getBackend()->getTable()),
                $connection->quoteInto('cft.entity_id=reward_table.customer_id AND cft.attribute_id = ?', $firstname->getAttributeId()),
                array('customer_firstname' => 'value')
             );

        $this->setFlag('customer_added', true);
        return $this;
    }

    /**
     * Add correction to expiration date based on expiry calculation
     * CASE ... WHEN ... THEN is used only in admin area to show expiration date for all stores
     *
     * @param int $websiteId
     * @return Df_Reward_Model_Mysql4_Reward_History_Collection
     */
    public function addExpirationDate($websiteId = null)
    {
        $expiryConfig = $this->_getExpiryConfig($websiteId);
        if (!$expiryConfig) {
            return $this;
        }

        if ($websiteId !== null) {
            $field = $expiryConfig->getExpiryCalculation()== 'static' ? 'expired_at_static' : 'expired_at_dynamic';
            $this->getSelect()->columns(array('expiration_date' => $field));
        } else {
            $sql = " CASE main_table.website_id ";
            $cases = array();
            foreach ($expiryConfig as $wId => $config) {
                $field = $config->getExpiryCalculation()== 'static' ? 'expired_at_static' : 'expired_at_dynamic';
                $cases[] = " WHEN '{$wId}' THEN `{$field}` ";
            }
            if (count($cases) > 0) {
                $sql .= implode(' ', $cases) . ' END ';
                $this->getSelect()->columns( array('expiration_date' => new Zend_Db_Expr($sql)) );
            }
        }

        return $this;
    }

    /**
     * Return total amounts of points that will be expired soon (pre-configured days value) for specified website
     * Result is grouped by customer
     *
     * @param int $websiteId Specified Website
     * @param bool $subscribedOnly Whether to load expired soon points only for subscribed customers
     * @return Df_Reward_Model_Mysql4_Reward_History_Collection
     */
    public function loadExpiredSoonPoints($websiteId, $subscribedOnly = true)
    {
        $expiryConfig = $this->_getExpiryConfig($websiteId);
        if (!$expiryConfig) {
            return $this;
        }
        $inDays = (int)$expiryConfig->getExpiryDayBefore();
        // Empty Value disables notification
        if (!$inDays) {
            return $this;
        }

        // join info about current balance and filter records by website
        $this->_joinReward();
        $this->addWebsiteFilter($websiteId);

        $field = $expiryConfig->getExpiryCalculation()== 'static' ? 'expired_at_static' : 'expired_at_dynamic';
        $now = $this->getResource()->formatDate(time());
        $expireAtLimit = new Zend_Date($now);
        $expireAtLimit->addDay($inDays);
        $expireAtLimit = $this->getResource()->formatDate($expireAtLimit);

        $this->getSelect()
            ->columns( array('total_expired' => new Zend_Db_Expr('SUM(`points_delta`-`points_used`)')) )
            ->where('`points_delta`-`points_used`>0')
            ->where('`is_expired`=0')
            ->where("`{$field}` IS NOT NULL") // expire_at - BEFORE_DAYS < NOW()
            ->where("`{$field}` < ?", $expireAtLimit) // eq. expire_at - BEFORE_DAYS < NOW()
            ->group('reward_table.customer_id')
            ->order('reward_table.customer_id');

        if ($subscribedOnly) {
            $this->getSelect()->where('reward_table.reward_warning_notification=1');
        }

        return $this;
    }

    /**
     * Order by primary key desc
     *
     * @return Df_Reward_Model_Mysql4_Reward_History_Collection
     */
    public function setDefaultOrder()
    {
        $this->getSelect()->reset(Zend_Db_Select::ORDER);
        return $this->setOrder('history_id', 'DESC');
    }
}

