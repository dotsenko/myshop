<?php

class Df_Chronopay_Model_Gate_Buyer extends Df_Core_Model_Abstract {


	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Chronopay_Model_Gate_Buyer';
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







	/**
	 * @return string
	 */
	public function getFirstName () {
 	    return df_a ($this->getCompositeName (), 0);
	}


	/**
	 * @return string
	 */
	public function getLastName () {
 	    return df_a ($this->getCompositeName (), 1);
	}


	/**
	 * @return string
	 */
	public function getStreetAddress () {
        return
				!df_empty ($this->getStreetLine1 ())
		    ?
				$this->getStreetLine1 ()
			:
				$this->getStreetLine2 ()
        ;
	}


	/**
	 * @return string
	 */
	public function getCity () {
 	    return $this->getBillingAddress ()->getCity ();
	}


	/**
	 * @return string
	 */
	public function getCountryCode () {
 	    return $this->getBillingAddress ()->getCountryModel()->getIso3Code();
	}


	/**
	 * @return string
	 */
	public function getRegionCode () {
 	    return $this->getBillingAddress ()->getRegionModel()->getCode();
	}


	/**
	 * @return string
	 */
	public function getPostCode () {
 	    return $this->getBillingAddress ()->getPostcode ();
	}


	/**
	 * @return string
	 */
	public function getPhone () {
 	    return $this->getBillingAddress ()->getTelephone ();
	}


	/**
	 * @return string
	 */
	public function getEmail () {
		return
			df_a (
				df_clean (
					array (
						$this->getOrder ()->getCustomerEmail ()
						,
						$this->getBillingAddress ()->getEmail ()
					)
				)
				,
				0
			)
		;
	}


	/**
	 * @return string
	 */
	private function getStreetLine1 () {
		return df_a ($this->getBillingAddress ()->getStreet(), 0, '');
	}


	/**
	 * @return string
	 */
	private function getStreetLine2 () {
		return df_a ($this->getBillingAddress ()->getStreet(), 1, '');
	}


	/**
	 * @return string
	 */
	public function getIpAddress () {
		return df_a ($_SERVER, "REMOTE_ADDR");
	}



	/**
	 * @return string
	 */
	public function getLocalTime () {
		return $this->getPayment ()->getClientLocalTime ();
	}


	/**
	 * @return string
	 */
	public function getScreenResolution () {
		return $this->getPayment ()->getClientScreenResolution ();
	}


	/**
	 * @return string
	 */
	public function getUserAgent () {
		return df_a ($_SERVER, "HTTP_USER_AGENT");
	}


	/**
	 * @var string
	 */
	private $_compositeName;


	/**
	 * @return string
	 */
	private function getCompositeName () {
		if (!isset ($this->_compositeName)) {

			$name =
				strtr (
					mb_strtoupper (
						$this->getPayment ()->getCcOwner ()
					)
					,
					df_helper()->chronopay()->cartholderNameConversionConfig()
						->getConversionTable ()
				)
			;

			$this->checkNameValidness ($name);


			// We expect that all name parts besides the last are First Name,
			// and the last part is Last Name
			$exploded =
				array_map (
					"df_trim"
					,
					explode (
						' '
						,
						$name
					)
				)
			;
			$countExplodedParts = count ($exploded);

			$this->_compositeName =
				array (
					implode (
						' '
						,
						array_slice (
							$exploded
							,
							0
							,
							$countExplodedParts - 1
						)
					)
				    ,
					df_a ($exploded, $countExplodedParts - 1, '')
				)
			;
		}
	    return $this->_compositeName;
	}



	/**
	 * @param  string $name
	 * @return Df_Chronopay_Model_Gate_Buyer
	 */
	private function checkNameValidness ($name) {
		$matches = array ();
		$r =
			preg_match_all (
				'#[^A-Z\s]+#mui'
				,
				$name
				,
				$matches
				,
				PREG_PATTERN_ORDER
			)
		;
		if ($r) {
			$invalidSymbols = df_a ($matches, 0);
			if (count ($invalidSymbols)) {
				df_error (
					sprintf (
						implode (
							"\r\n"
							,
							array_map (
								array ($this, "__")
								,
								array (
									"The cardholder name you entered (“%s”) contains invalid characters: %s."
								    ,
								    "Only English letters are valid in the cardholder name."
								    ,
								    "Please return one step back, review your credit card more accurately and type the cardholder name to the payment form straight as it typed on your credit card."
								)
							)
						)
						,
						$name
						,
						implode (
							", "
							,
							$invalidSymbols
						)
					)
				)
				;
			}

		}

	    return $this;
	}



	/**
	 * @param  string $text
	 * @return string
	 */
	private function __ ($text) {
		return df_helper()->chronopay()-> __ ($text);
	}



	/**
	 * @return Mage_Sales_Model_Order
	 */
	private function getOrder () {
		return $this->getPayment ()->getOrder ();
	}


	/**
	 * @return Mage_Sales_Model_Order_Address
	 */
	private function getBillingAddress () {
		return $this->getOrder ()->getBillingAddress ();
	}



	/**
	 * @return Mage_Payment_Model_Info
	 */
	private function getPayment () {
		return $this->getData (self::PARAM_PAYMENT);
	}


	const PARAM_PAYMENT = 'payment';
	const PARAM_PAYMENT_TYPE = 'Mage_Payment_Model_Info';

	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM_PAYMENT, self::PARAM_PAYMENT_TYPE
			)
		;
	}
}