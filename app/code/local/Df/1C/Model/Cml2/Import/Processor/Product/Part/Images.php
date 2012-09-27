<?php

class Df_1C_Model_Cml2_Import_Processor_Product_Part_Images
	extends Df_1C_Model_Cml2_Import_Processor_Product {



	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Import_Processor
	 */
	public function process () {

		/** @var bool $needSave  */
		$needSave = false;

		/** @var bool $needReload  */
		$needReload = false;


		if (is_null ($this->getExistingMagentoProduct())) {
			df_error (
				sprintf (
					'Попытка импорта картинок для отсутствующего в системе товара «%s»'
					,
					$this->getEntityOffer()->getExternalId()
				)
			);
		}

		df_assert (!is_null ($this->getExistingMagentoProduct()));


		/** @var Df_Catalog_Model_Product $product  */
		$product =
			df_model (
				Df_Catalog_Model_Product::getNameInMagentoFormat()
				,
				$this->getExistingMagentoProduct()->getData()
			)
		;

		df_assert ($product instanceof Df_Catalog_Model_Product);

		$product->deleteImages();


		/** @var bool $isPrimaryImage  */
		$isPrimaryImage = true;

		foreach ($this->getEntityProduct()->getImages() as $image) {

			/** @var Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_Image $image */
			df_assert ($image instanceof Df_1C_Model_Cml2_Import_Data_Entity_ProductPart_Image);

			$product
				->addImageToMediaGallery(
					$image->getFilePathFull()
					,
					$isPrimaryImage ? array ('thumbnail','small_image','image') : null
					,
					false
					,
					false
				)
			;

			$isPrimaryImage = false;


			df_helper()->_1c()
				->log (
					sprintf (
						'К товару «%s» добавлена картинка «%s».'
						,
						$product->getName()
						,
						$image->getFilePathRelative()
					)
				)
			;

			$needSave = true;


			/**
			 * Если после добавления к товару картинок не перезагрузить товар,
			 * то при повторном сохранении товара произойдёт исключительная ситуация
			 * (система будет пытаться повторно прикрепить те же самые картинки к товару).
			 *
			 * @todo Наверняка есть более правильное решение, чем перезагрузка товара.
			 */
			$needReload = true;

		}



		if ($needSave) {

			df_helper()->catalog()->product()
				->save (
					$product
					,
					$isMassUpdate = true
				)
			;
		}

		if ($needReload) {
			$product =
				df_helper()->catalog()->product()
					->reload (
						$product
					)
			;
		}



		df_helper()->_1c()
			->log (
				sprintf (
					'%s товар «%s».'
					,
					!is_null ($this->getExistingMagentoProduct()) ? 'Обновлён' : 'Создан'
					,
					$product->getName()
				)
			)
		;


		df_helper()->dataflow()->registry()->products()->addEntity ($product);

		return $this;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Processor_Product_Part_Images';
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


