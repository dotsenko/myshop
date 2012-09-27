<?php

class Df_Banner_Block_Banner extends Df_Core_Block_Template {


	/**
	 * @return Df_Banner_Model_Banner
	 */
	public function getBanner () {

		if (!isset ($this->_banner)) {

			/** @var Df_Banner_Model_Banner $result  */
			$result =
				df_model (
					Df_Banner_Model_Banner::getNameInMagentoFormat()
				)
			;

			df_assert ($result instanceof Df_Banner_Model_Banner);


			$result->load ($this->getBannerId(), 'identifier');


			$this->_banner = $result;

		}


		df_assert ($this->_banner instanceof Df_Banner_Model_Banner);

		return $this->_banner;

	}


	/**
	* @var Df_Banner_Model_Banner
	*/
	private $_banner;





	/**
	 * @return string
	 */
	public function getBannerId () {

		/** @var string $result  */
		$result = $this->cfg (self::PARAM__ID);

		if (is_null ($result)) {
			df_error (
				'Укажите идентификатор рекламного щита'
			);
		}

		df_result_string ($result);

		return $result;

	}




	/**
	 * @param Df_Banner_Model_Banneritem $bannerItem
	 * @return string
	 */
	public function getBannerItemImageUrl (Df_Banner_Model_Banneritem $bannerItem) {

		/** @var string $result  */
		$result =
				is_null ($bannerItem->getImageFileName())
			?
				$bannerItem->getImageUrl()
			:
				implode (
					Df_Core_Const::T_EMPTY
					,
					array (
						Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)
						,
						$bannerItem->getImageFileName()
					)
				)
		;


		df_result_string ($result);

		return $result;

	}


	

	
	/**
	 * @return Df_Banner_Model_Resource_Banneritem_Collection
	 */
	public function getBannerItems () {
	
		if (!isset ($this->_bannerItems)) {
	
			/** @var Df_Banner_Model_Resource_Banneritem_Collection $result  */
			$result = 
				Mage::getResourceModel (
					Df_Banner_Model_Resource_Banneritem_Collection::getNameInMagentoFormat()
				)
			;
	
			df_assert ($result instanceof Df_Banner_Model_Resource_Banneritem_Collection);


			$result->addFieldToFilter ('status', true);
			$result->addFieldToFilter ('banner_id', $this->getBanner()->getId());
			$result->setOrder ('banner_order', 'ASC');

	
			$this->_bannerItems = $result;
	
		}
	
	
		df_assert ($this->_bannerItems instanceof Df_Banner_Model_Resource_Banneritem_Collection);
	
		return $this->_bannerItems;
	
	}
	
	
	/**
	* @var Df_Banner_Model_Resource_Banneritem_Collection
	*/
	private $_bannerItems;	




	/**
	 * @return bool
	 */
	public function isVisible () {

		/** @var bool $result */
		$result =
				!is_null ($this->getBanner())
			&&
				$this->getBanner()->isEnabled()
		;

		df_result_boolean ($result);

		return $result;
	}




	/**
	 * @override
	 * @return bool
	 */
	protected function needToShow () {

		/** @var bool $result  */
		$result =
				parent::needToShow()
			&&
				df_enabled (Df_Core_Feature::BANNER)
			&&
				df_cfg()->promotion()->banners()->getEnabled()
			&&
				$this->getBanner()->isEnabled()
			&&
				count ($this->getBannerItems())
		;


		df_result_boolean ($result);

		return $result;

	}





	const PARAM__ID = 'banner_id';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Banner_Block_Banner';
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