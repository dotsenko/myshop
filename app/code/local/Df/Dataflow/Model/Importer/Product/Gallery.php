<?php


class Df_Dataflow_Model_Importer_Product_Gallery extends Df_Core_Model_Abstract {



	/**
	 * @return array
	 */
	public function getPrimaryImages () {

		if (!isset ($this->_primaryImages)) {

			$this->_primaryImages = array ();

			foreach ($this->getImageFields () as $primaryImageSize) {
				$imagePath = $this->getImportedValue ($primaryImageSize);
				if (!df_empty($imagePath) && ('no_selection' != $imagePath)) {
					if (!isset($this->_primaryImages[$imagePath])) {
						$this->_primaryImages[$imagePath] = array();
					}
					$this->_primaryImages[$imagePath][] = $primaryImageSize;
				}
			}

			// If there are empty primary image fields,
			// then fill it with the first additional image
			$usedPrimaryImageSizes = array_unique (array_values ($this->_primaryImages));
			$unusedPrimaryImageSizes =
				array_diff (
					$this->getImageFields ()
					,
					$usedPrimaryImageSizes
				)
			;
			if (count ($unusedPrimaryImageSizes) && count ($this->getAdditionalImages ())) {

				$replacement =
					df_a (
						$this->_primaryImages
						,
						$this->getKeyOfMainImage ()
						,
						$this->getAdditionalImageForBorrowing ()
					)
				;

				if ($replacement == $this->getAdditionalImageForBorrowing ()) {
					$this->setAdditionalImageBorrowed (true);
				}

				$this->_primaryImages[$replacement] = $unusedPrimaryImageSizes;

			}
		}

		df_result_array ($this->_primaryImages);

	    return $this->_primaryImages;
	}



	/**
	 * @var array
	 */
	private $_primaryImages;





	/**
	 * @return Df_Dataflow_Model_Importer_Product_Gallery
	 */
	public function addAdditionalImagesToProduct () {
	    $additionalImages =
				!$this->isAdditionalImageBorrowed ()
	        ?
				$this->getAdditionalImages ()
			:
				array_slice (
					$this->getAdditionalImages ()
					,
					1
					,
					-1 + count ($this->getAdditionalImages ())
				)
		;

        foreach ($additionalImages as $additionalImage) {
            $this->getProduct ()->addImageToMediaGallery($additionalImage, null, false, false);
        }
	    return $this;
	}






	/**
	 * @param bool $value
	 * @return Df_Dataflow_Model_Importer_Product_Gallery
	 */
	private function setAdditionalImageBorrowed ($value) {

		df_param_boolean ($value, 0);

		$this->_additionalImageBorrowed = $value;

		return $this;

	}






	/**
	 * @return bool
	 */
	private function isAdditionalImageBorrowed () {
	
		/** @var bool $result  */
		$result = 		
			$this->_additionalImageBorrowed
		;	
	
		df_assert_boolean ($result);
	
		return $result;
	
	}
	
	
	/**
	* @var bool
	*/
	private $_additionalImageBorrowed = false;







	/**
	 * @return array
	 */
	public function getAdditionalImages () {
		if (!isset ($this->_additionalImages)) {
			$this->_additionalImages =
				df_clean (
					array_map (
						array ($this, "removeNonExistent")
						,
						array_map (
							array ($this, "processRawImage")
							,
							$this->getAdditionalImagesAsRawArray ()
						)
					)
				)
			;
		    foreach ($this->_additionalImages as &$image) {
				$image =
					str_replace (
						'\\'
						,
						'/'
						,
						$image
					)
				;
		    }
		}

		return $this->_additionalImages;
	}


	/**
	 * @var array
	 */
	private $_additionalImages;








	/**
	 * @param  string $imagePath
	 * @return string|null
	 */
	public function removeNonExistent ($imagePath) {
		$result =
				!is_file ($imagePath)
			?
				NULL
			:
				$imagePath
		;

		if (!$result) {
			df_log (sprintf ("Imported image file %s does not exist", $imagePath));
		}

	    return $result;
	}



	/**
	 * @param  string $imagePath
	 * @return null|string
	 */
	public function processRawImage ($imagePath) {
		return
				$this->isUrl($imagePath)
			?
				$this->downloadImage ($imagePath)
			:
				$this->makePathAbsolute ($imagePath)
		;
	}
	
	
	
	
	
	/**
	 * @return array
	 */
	private function getImageFields () {
	
		if (!isset ($this->_imageFields)) {
	
			/** @var array $result  */
			$result = 
				array_keys (
					$this->getProduct()->getMediaAttributes ()
				)
			;
	
	
			df_assert_array ($result);
	
			$this->_imageFields = $result;
	
		}
	
	
		df_result_array ($this->_imageFields);
	
		return $this->_imageFields;
	
	}
	
	
	/**
	* @var array
	*/
	private $_imageFields;	
	
	
	
	


