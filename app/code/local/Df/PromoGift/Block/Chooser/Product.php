<?php


class Df_PromoGift_Block_Chooser_Product extends Df_Core_Block_Template {




	/**
	 * @return string
	 */
	public function getButtonCaption () {

		/** @var string $result  */
		$result =
				df_cfg()->promotion()->gifts()->enableAddToCartButton()
			?
				df_mage()->catalogHelper()->__ ('Add to Cart')
			:
				$this->__ ('Подробнее...')
		;


		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getButtonTitle () {

		/** @var string $result  */
		$result =
				df_cfg()->promotion()->gifts()->enableAddToCartButton()
			?
				df_mage()->catalogHelper()->__ ('Add to Cart')
			:
				$this->getName ()
		;


		df_result_string ($result);

		return $result;

	}




	/**
	 * @return string
	 */
	public function getButtonUrl () {

		/** @var string $result  */
		$result =
				df_cfg()->promotion()->gifts()->enableAddToCartButton()
			?
				df_mage()->checkout()->cartHelper()->getAddUrl (
					$this->getProduct()
				)
			:
				$this->getDetailsUrl ()
		;


		df_result_string ($result);

		return $result;

	}






	/**
	 * Возвращает название товара
	 *
	 * @return string
	 */
	public function getName () {

		$result = $this->getProduct()->getName ();

		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_string ($result);
		/*************************************/


		return $result;

	}





	/**
	 * Возвращает адрес товарной страницы
	 *
	 * @return string
	 */
	public function getDetailsUrl () {

		$result = $this->getProduct()->getProductUrl();

		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_string ($result);
		/*************************************/


		return $result;

	}






	/**
	 * @return Mage_Catalog_Model_Product
	 */
	public function getProduct () {

		$result = $this->_product;

		df_assert ($result instanceof Mage_Catalog_Model_Product);

		return $result;
	}





	/**
	 * @param Mage_Catalog_Model_Product $product
	 * @return Df_PromoGift_Block_Chooser_Product
	 */
	public function setProduct (Mage_Catalog_Model_Product $product) {
		$this->_product = $product;
		return $this;
	}


	/**
	 * @var Mage_Catalog_Model_Product
	 */
	private $_product;





	/**
	 * Возвращает адрес миниатюрной картинки товара
	 *
	 * @param int $size
	 * @return string
	 */
	public function getThumbnailUrl ($size) {

		/*************************************
		 * Проверка входных параметров метода
		 */
		df_param_integer ($size, 0);
		/*************************************/


		$this->getImageHelper ()->init($this->getProduct (), self::SMALL_IMAGE);
		$result =
			(string)
				$this->getImageHelper ()
					->resize ($size)
		;


		/*************************************
		 * Проверка результата работы метода
		 */
		df_result_string ($result);
		/*************************************/


		return $result;

	}






	/**
	 * @return Mage_Catalog_Helper_Image
	 */
	private function getImageHelper () {
		return df_mage ()->catalogImageHelper();
	}



	const SMALL_IMAGE = 'small_image';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_PromoGift_Block_Chooser_Product';
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


}

