<?php


/**
 * Customerbalance history resource model
 *
 */
class Df_CustomerBalance_Model_Mysql4_Balance_History extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Initialize resource
     *
     */
    protected function _construct()
    {
        $this->_init('df_customerbalance/balance_history', 'history_id');
    }

    /**
     * Set updated_at automatically before saving
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Df_CustomerBalance_Model_Mysql4_Balance_History
     */
    public function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        $object->setUpdatedAt($this->formatDate(time()));
        return parent::_beforeSave($object);
    }

    /**
     * Mark specified balance history record as sent to customer
     *
     * @param int $id
     */
    public function markAsSent($id)
    {
        $this->_getWriteAdapter()->update($this->getMainTable(), array('is_customer_notified' => 1),
            $this->_getWriteAdapter()->quoteInto('history_id = ?', $id)
        );
    }
}
