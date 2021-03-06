<?php


class Df_Speed_Model_Dispatcher extends Df_Core_Model_Abstract {




	/**
	 * @param Varien_Event_Observer $observer
	 * @return void
	 */
	public function controller_front_init_before (
		Varien_Event_Observer $observer
	) {

		try {


			/**
			 *
			 * controller_front_init_before вызывается до создания объектов Zend_Date
			 *
			 * Включение кэша дат даёт прирост производительности
			 * только при использовании быстрой системы кеширования.
			 *
			 * Например:
			 *
			 * 	<cache>
					<backend>Zend_Cache_Backend_ZendServer_ShMem</backend>
					<prefix>MIR_PRYAZHI_</prefix>
				</cache>
			 */

			if (df_cfg()->speed()->general()->enableZendDateCaching()) {

				Zend_Date
					::setOptions (
						array (
						   'cache' => Mage::app()->getCache()
						)
					)
				;
			}


			if (
					/**
					 * Данная функциональность приводит к проблеме
					 * при добавлении товара к сравнению:
					 * @link http://magento-forum.ru/topic/2295/
					 */
					false
				&&
					df_cfg()->speed()->general()->disableVisitorLogging()
			) {

				/**
				 * На данном этапе инициализации системы
				 * мы не можем вызывать df_is_admin()
				 */

				/** @var bool $isFrontendArea  */
				$isFrontendArea =
						false
					===
						strpos (
							Mage::app()->getRequest()->getOriginalPathInfo ()
							,
							(string)
								Mage::getConfig()->getNode(
									'admin/routers/adminhtml/args/frontName'
								)
						)
				;


				if ($isFrontendArea) {


					/** @var Mage_Core_Model_Config_Element $eventsConfig  */
					$eventsConfig = Mage::app()->getConfig()
						->getNode(Df_Core_Const_Design_Area::FRONTEND)->{'events'}
					;

					/** @var SimpleXMLElement[]|false $logNodes  */
					$logNodes = $eventsConfig->xpath ('//observers/log');

					if (is_array ($logNodes)) {

						foreach ($logNodes as $node) {

							/** @var DOMElement|false $domNode  */
							$domNode = dom_import_simplexml ($node);

							if ($domNode) {

								/** @var DOMNode $parent  */
								$parent = $domNode->parentNode;

								$parent->removeChild ($domNode);
							}
						}

					}

				}

			}

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Speed_Model_Dispatcher';
	}


	/**
	 * Например, для класса Df_SalesRule_Model_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {

		/** @var string $result */
		static $result;

		if (!isset ($result)) {
			$result = df()->reflection()->getModelNameInMagentoFormat (self::getClass());
		}

		return $result;
	}



}


