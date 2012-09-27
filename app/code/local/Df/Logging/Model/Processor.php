<?php

class Df_Logging_Model_Processor
{
    /**
     * Logging events config
     *
     * @var object
     */
    protected $_config;

    /**
     * current event config
     *
     * @var Varien_Simplexml_Element
     */
    protected $_eventConfig;

    /**
     * Instance of controller handler
     *
     * @var oblect
     */
    protected $_controllerActionsHandler;

    /**
     * Instance of model controller
     *
     * @var unknown_type
     */
    protected $_modelsHandler;

    /**
     * Last action name
     *
     * @var string
     */
    protected $_actionName = '';

    /**
     * Last full action name
     *
     * @var string
     */
    protected $_lastAction = '';

    /**
     * Initialization full action name
     *
     * @var string
     */
    protected $_initAction = '';

    /**
     * Flag that signal that we should skip next action
     *
     * @var bool
     */
    protected $_skipNextAction = false;

    /**
     * Temporary storage for model changes before saving to df_logging_event_changes table.
     *
     * @var array
     */
    protected $_eventChanges = array();

    /**
     * Set of fields that should not be logged for all models
     *
     * @deprecated 1.6.0.0
     * @var array
     */
    protected $_skipFields = array();

    /**
     * Set of fields that should not be logged per expected model
     *
     * @deprecated 1.6.0.0
     * @var array
     */
    protected $_skipFieldsByModel = array();

    /**
     * Collection of affected ids
     *
     * @var array
     */
    protected $_collectedIds = array();

    /**
     * @deprecated after 1.6.0.0
     *
     */
    const XML_PATH_SKIP_GLOBAL_FIELDS = 'adminhtml/df/logging/skip_fields';

    /**
     * Initialize configuration model, controller and model handler
     */
    public function __construct()
    {
        $this->_config = Mage::getSingleton('df_logging/config');
        $this->_modelsHandler = Mage::getModel('df_logging/handler_models');
        $this->_controllerActionsHandler = Mage::getModel('df_logging/handler_controllers');
    }

    /**
     * preDispatch action handler
     *
     * @param string $fullActionName Full action name like 'adminhtml_catalog_product_edit'
     * @param string $actionName Action name like 'save', 'edit' etc.
     */
    public function initAction($fullActionName, $actionName)
    {
        $this->_actionName = $actionName;

        if(!$this->_initAction){
            $this->_initAction = $fullActionName;
        }

        $this->_lastAction = $fullActionName;

        $this->_skipNextAction = (!$this->_config->isActive($fullActionName)) ? true : false;
        if ($this->_skipNextAction) {
            return;
        }
        $this->_eventConfig = $this->_config->getNode($fullActionName);

        /**
         * Skip view action after save. For example on 'save and continue' click.
         * Some modules always reloading page after save. We pass comma-separated list
         * of actions into getSkipLoggingAction, it is necessary for such actions
         * like customer balance, when customer balance ajax tab loaded after
         * customer page.
         */
        if ($doNotLog = Mage::getSingleton('admin/session')->getSkipLoggingAction()) {
            if (is_array($doNotLog)) {
                $key = array_search($fullActionName, $doNotLog);
                if ($key !== false) {
                    unset($doNotLog[$key]);
                    Mage::getSingleton('admin/session')->setSkipLoggingAction($doNotLog);
                    $this->_skipNextAction = true;
                    return;
                }
            }
        }

        if (isset($this->_eventConfig->skip_on_back)) {
            $addValue = array_keys($this->_eventConfig->skip_on_back->asArray());
            $sessionValue = Mage::getSingleton('admin/session')->getSkipLoggingAction();
            if (!is_array($sessionValue) && $sessionValue) {
                $sessionValue = explode(',', $sessionValue);
            }
            elseif (!$sessionValue) {
                $sessionValue = array();
            }
            $merge = array_merge($addValue, $sessionValue);
            Mage::getSingleton('admin/session')->setSkipLoggingAction($merge);
        }
    }

