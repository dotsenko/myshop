<?php


class Df_Seo_Model_Product_Gallery_Processor_Image_Exif extends Df_Core_Model_Abstract {



	/**
	 * @return Df_Seo_Model_Product_Gallery_Processor_Image_Exif
	 */
	public function process () {
		
		if ($this->isEligibleForExif ()) {
			if (
					file_exists (
						$this->getImagePath ()
					)
				&&
					$this->getProduct ()
			) {
				df_helper()->pel()->lib()->setCompatibleErrorReporting ();

				try {
					$data =
						new PelDataWindow (
							file_get_contents (
								$this->getImagePath ()
							)
						)
					;


					/** @var bool $imageIsValid  */
					$imageIsValid = false;

					try {
						/**
						 * При вызове PelJpeg::isValid библиотека Pel может давать сбой:
						 * «Offset 0 not within [0, -1]»
						 */
						$imageIsValid = PelJpeg::isValid ($data);
					}
					catch (Exception $e) {

					}

					if ($imageIsValid) {

						$jpeg = new PelJpeg ();
						$jpeg->load ($data);
						$exif = $jpeg->getExif();

						if (null == $exif) {
							$exif = new PelExif();
							$jpeg->setExif($exif);
							$tiff = new PelTiff();
							$exif->setTiff($tiff);
						}

						$tiff = $exif->getTiff();

						$ifd0 = $tiff->getIfd();
						if (null == $ifd0) {
							$ifd0 = new PelIfd(PelIfd::IFD0);
							$tiff->setIfd($ifd0);
						}

						$ifdExif = new PelIfd(PelIfd::EXIF);
						$ifd0->addSubIfd ($ifdExif);


						$description =
							html_entity_decode (
								strip_tags (
									$this->getProduct ()->getDescription ()
								)
							)
						;

						$title = $this->getProduct ()->getName ();

						//$author = Mage::getStoreConfig('design/head/default_title');
						$copyright = Mage::getStoreConfig('design/footer/copyright');
						$keywords = $this->getProduct ()->getMetaKeyword();

						$categoryIds = $this->getProduct ()->getCategoryIds();
						$subject =
								empty ($categoryIds)
							?
								$title
							:
								df_model('catalog/category')
									->load(
										df_a ($categoryIds, 0)
									)
										->getName()
						;



						$ifd0->addEntry(new PelEntryAscii(PelTag::IMAGE_DESCRIPTION, $title));

						//$ifd0->addEntry(new PelEntryAscii(PelTag::MAKE, "MAKE"));


						$ifd0
							->addEntry(
								new PelEntryCopyright(
									strtr (
										$copyright
										,
										array (
											"&copy;" => "(c)"
											,
											"©" => "(c)"
											,
											'{currentYear}' =>
												Zend_Date::now ()->toString (
													Zend_Date::YEAR
												)
										)
									)
								)
							)
						;


						$ifd0
							->addEntry(
								new PelEntryWindowsString (
									PelTag::XP_KEYWORDS
									,
									$keywords
								)
							)
						;

						$ifd0
							->addEntry(
								new PelEntryWindowsString (
									PelTag::XP_SUBJECT
									,
									$subject
								)
							)
						;


						$ifd0
							->addEntry(
								new PelEntryWindowsString (
									PelTag::XP_AUTHOR
									,
									Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)
								)
							)
						;


						$ifdExif
							->addEntry(
								new PelEntryUserComment(
									$this->toAnsi ($description)
								)
							)
						;




//						$ifdExif
//							->addEntry(
//								new PelEntryTime (
//									PelTag::DATE_TIME_ORIGINAL
//									,
//									time () - 7 * 24 * 3600
//								)
//							)
//						;


						$ifdExif
							->addEntry(
								new PelEntryVersion(PelTag::EXIF_VERSION, 2.2)
							)
						;


						$ifdInterop= new PelIfd(PelIfd:: INTEROPERABILITY);
						$ifd0->addSubIfd ($ifdInterop);

						file_put_contents($this->getImagePath (), $jpeg->getBytes());
					}

					df_helper()->pel()->lib()->restoreErrorReporting ();
				}
				catch (Exception $e) {
					df_handle_entry_point_exception ($e, false);
				}
			}
		}

		return $this;
	}




	/**
	 * @param  string $string
	 * @return string
	 */
	private function toAnsi ($string) {
		return
			iconv (
				"UTF-8"
				,
				"windows-1251//IGNORE"
				,
				$string
			)
		;
	}



	/**
	* @return bool
	*/
	private function isEligibleForExif () {
		return
			in_array (
				strtolower (
					df_a (pathinfo($this->getImagePath ()), 'extension')
				)
				,
				// Today, we supports only JPEG images...
				array ("jpg", "jpeg")
			)
		;
	}


	/**
	 * @return string
	 */
	private function getImagePath () {
		return $this->cfg (self::PARAM_IMAGE_PATH);
	}


	/**
	 * @return Mage_Catalog_Model_Product|null
	 */
	private function getProduct () {
		return $this->cfg (self::PARAM_PRODUCT);
	}







	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
	        ->addValidator (self::PARAM_IMAGE_PATH, new Zend_Validate_NotEmpty ())
	        ->addValidator (self::PARAM_IMAGE_PATH, new Df_Zf_Validate_String() )
	        ->validateClass (self::PARAM_PRODUCT, self::PARAM_PRODUCT_TYPE, false)
		;
	}



	const PARAM_PRODUCT = 'product';
	const PARAM_PRODUCT_TYPE = 'Mage_Catalog_Model_Product';

	const PARAM_IMAGE_PATH = 'imagePath';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Model_Product_Gallery_Processor_Image_Exif';
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