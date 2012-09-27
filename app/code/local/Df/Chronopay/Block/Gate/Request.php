<?php


class Df_Chronopay_Block_Gate_Request extends Df_Core_Block_Template {



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Chronopay_Block_Gate_Request';
	}


	/**
	 * Например, для класса Df_SalesRule_Block_Event_Validator_Process
	 * метод должен вернуть: «df_sales_rule/event_validator_process»
	 *
	 * @static
	 * @return string
	 */
	public static function getNameInMagentoFormat () {
		return
			df()->reflection()

				/**
				 * Для блоков тоже работает
				 */
				->getModelNameInMagentoFormat (
					self::getClass()
				)
		;
	}








	/**
	 * @var array
	 */
	private $_options;


	/**
	 * @return array
	 */
	public function getOptions () {
		if (!isset ($this->_options)) {

			$options =
				array (
					"skip_client_callback" => "skipClientCallback"
					,
					"skip_customer_email" => "skipCustomerEmail"
					,
					"skip_rebill" => "skipRebill"
				)
			;

			$this->_options = array ();

			foreach ($options as $key => $tag) {
				if ($this->getConfigParam ($key)) {
					$this->_options  []= $tag;
				}
			}
		}
	    return $this->_options;
	}


	/**
	 * @return string
	 */
	public function getPrice () {
		try {
			$convertedPrice =
				df_helper()->directory()->currency()->getBase ()
					->convert (
						$this->getBaseGrandTotal ()
						,
						$this->getPaymentController ()->getChronopayCurrency ()
					)
			;
		}
		catch (Exception $e) {
			df_log ($e->getMessage ());
		    throw $e;
		}

        return
		    number_format (
			    (float)$convertedPrice

				,
			    2
		        ,
			    '.'
		        ,
			    ''
		    )
        ;
	}












	/**
	 * @return double
	 */
	public function getBaseGrandTotal () {
		return $this->getPaymentInfo ()->getOrder()->getBaseGrandTotal();
	}



	/**
	 * @return string
	 */
	public function getHash () {
		return
			md5 (
				implode (
					"-"
					,
					array (
						df_mage()->coreHelper()->decrypt (
							$this->getConfigParam ("shared_sec")
						)
						,
						$this->getOperationCode ()
						,
						$this->getProductId ()
					)
				)
			)
		;
	}


	/**
	 * @return int
	 */
	public function getOperationCode () {
 	    return 1;
	}


	/**
	 * @return string
	 */
	public function getProductId () {
 	    return $this->getConfigParam ("product_id");
	}


	/**
	 * @var Df_Chronopay_Model_Gate_Buyer
	 */
	private $_buyer;

	/**
	 * @return Df_Chronopay_Model_Gate_Buyer
	 */
	public function getBuyer () {
		if (!isset ($this->_buyer)) {
			$this->_buyer =
				df_model (
					Df_Chronopay_Model_Gate_Buyer::getNameInMagentoFormat()
					,
					array (
						Df_Chronopay_Model_Gate_Buyer::PARAM_PAYMENT => $this->getPaymentInfo ()
					)
				)
			;
		}
		return $this->_buyer;
	}


	/**
	 * @var Df_Chronopay_Model_Gate_Card
	 */
	private $_card;

	/**
	 * @return Df_Chronopay_Model_Gate_Card
	 */
	public function getCard () {
		if (!isset ($this->_card)) {
			$this->_card =
				df_model (
					Df_Chronopay_Model_Gate_Card::getNameInMagentoFormat()
					,
					array (
						Df_Chronopay_Model_Gate_Card::PARAM_PAYMENT => $this->getPaymentInfo ()
					)
				)
			;
		}
		return $this->_card;
	}


	/**
	 * @param  string $key
	 * @param $default
	 * @return string
	 */
	private function getConfigParam ($key, $default = NULL) {
		$result = $this->getPaymentController ()->getConfigData ($key);
	    if (df_empty ($key)) {
		    $result = $default;
	    }
	    return $result;
	}



	/**
	 * @return Mage_Payment_Model_Info
	 */
	private function getPaymentInfo () {
		return $this->getData ("paymentInfo");
	}


	/**
	 * @return Df_Chronopay_Model_Gate
	 */
	private function getPaymentController () {
		return $this->getData ("paymentController");
	}

}


