<?php


/**
 * Invitation collection
 *
 * @category   Df
 * @package    Df_Invitation
 */
class Df_Invitation_Model_Mysql4_Invitation_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected $_map = array('fields' => array(
        'invitee_email'    => 'c.email',
        'website_id'       => 'w.website_id',
        'invitation_email' => 'main_table.email',
        'invitee_group_id' => 'main_table.group_id'
    ));

    /**
     * Intialize collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('df_invitation/invitation');
    }

    /**
     * Instantiate select object
     *
     * @return Df_Invitation_Model_Mysql4_Invitation_Collection
     */
    protected function _initSelect()
    {
        $this->getSelect()->from(array('main_table' => $this->getResource()->getMainTable()),
            array('*', 'invitation_email' => 'email', 'invitee_group_id' => 'group_id')
        );
        return $this;
    }

    /**
     * Load collection where customer id equals passed parameter
     *
     * @param int $id
     * @return Df_Invitation_Model_Mysql4_Invitation_Collection
     */
    public function loadByCustomerId($id)
    {
        $this->getSelect()->where('main_table.customer_id = ?', $id);
        return $this->load();
    }

    /**
     * Filter by specified store ids
     *
     * @param array|int $storeIds
     * @return Df_Invitation_Model_Mysql4_Invitation_Collection
     */
    public function addStoreFilter($storeIds)
    {
        $this->getSelect()->where('main_table.store_id IN (?)', $storeIds);
        return $this;
    }

    /**
     * Join website ID
     *
     * @return Df_Invitation_Model_Mysql4_Invitation_Collection
     */
    public function addWebsiteInformation()
    {
        $this->getSelect()
            ->joinInner(array('w' => $this->getTable('core/store')), 'main_table.store_id = w.store_id', 'w.website_id');
        return $this;
    }

    /**
     * Join referrals information (email)
     *
     * @return Df_Invitation_Model_Mysql4_Invitation_Collection
     */
    public function addInviteeInformation()
    {
        $this->getSelect()->joinLeft(array('c' => $this->getTable('customer/entity')),
            'main_table.referral_id = c.entity_id', array('invitee_email' => 'c.email')
        );
        return $this;
    }

    /**
     * Filter collection by items that can be sent
     *
     * @return Df_Invitation_Model_Mysql4_Invitation_Collection
     */
    public function addCanBeSentFilter()
    {
        return $this->addFieldToFilter('status', Df_Invitation_Model_Invitation::STATUS_NEW);
    }

    /**
     * Filter collection by items that can be cancelled
     *
     * @return Df_Invitation_Model_Mysql4_Invitation_Collection
     */
    public function addCanBeCanceledFilter()
    {
        return $this->addFieldToFilter('status', array('nin' => array(
            Df_Invitation_Model_Invitation::STATUS_CANCELED,
            Df_Invitation_Model_Invitation::STATUS_ACCEPTED
        )));
    }
}
