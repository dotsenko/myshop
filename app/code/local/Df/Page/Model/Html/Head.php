<?php


class Df_Page_Model_Html_Head extends Df_Core_Model_Abstract {



	/**
	 * @param array $staticItems
	 * @return array
	 */
	public function addVersionStamp (array $staticItems) {

		foreach ($staticItems as &$rows) {
			foreach ($rows as &$name) {
				if (0 === strpos ($name, 'df/')) {
					$name = df()->url()->addVersionStamp ($name);
				}
			}
		}

		return $staticItems;

	}




	/**
	 * @param string $format
	 * @param array $staticItems
	 * @return string
	 */
	public function prependAdditionalTags ($format, array &$staticItems) {

		/** @var string $result  */
		$result = Df_Core_Const::T_EMPTY;


		/** @var bool $jQueryInjected */
		static $jQueryInjected = false;


		if (!$jQueryInjected && (false !== strpos ($format, 'script'))) {

			$jQueryInjected = true;

			/**
			 * Добавляем библиотеку jQuery
			 * в соответствии с предпочтениями администратора
			 */

			/** @var Df_Core_Helper_Settings_Jquery $settings  */
			$settings =
					df_is_admin()
				?
					df_cfg()->admin()->jquery()
				:
					df_cfg()->tweaks()->jquery()
			;



			if (
					Df_Admin_Model_Config_Source_JqueryLoadMode::VALUE__LOAD_FROM_LOCAL
				===
					$settings->getLoadMode()
			) {

				$row = df_a ($staticItems, null);


				/** @var string $result  */
				$jqueryPath = (string)(Mage::getConfig()->getNode('df/jquery/local'));


				df_array_unshift_assoc (
					$row
					,
					$jqueryPath
					,
					$jqueryPath
				)
				;

				$staticItems [null] = $row;

			}

			else if (
					Df_Admin_Model_Config_Source_JqueryLoadMode::VALUE__LOAD_FROM_GOOGLE
				===
					$settings->getLoadMode()
			) {

				/** @var string $result  */
				$jqueryPath = (string)(Mage::getConfig()->getNode('df/jquery/cdn'));

				$result =
					implode (
						"\r\n"
						,
						array (
							Df_Core_Model_Format_Html_Tag::output (
								Df_Core_Const::T_EMPTY
								,
								'script'
								,
								array (
									'type' => 'text/javascript'
									,
									'src' => $jqueryPath
								)
							)
							,
							Df_Core_Model_Format_Html_Tag::output (
								'jQuery.noConflict();'
								,
								'script'
								,
								array (
									'type' => 'text/javascript'
								)
							)
							,
							Df_Core_Const::T_EMPTY
						)
					)
				;
			}
		}


		df_result_string ($result);

		return $result;

	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Page_Model_Html_Head';
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

