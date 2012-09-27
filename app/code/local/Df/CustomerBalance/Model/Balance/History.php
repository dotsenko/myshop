<?php


/**
 * Customerbalance history model
 *
 */
class Df_CustomerBalance_Model_Balance_History extends Mage_Core_Model_Abstract
{
    const ACTION_UPDATED  = 1;
    const ACTION_CREATED  = 2;
    const ACTION_USED     = 3;
    const ACTION_REFUNDED = 4;

    /**
     * Initialize resource
     *
     */
    protected function _construct()
    {
        $this->_init('df_customerbalance/balance_history');
    }

    /**
     * Available action names getter
     *
     * @return array
     */
    public function getActionNamesArray()
    {
        return array(
            self::ACTION_CREATED  => df_helper()->customer()->balance()->__('Created'),
            self::ACTION_UPDATED  => df_helper()->customer()->balance()->__('Updated'),
            self::ACTION_USED     => df_helper()->customer()->balance()->__('Used'),
            self::ACTION_REFUNDED => df_helper()->customer()->balance()->__('Refunded'),
        );
    }

    /**
     * Validate balance history before saving
     *
     * @return Df_CustomerBalance_Model_Balance_History
     */
    protected function _beforeSave()
    {
        $balance = $this->getBalanceModel();
        if ((!$balance) || !$balance->getId()) {
            Mage::throwException(df_helper()->customer()->balance()->__('Balance history cannot be saved without existing balance.'));
        }

        $this->addData(array(
            'balance_id'     => $balance->getId(),
            'updated_at'     => time(),
            'balance_amount' => $balance->getAmount(),
            'balance_delta'  => $balance->getAmountDelta(),
        ));

        switch ((int)$balance->getHistoryAction())
        {
            case self::ACTION_CREATED:
                // break intentionally omitted
            case self::ACTION_UPDATED:
                if (!$balance->getUpdatedActionAdditionalInfo()) {
                    if ($user = Mage::getSingleton('admin/session')->getUser()) {
                        if ($user->getUsername()) {
                            if (!trim($balance->getComment())){
                                $this->setAdditionalInfo(df_helper()->customer()->balance()->__('By admin: %s.', $user->getUsername()));
                            }else{
                                $this->setAdditionalInfo(df_helper()->customer()->balance()->__('By admin: %1$s. (%2$s)', $user->getUsername(), $balance->getComment()));
                            }
                        }
                    }
                } else {
                    $this->setAdditionalInfo($balance->getUpdatedActionAdditionalInfo());
                }
                break;
            case self::ACTION_USED:
                $this->_checkBalanceModelOrder($balance);
                $this->setAdditionalInfo(df_helper()->customer()->balance()->__('Order #%s', $balance->getOrder()->getIncrementId()));
                break;
            case self::ACTION_REFUNDED:
                $this->_checkBalanceModelOrder($balance);
                if ((!$balance->getCreditMemo()) || !$balance->getCreditMemo()->getIncrementId()) {
                    Mage::throwException(df_helper()->customer()->balance()->__('There is no creditmemo set to balance model.'));
                }
                $this->setAdditionalInfo(df_helper()->customer()->balance()->__('Order #%s, creditmemo #%s',
                    $balance->getOrder()->getIncrementId(), $balance->getCreditMemo()->getIncrementId()
                ));
                break;
            default:
                Mage::throwException(df_helper()->customer()->balance()->__('Unknown balance history action code'));
                // break intentionally omitted
        }
        $this->setAction((int)$balance->getHistoryAction());

        return parent::_beforeSave();
    }

    /**
     * Send balance update if required
     *
     * @return Df_CustomerBalance_Model_Balance_History
     */
    protected function _afterSave()
    {
        parent::_afterSave();

        // attempt to send email
        $this->setIsCustomerNotified(false);
        if ($this->getBalanceModel()->getNotifyByEmail()) {
            $storeId = $this->getBalanceModel()->getStoreId();
            $email = Mage::getModel('core/email_template')->setDesignConfig(array('store' => $storeId));
            $customer = $this->getBalanceModel()->getCustomer();
            $email->sendTransactional(
                Mage::getStoreConfig (
					'df_customer/balance/email_template'
					,
					$storeId
				)
				,
                Mage::getStoreConfig (
					'df_customer/balance/email_identity'
					,
					$storeId
				)
				,
                $customer->getEmail(), $customer->getName(),
                array(
                    'balance' => Mage::app()->getWebsite($this->getBalanceModel()->getWebsiteId())->getBaseCurrency()->format($this->getBalanceModel()->getAmount(), array(), false),
                    'name'    => $customer->getName(),
            ));
            if ($email->getSentSuccess()) {
                $this->getResource()->markAsSent($this->getId());
                $this->setIsCustomerNotified(true);
            }
        }

        return $this;
    }

    /**
     * Validate order model for balance update
     *
     * @param Mage_Sales_Model_Order $model
     */
    protected function _checkBalanceModelOrder($model)
    {
        if ((!$model->getOrder()) || !$model->getOrder()->getIncrementId()) {
            Mage::throwException(df_helper()->customer()->balance()->__('There is no order set to balance model.'));
        }
    }
}
