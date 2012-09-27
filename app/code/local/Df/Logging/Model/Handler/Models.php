<?php


/**
 * Custom handlers for models logging
 *
 */
class Df_Logging_Model_Handler_Models
{
    /**
     * SaveAfter handler
     *
     * @param object Mage_Core_Model_Abstract $model
     * @return object Df_Logging_Event_Changes or false if model wasn't modified
     */
    public function modelSaveAfter($model, $processor)
    {
        $processor->collectId($model);
        $changes = Mage::getModel('df_logging/event_changes')
            ->setOriginalData($model->getOrigData())
            ->setResultData($model->getData());
        return $changes;
    }

    /**
     * Delete after handler
     *
     * @param object Mage_Core_Model_Abstract $model
     * @return object Df_Logging_Event_Changes
     */
    public function modelDeleteAfter($model, $processor)
    {
        $processor->collectId($model);
        $changes = Mage::getModel('df_logging/event_changes')
            ->setOriginalData($model->getOrigData())
            ->setResultData(null);
        return $changes;
    }

    /**
     * MassUpdate after handler
     *
     * @param object Mage_Core_Model_Abstract $model
     * @return object Df_Logging_Event_Changes
     */
    public function modelMassUpdateAfter($model, $processor)
    {
        return $this->modelSaveAfter($model, $processor);
    }

    /**
     * MassDelete after handler
     *
     * @param object Mage_Core_Model_Abstract $model
     * @return object Df_Logging_Event_Changes
     */
    public function modelMassDeleteAfter($model, $processor)
    {
        return $this->modelSaveAfter($model, $processor);
    }

    /**
     * Load after handler
     *
     * @param object Mage_Core_Model_Abstract $model
     * @return Df_Logging_Model_Event_Changes
     */
    public function modelViewAfter($model, $processor)
    {
        $processor->collectId($model);
        return true;
    }
}
