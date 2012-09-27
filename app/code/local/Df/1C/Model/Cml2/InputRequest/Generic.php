<?php

class Df_1C_Model_Cml2_InputRequest_Generic extends Df_Core_Model_InputRequest {


	/**
	 * @return string
	 */
	public function getMode () {

		/** @var string $result  */
		$result = $this->getParam ('mode');

		if (
			!in_array (
				$result
				,
				array (
					self::MODE__CHECK_AUTH
					,
					self::MODE__FILE
					,
					self::MODE__IMPORT
					,
					self::MODE__INIT
					,
					self::MODE__QUERY
					,
					self::MODE__SUCCESS
				)
			)
		) {
			df_error (
				sprintf (
					'Недопустимое значение параметра «mode»: «%s»'
					,
					$result
				)
			);
		}

		return $result;

	}



	/**
	 * @return string
	 */
	public function getType () {

		/** @var string $result  */
		$result = $this->getParam ('type');

		if (
			!in_array (
				$result
				,
				array (
					self::TYPE__ORDERS
					,
					self::TYPE__CATALOG
				)
			)
		) {
			df_error (
				sprintf (
					'Недопустимое значение параметра «type»: «%s»'
					,
					$result
				)
			);
		}

		return $result;
	}




	const MODE__CHECK_AUTH = 'checkauth';
	const MODE__FILE = 'file';
	const MODE__IMPORT = 'import';
	const MODE__INIT = 'init';
	const MODE__QUERY = 'query';
	const MODE__SUCCESS = 'success';


	const TYPE__ORDERS = 'sale';
	const TYPE__CATALOG = 'catalog';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_InputRequest_Generic';
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