    /**
     * Action model processing.
     * Get defference between data & orig_data and store in the internal modelsHandler container.
     *
     * @param object $model
     */
    public function modelActionAfter($model, $action)
    {
        if ($this->_skipNextAction) {
            return;
        }
        //These models used when we merge action models with action group models
        $usedModels = $defaultExpectedModels = null;
        if ($this->_eventConfig) {
            $actionGroupNode = $this->_eventConfig->getParent()->getParent();
            if (isset($actionGroupNode->expected_models)) {
                $defaultExpectedModels = $actionGroupNode->expected_models;
            }
        }

        //Exact models in exactly action node
        $expectedModels = isset($this->_eventConfig->expected_models)
            ? $this->_eventConfig->expected_models : false;

        if (!$expectedModels || empty($expectedModels)) {
            if (empty($defaultExpectedModels)) {
                return;
            }
            $usedModels = $defaultExpectedModels;
        }
        else {
            if ($expectedModels->getAttribute('extends') == 'merge') {
                $defaultExpectedModels->extend($expectedModels);
                $usedModels = $defaultExpectedModels;
            }
            else {
                $usedModels = $expectedModels;
            }
        }

        $skipData = array();

        //Log event changes for each model
        foreach ($usedModels->children() as $expect => $callback) {

            //Add custom skip fields per expecetd model
            if (isset($callback->skip_data)) {
                if ($callback->skip_data->hasChildren()) {
                    foreach ($callback->skip_data->children() as $skipName => $skipObj) {
                        if (!in_array($skipName, $skipData)) {
                            $skipData[] = $skipName;
                        }
                    }
                }
            }

            $className = Mage::getConfig()->getModelClassName(str_replace('__', '/', $expect));
            if ($model instanceof $className){
                $classMap = $this->_getCallbackFunction(trim($callback), $this->_modelsHandler,
                    sprintf('model%sAfter', ucfirst($action)));
                $handler  = $classMap['handler'];
                $callback = $classMap['callback'];
                if ($handler) {

					if (
							/**
							 * Чтобы не попасть в рекурсию,
							 * для Mage_Core_Model_Store мы не вызываем df_enabled
							 */
							($model instanceof Mage_Core_Model_Store)
						||
							(
									df_cfg()->logging()->isEnabled()
								&&
									df_enabled (Df_Core_Feature::LOGGING)
							)
					) {

						$changes = $handler->$callback($model, $this);
						//Because of logging view action, $changes must be checked if it is an object
						if (is_object($changes)) {
							$changes->cleanupData($skipData);
							if ($changes->hasDifference()) {
								$changes->setSourceName($className);
								$changes->setSourceId($model->getId());
								$this->addEventChanges($changes);
							}
						}

					}

                }
            }
            $skipData = array();
        }
    }

