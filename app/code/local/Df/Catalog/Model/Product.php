<?php


class Df_Catalog_Model_Product extends Mage_Catalog_Model_Product {



	/**
	 * @return Df_Catalog_Model_Product
	 */
	public function deleteImages () {

		/** @var array $attributes */
		$attributes = $this->getTypeInstance()->getSetAttributes();

		df_assert_array ($attributes);


		/** @var Mage_Eav_Model_Entity_Attribute_Abstract|null $mediaGalleryAttribute */
		$mediaGalleryAttribute = df_a ($attributes, self::PARAM__MEDIA_GALLERY);

		if (!is_null ($mediaGalleryAttribute)) {

			df_assert ($mediaGalleryAttribute instanceof Mage_Eav_Model_Entity_Attribute_Abstract);

			if (is_array ($this->getMediaGallery())) {

				$this->getMediaGalleryImages();

				/** @var array|null $images */
				$images = df_a ($this->getMediaGallery(), 'images');

				if (is_array ($images)) {

					/** @var Mage_Catalog_Model_Product_Attribute_Backend_Media $backend */
					$backend = $mediaGalleryAttribute->getBackend();

					df_assert ($backend instanceof Mage_Catalog_Model_Product_Attribute_Backend_Media);

					foreach($images as $image){

						/** @var string|null $fileName */
						$fileName = df_a ($image, 'file');

						if ($backend->getImage($this, $fileName)) {
							$backend->removeImage($this, $fileName);
						}
					}
				}
			}
		}

	    return $this;
	}





	/**
	 * @return Df_Catalog_Model_Product
	 */
	public function deleteOptions () {

		df_assert_array ($this->getOptions ());

		foreach ($this->getOptions () as $option) {

			/** @var Df_Catalog_Model_Product_Option $option */

			df_assert ($option instanceof Df_Catalog_Model_Product_Option);

			$option->deleteWithDependencies ();
		}

		return $this;
	}



	/**
	 * @return int
	 */
	public function getAttributeSetId () {

		/** @var int $result  */
		$result = intval ($this->getData ('attribute_set_id'));

		df_result_integer ($result);

		return $result;
	}





	/**
	 * @return string
	 */
	public function getDescription () {

		/** @var string $result  */
		$result =
			df_convert_null_to_empty_string (
				$this->getData (self::PARAM__DESCRIPTION)
			)
		;

		df_result_string ($result);

		return $result;
	}



	/**
	 * Обратите внимание, что стандартный программный код иногда использует синтаксис:
	 * $this->getMediaGallery('images')
	 *
	 * Наш метод тоже поддерживает этот синтаксис.
	 *
	 * @param string|null $key [optional]
	 * @return array|null
	 */
	public function getMediaGallery ($key = null) {

		/** @var array|null $result */
		$result = null;

		/** @var array|null $mediaGallery  */
		$mediaGallery = $this->getData (self::PARAM__MEDIA_GALLERY);

		if (!is_null ($mediaGallery)) {

			df_assert_array ($mediaGallery);

			$result =
					is_null ($key)
				?
					$mediaGallery
				:
					df_a ($mediaGallery, $key)
			;
		}

		if (!is_null ($result)) {
			df_result_array ($result);
		}

		return $result;
	}





	/**
	 * @return string
	 */
	public function getMetaTitle () {
		return
				df_enabled (Df_Core_Feature::SEO)
			?
	            $this->getMetaTitleDf ()
			:
				parent::getData (self::ATTRIBUTE_META_TITLE)
		;
	}




	/**
	 * @return string
	 */
	public function getName () {

		/** @var string $result  */
		$result =
			df_convert_null_to_empty_string (
				$this->getData (self::PARAM__NAME)
			)
		;

		df_result_string ($result);

		return $result;
	}




	/**
	 * @param  string $title
	 * @return array
	 */
	public function getOptionsByTitle ($title) {

		df_param_string ($title, 0);

		$result = array ();

		df_assert_array ($this->getOptions ());

		foreach ($this->getOptions () as $option) {

			/** @var Df_Catalog_Model_Product_Option $option */

			df_assert ($option instanceof Df_Catalog_Model_Product_Option);

			if (
					$title
				==
					$option->getDataUsingMethod (
						Df_Catalog_Const::PRODUCT_OPTION_PARAM_TITLE
					)
			) {
				$result[] = $option;
			}
		}

		return $result;
	}




	/**
	 * @param  string $text
	 * @return Df_Seo_Model_Template_Processor
	 */
	private function createTextTemplateProcessor ($text) {
		return
			df_model (
				Df_Seo_Model_Template_Processor::getNameInMagentoFormat()
				,
				array (
					/**
					 * Явно приводим $text к типу string,
					 * потому что $text в настоящий момент может быть равен NULL
					 */
					Df_Seo_Model_Template_Processor::PARAM_TEXT => (string)$text
					,
					Df_Seo_Model_Template_Processor::PARAM_OBJECTS =>
						array (
							"product" => $this
						)
				)
			)
		;
	}




	/**
	 * @return string|null
	 */
	private function getCategoryTail () {
		return
				!$this->getCurrentCategory ()
			?
				NULL
			:
				$this->getCurrentCategory ()->getName ()
		;
	}




	/**
	 * @return boolean
	 */
	private function isCategoryTailEnabled () {
		return
			df_cfg ()->seo()->html()->getAppendCategoryNameToProductTitleTag ()
		;
	}



	/**
	 * @return string|null
	 */
	private function getCategoryTailIfEnabled () {
		return
				!$this->isCategoryTailEnabled ()
			?
				null
			:
				$this->getCategoryTail ()
		;
	}



	/**
	 * @return Mage_Catalog_Model_Category|null
	 */
	private function getCurrentCategory () {
		return Mage::registry(Df_Catalog_Const::REGISTRY_CURRENT_CATEGORY);
	}




	/**
	 * @return string
	 */
	private function getRawMetaTitle () {
		return
				!df_empty (parent::getData (self::ATTRIBUTE_META_TITLE))
			?
				parent::getData (self::ATTRIBUTE_META_TITLE)
			:
				$this->getDefaultProductRawMetaTitle ()
		;
	}


	/**
	 * @return string
	 */
	private function getDefaultProductRawMetaTitle () {
		return
			df_cfg()->seo()->html()->getDefaultPatternForProductTitleTag()
		;
	}



	/**
	 * @return string
	 */
	private function getMetaTitleDf () {
		return
			implode (
				" - "
				,
				df_clean (
					array (
						$this
							->processPatterns (
								$this->getRawMetaTitle ()
							)
						,
						$this->getCategoryTailIfEnabled ()
					)
				)
			)
		;
	}



	/**
	 * @param  string $text
	 * @return string
	 */
	private function processPatterns ($text) {
		return $this->createTextTemplateProcessor($text)->process ();
	}



	const ATTRIBUTE_META_TITLE = 'meta_title';

	const PARAM__DESCRIPTION = 'description';
	const PARAM__MEDIA_GALLERY = 'media_gallery';
	const PARAM__NAME = 'name';
	const PARAM__STORE_ID = 'store_id';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Catalog_Model_Product';
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