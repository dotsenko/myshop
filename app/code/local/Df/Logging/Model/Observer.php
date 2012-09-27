<?php


/**
 * Log admin actions and performed changes.
 * It doesn't log all admin actions, only listed in logging.xml config files.
 */
class Df_Logging_Model_Observer
{


    /**
     * Initialize Df_Logging_Model_Processor class
     *
     */
    public function __construct()
    {
        $this->_processor = Mage::getSingleton('df_logging/processor');
    }



    /**
     * Instance of Df_Logging_Model_Processor
     *
     * @var Df_Logging_Model_Processor
     */
    protected $_processor;





    /**
     * Mark actions for logging, if required
     *
     * @param Varien_Event_Observer $observer
     */
    public function controllerPredispatch($observer)
    {
		if (
				df_cfg()->logging()->isEnabled()
			&&
				df_enabled (Df_Core_Feature::LOGGING)
		) {

			/* @var $action Mage_Core_Controller_Varien_Action */
			$action = $observer->getEvent()->getControllerAction();
			/* @var $request Mage_Core_Controller_Request_Http */
			$request = $observer->getEvent()->getControllerAction()->getRequest();

			$beforeForwardInfo = $request->getBeforeForwardInfo();

			// Always use current action name bc basing on
			// it we make decision about access granted or denied
			$actionName = $request->getRequestedActionName();

			if (empty($beforeForwardInfo)) {
				$fullActionName = $action->getFullActionName();
			} else {
				$fullActionName = array($request->getRequestedRouteName());

				if (isset($beforeForwardInfo['controller_name'])) {
					$fullActionName[] = $beforeForwardInfo['controller_name'];
				} else {
					$fullActionName[] = $request->getRequestedControllerName();
				}

				if (isset($beforeForwardInfo['action_name'])) {
					$fullActionName[] = $beforeForwardInfo['action_name'];
				} else {
					$fullActionName[] = $actionName;
				}

				$fullActionName = implode('_', $fullActionName);
			}

			$this->_processor->initAction($fullActionName, $actionName);

		}
    }







    /**
     * Model after save observer.
     *
     * @param Varien_Event_Observer
     */
    public function modelSaveAfter($observer)
    {
		if (df_cfg()->logging()->isEnabled()) {
			/**
			 * Опасно здесь ставить df_enabled, потому что можем попасть в рекурсию.
			 * Вместо этого ставим df_enabled внутри $this->_processor->modelActionAfter
			 */
			$this->_processor->modelActionAfter($observer->getEvent()->getObject(), 'save');
		}
    }





    /**
     * Model after delete observer.
     *
     * @param Varien_Event_Observer
     */
    public function modelDeleteAfter($observer)
    {
		if (df_cfg()->logging()->isEnabled()) {
			/**
			 * Опасно здесь ставить df_enabled, потому что можем попасть в рекурсию.
			 * Вместо этого ставим df_enabled внутри $this->_processor->modelActionAfter
			 */
			$this->_processor->modelActionAfter($observer->getEvent()->getObject(), 'delete');
		}
    }




    /**
     * Model after load observer.
     *
     * @param Varien_Event_Observer
     */
    public function modelLoadAfter($observer)
    {
		/**
		 * Нельзя здесь ставить df_enabled или df_cfg()->logging()->isEnabled(),
		 * потому что иначе попадём в рекурсию.
		 * Вместо этого выполняем проверки внутри $this->_processor->modelActionAfter
		 */
		$this->_processor->modelActionAfter($observer->getEvent()->getObject(), 'view');

    }




    /**
     * Log marked actions
     *
     * @param Varien_Event_Observer $observer
     */
    public function controllerPostdispatch($observer)
    {
		if (
				df_cfg()->logging()->isEnabled()
			&&
				df_enabled (Df_Core_Feature::LOGGING)
		) {
			if ($observer->getEvent()->getControllerAction()->getRequest()->isDispatched()) {
				$this->_processor->logAction();
			}
		}
    }




    /**
     * Log successful admin sign in
     *
     * @param Varien_Event_Observer $observer
     */
    public function adminSessionLoginSuccess($observer)
    {
		if (
				df_cfg()->logging()->isEnabled()
			&&
				df_enabled (Df_Core_Feature::LOGGING)
		) {
			$this->_logAdminLogin($observer->getUser()->getUsername(), $observer->getUser()->getId());
		}
    }




    /**
     * Log failure of sign in
     *
     * @param Varien_Event_Observer $observer
     */
    public function adminSessionLoginFailed($observer)
    {
		if (
				df_cfg()->logging()->isEnabled()
			&&
				df_enabled (Df_Core_Feature::LOGGING)
		) {
			$eventModel = $this->_logAdminLogin($observer->getUserName());

			if (class_exists('Df_Pci_Model_Observer', false) && $eventModel) {
				$exception = $observer->getException();
				if ($exception->getCode() == Df_Pci_Model_Observer::ADMIN_USER_LOCKED) {
					$eventModel->setInfo(Mage::helper('df_logging')->__('User is locked'))->save();
				}
			}
		}
    }




    /**
     * Log sign in attempt
     *
     * @param string $username
     * @param int $userId
     * @return Df_Logging_Model_Event
     */
    protected function _logAdminLogin($username, $userId = null)
    {
        $eventCode = 'admin_login';
        if (!Mage::getSingleton('df_logging/config')->isActive($eventCode, true)) {
            return;
        }
        $success = (bool)$userId;
        if (!$userId) {
            $userId = Mage::getSingleton('admin/user')->loadByUsername($username)->getId();
        }
        $request = Mage::app()->getRequest();
        return Mage::getSingleton('df_logging/event')->setData(array(
            'ip'         => Mage::helper('core/http')->getRemoteAddr(),
            'user'       => $username,
            'user_id'    => $userId,
            'is_success' => $success,
            'fullaction' => "{$request->getRouteName()}_{$request->getControllerName()}_{$request->getActionName()}",
            'event_code' => $eventCode,
            'action'     => 'login',
        ))->save();
    }




    /**
     * Cron job for logs rotation
     */
    public function rotateLogs()
    {
		if (
				df_cfg()->logging()->isEnabled()
			&&
				df_enabled (Df_Core_Feature::LOGGING)
		) {
			$lastRotationFlag = Mage::getModel('df_logging/flag')->loadSelf();
			//$lastRotationTime = $lastRotationFlag->getFlagData();
	//        $rotationFrequency =
	//			3600 * 24 * df_cfg()->admin()->logging()->archiving()->getFrequency()
	//		;
	//        if (!$lastRotationTime || ($lastRotationTime < time() - $rotationFrequency)) {
				Mage::getResourceModel('df_logging/event')->rotate(
					3600 * 24 * (df_cfg()->admin()->logging()->archiving()->getLifetime())
				);
	//        }
			$lastRotationFlag->setFlagData(time())->save();
		}
    }
}