	/**
	 * @param  string $imagePath
	 * @return null|string
	 */
	private function makePathAbsolute ($imagePath) {
		return
				df_empty ($imagePath)
			?
				NULL
			:
				sprintf (
					"%s/%s"
					,
					$this->getDestinationBaseDir ()
					,
					df_trim (
						$imagePath
						,
						'/'
					)
				)
		;
	}



	/**
	 * @var string
	 */
	private $_keyOfMainImage;

	/**
	 * @return string
	 */
	private function getKeyOfMainImage () {
		if (!isset ($this->_keyOfMainImage)) {
			$this->_keyOfMainImage = df_a (array_values ($this->getImageFields ()), 0);
		}
	    return $this->_keyOfMainImage;
	}


	/**
	 * @return string
	 */
	private function getAdditionalImagesAsString () {
		return $this->getImportedValue ("df_additional_images");
	}


	/**
	 * @var array
	 */
	private $_additionalImagesAsRawArray;


	/**
	 * @return array
	 */
	private function getAdditionalImagesAsRawArray () {
		if (!isset ($this->_additionalImagesAsRawArray)) {
 	        $this->_additionalImagesAsRawArray =
			    df_clean (
					array_map (
						"df_trim"
						,
						explode (
							$this->getRawImagesDelimiter ()
							,
							$this->getAdditionalImagesAsString ()
						)
					)
			    )
			;
		}
		return $this->_additionalImagesAsRawArray;
	}





	/**
	 * @var string
	 */
	private $_additionalImageForBorrowing;

	/**
	 * @return string
	 */
	private function getAdditionalImageForBorrowing () {
		if (!isset ($this->_additionalImageForBorrowing)) {

			$this->_additionalImageForBorrowing =
					!count ($this->getAdditionalImagesAsRawArray ())
				?
					NULL
				:
					$this->addLeadingSlash (
						df_a ($this->getAdditionalImagesAsRawArray (), 0)
					)
			;
		}
	    return $this->_additionalImageForBorrowing;
	}



	/**
	 * @param  string $path
	 * @return string
	 */
	private function addLeadingSlash ($path) {
		return
				('/' == substr ($path, 0, 1))
			?
				$path
			:
				'/' . $path
		;
	}



	/**
	 * @return string
	 */
	private function getRawImagesDelimiter () {
		$defaultDelimiter = ";";
		return
				false === strpos ($this->getAdditionalImagesAsString (), $defaultDelimiter)
			?
				","
			:
				$defaultDelimiter
		;
	}


	/**
	 * @param  string $string
	 * @return bool
	 */
	private function isUrl ($string) {
		return "http" == strtolower (substr ($string, 0, 4));
	}


	/**
	 * @param  string $imagePath
	 * @return null|string
	 */
	private function downloadImage ($imagePath) {

		$result =
			sprintf (
				"%s/%s"
				,
				$this->getDestinationBaseDir ()
				,
				basename ($imagePath)
			)
		;

		try {
			$this
				->download (
					$imagePath
					,
					$result
				)
			;
		}
		catch (Exception $e) {
			df_handle_entry_point_exception ($e, false);
		    $result = NULL;
		}

	    return $result;
	}


	/**
	 * @return string
	 */
	private function getDestinationBaseDir () {
		return Mage::getBaseDir('media') . DS . 'import';
	}


	/**
	 * @throws #CException|?
	 * @param  string $file_source
	 * @param  string $file_target
	 * @return Df_Dataflow_Model_Importer_Product_Gallery
	 */
	private function download ($file_source, $file_target) {
		$rh = NULL;
		$wh = NULL;
		try {
			$rh = fopen ($file_source, 'rb');
			df_assert (false !== $rh, sprintf ("Failed to download url: %s", $file_source));

			$wh = fopen ($file_target, 'wb');
			df_assert (false !== $wh, sprintf ("Failed to create file: %s", $file_target));

			while (!feof($rh)) {
				$r = fwrite($wh, fread($rh, 1024));
				df_assert (
					false !== $r
					,
					sprintf (
						"Error while downloading url %s to destination %s"
						,
						$file_source
						,
						$file_target
					)
				)
				;
			}
		}
		catch (Exception $e) {
			fclose($rh);
			fclose($wh);
		    throw $e;
		}
	    return $this;
	}



	/**
	 * @param string $key
	 * @param string|null $default
	 * @return string
	 */
	protected function getImportedValue ($key, $default = NULL) {
		return df_a ($this->getImportedRow (), $key, $default);
	}





	/**
	 * @return Mage_Catalog_Model_Product
	 */
	protected function getProduct () {
		return $this->getData (self::PARAM_PRODUCT);
	}


	/**
	 * @return array
	 */
	protected function getImportedRow () {
		return $this->getData (self::PARAM_IMPORTED_ROW);
	}






	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Dataflow_Model_Importer_Product_Gallery';
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
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
	        ->validateClass (
				self::PARAM_PRODUCT, Df_Catalog_Const::PRODUCT_CLASS
			)
	        ->addValidator (
				self::PARAM_IMPORTED_ROW, new Df_Zf_Validate_Array ()
			)
		;
	}




	const PARAM_PRODUCT = 'product';
	const PARAM_IMPORTED_ROW = 'importedRow';


}