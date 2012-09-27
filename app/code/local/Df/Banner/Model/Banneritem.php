<?php

class Df_Banner_Model_Banneritem extends Df_Core_Model_Abstract {



	/**
	 * @return string
	 */
	public function getContent () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__CONTENT, Df_Core_Const::T_EMPTY);

		df_result_string ($result);

		return $result;

	}



	/**
	 * @return string|null
	 */
	public function getImageFileName () {

		/** @var string|null $result  */
		$result = $this->cfg (self::PARAM__IMAGE__FILE_NAME);

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}




	/**
	 * @return string|null
	 */
	public function getImageUrl () {

		/** @var string|null $result  */
		$result = $this->cfg (self::PARAM__IMAGE__URL);

		if (!is_null ($result)) {
			df_result_string ($result);
		}

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
	 * @return string|null
	 */
	public function getThumbnailFileName () {

		/** @var string|null $result  */
		$result = $this->cfg (self::PARAM__THUMBNAIL__FILE_NAME);

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}




	/**
	 * @return string|null
	 */
	public function getThumbnailUrl () {

		/** @var string|null $result  */
		$result = $this->cfg (self::PARAM__THUMBNAIL__URL);

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}
	



	/**
	 * @return string
	 */
	public function getUrl () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__URL);

		df_result_string ($result);

		return $result;

	}





	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this->_init('df_banner/banneritem');
	}




	const PARAM__CONTENT = 'content';

	const PARAM__IMAGE__FILE_NAME = 'image';
	const PARAM__IMAGE__URL = 'image_url';

	const PARAM__TITLE = 'title';

	const PARAM__THUMBNAIL__FILE_NAME = 'thumb_image';
	const PARAM__THUMBNAIL__URL = 'thumb_image_url';

	const PARAM__URL = 'link_url';





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Banner_Model_Banneritem';
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