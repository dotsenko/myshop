<?php

class Df_1C_Model_Cml2_Import_Data extends Df_1C_Model_Cml2 {


	/**
	 * Этот блок XML содержит те самые данные,
	 * доступ к которым предоставляет данный оъект
	 *
	 * @return Varien_Simplexml_Element
	 */
	public function getSimpleXmlElement () {
		return $this->cfg (self::PARAM__SIMPLE_XML);
	}




	/**
	 * @return array
	 */
	protected function getAsCanonicalArray () {

		if (!isset ($this->_asCanonicalArray)) {

			/** @var array $result  */
			$result = $this->getSimpleXmlElement()->asCanonicalArray();

			df_assert_array ($result);

			$this->_asCanonicalArray = $result;

		}


		df_result_array ($this->_asCanonicalArray);

		return $this->_asCanonicalArray;

	}


	/**
	* @var array
	*/
	private $_asCanonicalArray;





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->validateClass (
				self::PARAM__SIMPLE_XML, 'Varien_Simplexml_Element'
			)
		;
	}



	const PARAM__SIMPLE_XML = 'simple_xml';


	



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Data';
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
