<?php

class Df_Banner_Model_Banner extends Df_Core_Model_Abstract {



	/**
	 * @return int
	 */
	public function getDelay () {

		/** @var int $result  */
		$result =
			intval (
				$this->cfg (self::PARAM__DELAY)
			)
		;

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	public function getSizeHeight () {

		/** @var int $result  */
		$result =
			intval (
				$this->cfg (
					self::PARAM__SIZE__HEIGHT
				)
			)
		;

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @return int
	 */
	public function getSizeWidth () {

		/** @var int $result  */
		$result =
			intval (
				$this->cfg (
					self::PARAM__SIZE__WIDTH
				)
			)
		;

		df_result_integer ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getTitle () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__TITLE);

		df_result_string ($result);

		return $result;

	}




	/**
	 * @return bool
	 */
	public function isEnabled () {

		/** @var bool $result  */
		$result =
			(
					1
				===
					intval (
						$this->cfg (self::PARAM__IS_ENABLED)
					)
			)
		;

		df_result_boolean ($result);

		return $result;

	}





	/**
	 * @return bool
	 */
	public function needShowTitle () {

		/** @var bool $result  */
		$result =
			(
					1
				===
					intval (
						$this->cfg (self::PARAM__NEED_SHOW_TITLE)
					)
			)
		;

		df_result_boolean ($result);

		return $result;

	}




	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this->_init('df_banner/banner');
	}



	const PARAM__DELAY = 'delay';

	const PARAM__IS_ENABLED = 'status';

	const PARAM__NEED_SHOW_TITLE = 'show_title';

	const PARAM__SIZE__HEIGHT = 'height';
	const PARAM__SIZE__WIDTH = 'width';

	const PARAM__TITLE = 'title';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Banner_Model_Banner';
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