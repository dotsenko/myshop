<?php


class Df_Seo_Model_Product_Gallery_Processor_Image_Renamer extends Df_Core_Model_Abstract {




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Model_Product_Gallery_Processor_Image_Renamer';
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
	public function process () {

		$result = $this->getImage ()->getPath();

		if (!is_file ($result)) {
			df_log (
				sprintf (
					"Image for product %s does not exist: %s"
					,
					$this->getProduct ()->getName ()
					,
					$result
				)
			)
			;
		}
		else {
			if ($this->needCorrections ()) {

				$correctedFileName = $this->getCorrectedFileName ();

				$r =
					copy (
						$this->getImage ()->getPath()
						,
						$correctedFileName
					)
				;

				if (!$r || !is_file ($correctedFileName)) {
					df_log (sprintf ("Failed to create file: %s", $correctedFileName));
				}
				else {
					$result = $correctedFileName;

					// Add image with new name
					$this
						->getProduct ()
						->addImageToMediaGallery (
							$correctedFileName
							,
							$this->getFields ()
							,
							false
							,
							false
						)
					;

					// Remove previous image
					$this
						->getProduct ()
						->getMediaGalleryAttribute ()
						->getBackend ()
						->removeImage (
							$this->getProduct ()
							,
							$this->getImage ()->getFile ()
						)
					;


					if (
						file_exists (
							$this->getImage ()->getPath ()
						)
					) {
						unlink (
							$this->getImage ()->getPath ()
						)
						;
					}
				}
			}
		}

		return $result;
	}



	private function getCorrectedFileName () {
		$result = $this->getImage ()->getPath ();

		$dirname = df_a ($this->getFileInfo (), 'dirname');
		$extension = df_a ($this->getFileInfo (), 'extension');
		$key = $this->getProduct ()->getImageKey ();

		$i = 1;
		while (1) {
			$result =
				sprintf (
					"%s/%s"
					,
					$dirname
					,
					implode (
						"."
						,
						df_clean (
							array (
								$this->generateOrderedKey ($key, $i++)
								,
								$extension
							)
						)
					)
				)
			;

			if (!file_exists ($result)) {
				break;
			}
		}

		return $result;
	}



	/**
	 * @param  string $key
	 * @param  int $ordering
	 * @return string
	 */
	private function generateOrderedKey ($key, $ordering) {
		return
				(1 == $ordering)
			?
				$key
			:
				implode (
					"-"
					,
					array (
						$key
						,
						$ordering
					)
				)
		;
	}



	/**
	 * @return array
	 */
	private function getFields () {
		$result = array ();
		$fields = array ("image", "small_image", "thumbnail");

		foreach ($fields as $field) {
			if ($this->getImage ()->getFile () == $this->getProduct ()->getData ($field)) {
				$result []= $field;
			}
		}

		return $result;
	}


	/**
	 * @return bool
	 */
	private function needCorrections () {
		return
				0
			!==
				strpos (
					$this->getBaseName ()
					,
					$this->getProduct ()->getImageKey ()
				)
		;
	}


	/**
	 * @return string
	 */
	private function getBaseName () {
		return df_a ($this->getFileInfo (), 'basename');
	}



	/**
	 * @var array
	 */
	private $_fileInfo;

	/**
	 * @return array
	 */
	private function getFileInfo () {
		if (!isset ($this->_fileInfo)) {
			$this->_fileInfo = pathinfo ($this->getImage ()->getPath ());
		}
		return $this->_fileInfo;
	}



	/**
	 * @return Varien_Object
	 */
	private function getImage () {
		return $this->cfg (self::PARAM_IMAGE);
	}


	/**
	 * @return Mage_Catalog_Model_Product
	 */
	private function getProduct () {
		return $this->cfg (self::PARAM_PRODUCT);
	}



	const PARAM_IMAGE = 'image';
	const PARAM_IMAGE_TYPE = 'Varien_Object';

	const PARAM_PRODUCT = 'product';
	const PARAM_PRODUCT_TYPE = 'Mage_Catalog_Model_Product';


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
	    $this
			->validateClass (self::PARAM_IMAGE, self::PARAM_IMAGE_TYPE)
	        ->validateClass (self::PARAM_PRODUCT, self::PARAM_PRODUCT_TYPE)
		;
	}


}