<?php


class Df_WalletOne_Model_Request_SignatureGenerator extends Df_Core_Model_Abstract {
	
	
	
	/**
	 * @return string
	 */
	public function getSignature () {

		if (!isset ($this->_signature)) {
	
			/** @var string $result  */
			$result = 
				base64_encode (
					pack (
						'H*'
						,
						md5 (
							implode (
								Df_Core_Const::T_EMPTY
								,
								array (
									$this->implodeParams (
										$this->convertParamsToWindows1251 (
											$this->sortParams (
												$this->getSignatureParams()
											)
										)
									)
									,
									$this->getEncryptionKey()
								)
							)
						)
					)
				)
			;
	
			df_assert_string ($result);
	
			$this->_signature = $result;
	
		}
	
	
		df_result_string ($this->_signature);
	
		return $this->_signature;
	
	}
	
	
	/**
	* @var string
	*/
	private $_signature;






	/**
	 * @param array $params
	 * @return array
	 */
	private function convertParamsToWindows1251 (array $params) {

		/** @var array $result  */
		$result = array ();

		foreach ($params as $key => $value) {

			/** @var string|int $key */
			/** @var mixed $value */


			$result [$key] =
					is_array ($value)
				?
					$this->convertParamsToWindows1251 ($value)
				:
					(
							(
									is_string ($value)
								&&
									/**
									 * Обратите внимание, что данный класс
									 * используется в двух сценариях:
									 *
									 * при отсылке запроса на проведение платежа платёжной системе
									 * и при получении от платёжной системы
									 * подтверждения приёма оплаты от покупателя.
									 *
									 * Во втором сценарии платёжная система
									 * присылает текстовые данные не в UTF-8,
									 * а в Windows-1251, и тогда iconv не нужна и, более того,
									 * приводит к сбою:
									 * Detected an illegal character in input string
									 */
									mb_detect_encoding($value, 'UTF-8', true)
							)
						?
							df_text()->convertUtf8ToWindows1251 ($value)
						:
							$value
					)
			;

		}

		df_result_array ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	private function getEncryptionKey () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__ENCRYPTION_KEY);

		df_result_string ($result);

		return $result;

	}





	/**
	 * @return array
	 */
	private function getSignatureParams () {

		/** @var array $result  */
		$result = $this->cfg (self::PARAM__SIGNATURE_PARAMS);

		df_result_array ($result);

		return $result;

	}




	/**
	 * @param array $params
	 * @return string
	 */
	private function implodeParams (array $params) {

		/** @var array $result  */
		$resultAsArray = NULL;

		foreach ($params as $key => $value) {

			/** @var string $key */
			/** @var mixed $value */

			df_assert_string ($key);

			if (is_array ($value)) {
				$value = implode (Df_Core_Const::T_EMPTY, $value);
			}

			$resultAsArray[$key] = $value;

		}

		/** @var string $result  */
		$result = implode (Df_Core_Const::T_EMPTY, $resultAsArray);

		df_result_string ($result);

		return $result;

	}




	/**
	 * @param array $params
	 * @return array
	 */
	private function sortParams (array $params) {

		/** @var array $result  */
		$result = array ();

		foreach ($params as $key => $value) {

			/** @var string $key */
			/** @var mixed $value */

			df_assert_string ($key);

			if (is_array ($value)) {
				usort($value, 'strcasecmp');
			}

			$result [$key] = $value;

		}

		uksort($result, 'strcasecmp');


		df_result_array ($result);

		return $result;

	}




	



	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__ENCRYPTION_KEY, new Df_Zf_Validate_String()
			)
			->addValidator (
				self::PARAM__SIGNATURE_PARAMS, new Df_Zf_Validate_Array()
			)
		;
	}



	const PARAM__ENCRYPTION_KEY = 'encryption_key';
	const PARAM__SIGNATURE_PARAMS = 'signature_params';
	
	
	


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_WalletOne_Model_Request_SignatureGenerator';
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