    /**
     * Postdispatch action handler
     *
     */
    public function logAction()
    {
        if (!$this->_initAction) {
            return;
        }
        $username = null;
        $userId   = null;
        if (Mage::getSingleton('admin/session')->isLoggedIn()) {
            $userId = Mage::getSingleton('admin/session')->getUser()->getId();
            $username = Mage::getSingleton('admin/session')->getUser()->getUsername();
        }
        $errors = Mage::getModel('adminhtml/session')->getMessages()->getErrors();
        $loggingEvent = Mage::getModel('df_logging/event')->setData(array(
            'ip'            => Mage::helper('core/http')->getRemoteAddr(),
            'x_forwarded_ip'=> Mage::app()->getRequest()->getServer('HTTP_X_FORWARDED_FOR'),
            'user'          => $username,
            'user_id'       => $userId,
            'is_success'    => empty($errors),
            'fullaction'    => $this->_initAction,
            'error_message' => implode("\n", array_map(create_function('$a', 'return $a->toString();'), $errors)),
        ));

        if ($this->_actionName == 'denied') {
            $_conf = $this->_config->getNode($this->_initAction);
            if (!$_conf || !$this->_config->isActive($this->_initAction)) {
                return;
            }
            $loggingEvent->setAction($_conf->action);
            $loggingEvent->setEventCode($_conf->getParent()->getParent()->getName());
            $loggingEvent->setInfo(Mage::helper('df_logging')->__('Access denied'));
            $loggingEvent->setIsSuccess(0);
            $loggingEvent->save();
            return;
        }

        if ($this->_skipNextAction) {
            return;
        }

        $loggingEvent->setAction($this->_eventConfig->action);
        $loggingEvent->setEventCode($this->_eventConfig->getParent()->getParent()->getName());

        try {
            $callback = isset($this->_eventConfig->post_dispatch) ? (string)$this->_eventConfig->post_dispatch : false;
            $defaulfCallback = 'postDispatchGeneric';
            $classMap = $this->_getCallbackFunction($callback, $this->_controllerActionsHandler, $defaulfCallback);
            $handler  = $classMap['handler'];
            $callback = $classMap['callback'];
            if (!$handler) {
                return;
            }
            if ($handler->$callback($this->_eventConfig, $loggingEvent, $this)) {
                $loggingEvent->save();
                if ($eventId = $loggingEvent->getId()) {
                    foreach ($this->_eventChanges as $changes){
                        if ($changes && ($changes->getOriginalData() || $changes->getResultData())) {
                            $changes->setEventId($eventId);
                            $changes->save();
                        }
                    }
                }
            }
        } catch (Exception $e) {
            df_handle_entry_point_exception ($e, false);
        }
    }

    /**
     * Collect $model id
     *
     * @param object $model
     */
    public function collectId($model)
    {
        $this->_collectedIds[get_class($model)][] = $model->getId();
    }

    /**
     * Collected ids getter
     *
     * @return array
     */
    public function getCollectedIds()
    {
        $ids = array();
        foreach ($this->_collectedIds as $className => $classIds) {
            $uniqueIds  = array_unique($classIds);
            $ids        = array_merge($ids, $uniqueIds);
            $this->_collectedIds[$className] = $uniqueIds;
        }
        return $ids;
    }

    /**
     * Get callback function for logAction and modelActionAfter functions
     *
     * @param string $srtCallback
     * @param oblect $defaultHandler
     * @param string $defaultFunction
     * @return array Contains two values 'handler' and 'callback' that indicate what callback function should be applied
     */
    protected function _getCallbackFunction($srtCallback, $defaultHandler, $defaultFunction)
    {
        $return = array('handler' => $defaultHandler, 'callback' => $defaultFunction);
        if (empty($srtCallback)) {
            return $return;
        }

        try {
            $classPath = explode('::', $srtCallback);
            if (count($classPath) == 2) {
                $return['handler'] = Mage::getSingleton(str_replace('__', '/', $classPath[0]));
                $return['callback'] = $classPath[1];
            } else {
                $return['callback'] = $classPath[0];
            }
            if (!$return['handler'] || !$return['callback'] || !method_exists($return['handler'],
                $return['callback'])) {
                Mage::throwException("Unknown callback function: {$srtCallback}");
            }
        } catch (Exception $e) {
            $return['handler'] = false;
            df_handle_entry_point_exception ($e, false);
        }

        return $return;
    }

    /**
     * Clear model data from objects, arrays and fields that should be skipped
     *
     * @deprecated after 1.6.0.0
     * @param array $data
     * @return array
     */
    public function cleanupData($data)
    {
        if (!$data && !is_array($data)) {
            return array();
        }
        $clearData = array();
        foreach ($data as $key=>$value) {
            if (!in_array($key, $this->_skipFields) && !in_array($key, $this->_skipFieldsByModel) && !is_array($value) && !is_object($value)) {
                $clearData[$key] = $value;
            }
        }
        return $clearData;
    }

    /**
     * Add new event changes
     *
     * @param Df_Logging_Model_Event_Changes $eventChange
     * @return Df_Logging_Model_Processor
     */
    public function addEventChanges($eventChange)
    {
        $this->_eventChanges[] = $eventChange;
        return $this;
    }
}
