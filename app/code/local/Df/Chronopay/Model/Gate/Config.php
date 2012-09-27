<?php


class Df_Chronopay_Model_Gate_Config extends Varien_Object
{





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Chronopay_Model_Gate_Config';
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
	 * @param string $field
	 * @param null|string $default
	 * @return null|string
	 */
    public function getParam ($field, $default = NULL) {
		$result =
			$this->getPaymentModel()->getConfigData ($field, NULL)
		;
	    return
	            (NULL === $result)
			?
	            $default
			:
				$result
	    ;
    }


	/**
	 * @return Df_Chronopay_Model_Gate
	 */
	private function getPaymentModel () {
		return
			Mage::getSingleton ("df_chronopay/gate")
		;
	}



	/**
	 * @return string
	 */
    public function getSiteId ()
    {
        return $this->getParam ('site_id');
    }



	/**
	 * @return string
	 */
    public function getProductId ()
    {
        return $this->getParam ('product_id');
    }



	/**
	 * @return string
	 */
    public function getSharedSecret ()
    {
        return $this->getParam ('shared_sec');
    }


	/**
	 * @return string
	 */
    public function getDescription ()
    {
        $description = $this->getParam ('description');
        return $description;
    }


	/**
	 * @return string
	 */
    public function getNewOrderStatus ()
    {
        return $this->getParam ('order_status');
    }



	/**
	 * @return string
	 */
    public function getCurrency ()
    {
        return $this->getParam ('currency');
    }


	/**
	 * @return string
	 */
    public function getLanguage ()
    {
        return $this->getParam ('language');
    }
}