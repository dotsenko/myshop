<?php


/**
 * Logging event model
 */
class Df_Logging_Model_Event extends Mage_Core_Model_Abstract
{
    const RESULT_SUCCESS = 'success';
    const RESULT_FAILURE = 'failure';



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_init('df_logging/event');
    }

    /**
     * Set some data automatically before saving model
     *
     * @return Df_Logging_Model_Event
     */
    protected function _beforeSave()
    {
        if (!$this->getId()) {
            $this->setStatus($this->getIsSuccess() ? self::RESULT_SUCCESS : self::RESULT_FAILURE);
            if (!$this->getUser() && $id = $this->getUserId()) {
                $this->setUser(Mage::getModel('admin/user')->load($id)->getUserName());
            }
            if (!$this->hasTime()) {
                $this->setTime(time());
            }
        }
        return parent::_beforeSave();
    }

    /**
     * Define if current event has event changes
     *
     * @return bool
     */
    public function hasChanges()
    {
        if ($this->getId()) {
            return (bool)$this->getResource()->getEventChangeIds($this->getId());
        }
        return false;
    }
}
