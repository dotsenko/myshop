<?php

class Df_Catalog_Model_Product_Attribute_Backend_Media extends Mage_Catalog_Model_Product_Attribute_Backend_Media
{

	/**
	 * @override
	 * @param  Mage_Catalog_Model_Product $object
	 * @return Mage_Catalog_Model_Product_Attribute_Backend_Media
	 */
    public function beforeSave ($object)
    {
        $this->setProduct ($object);

        parent::beforeSave ($object);

		return $this;
    }


	/**
	 * @override
	 * @param  string $file
	 * @return string
	 */
    protected function _moveImageFromTmp ($file)
    {
        return
				(df_enabled (Df_Core_Feature::SEO) && df_cfg()->seo()->images()->getUseDescriptiveFileNames())
			?
				$this->_moveImageFromTmpDf ($file)
			:
				parent::_moveImageFromTmp ($file)
		;
    }







	/**
	 * @param  string $file
	 * @return string
	 */
    protected function _moveImageFromTmpDf ($file) {


		df_param_string ($file, 0);


		/** @var Varien_Io_File $ioObject  */
	    $ioObject = new Varien_Io_File ();

		df_assert ($ioObject instanceof Varien_Io_File);


		/** @var string $destDirectory  */
		$destDirectory =
			dirname (
				$this->_getConfig()->getMediaPath (
					$file
				)
			)
		;

		df_assert_string ($destDirectory);






        try {

            $ioObject->open(array('path'=>$destDirectory));

        }
		catch (Exception $e) {

			try {
				$ioObject->mkdir($destDirectory, 0777, true);
				$ioObject->open(array('path'=>$destDirectory));
			}
			catch (Exception $e) {

				df_error (
					strtr (
								"[%method%]:\nСогласно текущему алгоритму,"
							.
								"\nсистема должна переместить загруженную картинку в папку"
							.
								"\n«%destionationDirectory%»"
							.
								"\nОднако, папка «%destionationDirectory%» отсутствует на сервере,"
							.
								"\nи система не в состоянии её вручную создать по причине:\n«%exceptionMessage%»."
						,
							array (
								'%method%' => __METHOD__
								,
								'%destionationDirectory%' => $destDirectory
								,
								'%exceptionMessage%»' => $e->getMessage ()
							)

					)
				)
				;

			}


        }



        if (strrpos($file, '.tmp') == strlen($file)-4) {

            $file = substr($file, 0, strlen($file)-4);

			df_assert_string ($file);

        }




		/** @var string $destionationFilePath */
		$destionationFilePath =
			df_helper ()->core ()->path ()->adjustSlashes (
				$this->_getConfig()->getMediaPath(
					$file
				)
			)
		;

		df_assert_string ($destionationFilePath);





		/** @var Mage_Catalog_Model_Product $product  */
		$product = $this->getProduct ();

		df_assert ($product instanceof Mage_Catalog_Model_Product);


		/** @var string $destionationFilePathOptimizedForSeo */
		$destionationFilePathOptimizedForSeo =
			df_helper ()->seo ()->getProductImageRenamer ()
				->getSeoFileName (
					$destionationFilePath
					,
					$product
				)
		;



		df_assert_string ($destionationFilePathOptimizedForSeo);



		/** @var string $destionationFilePathOptimizedForSeoAndUnique  */
		$destionationFilePathOptimizedForSeoAndUnique =
			df_helper ()->core ()->file ()
			    ->getUniqueFileName (
					$destionationFilePathOptimizedForSeo
				)
		;

		df_assert_string ($destionationFilePathOptimizedForSeoAndUnique);



		/**
		 * Раз путь к файлу - уникален, значит, не должно быть уде загруженного файла с таким путём
		 */
		df_assert (
			!is_file ($destionationFilePathOptimizedForSeoAndUnique)
		)
		;



		/** @var string $sourceFilePath  */
		$sourceFilePath =
			$this->_getConfig()->getTmpMediaPath (
				$file
			)
		;


		df_assert_string ($sourceFilePath);

		df_assert (
			is_file (
				$sourceFilePath
			)
			,
			strtr (
						"[%method%]:\nСогласно текущему алгоритму,"
					.
						"\nсистема должна была временно сохранить загруженную картинку по пути"
					.
						"\n«%sourceFilePath%»"
					.
						"\nОднако, файл «%sourceFilePath%» отсутствует на сервере."
				,
					array (
						'%method%' => __METHOD__
						,
						'%sourceFilePath%' => $sourceFilePath
					)

			)
		)
		;




		/** @var bool $r */
		$r =
			$ioObject
				->mv (
					$this->_getConfig()->getTmpMediaPath($file)
					,
					$destionationFilePathOptimizedForSeoAndUnique
				)
		;


		df_assert_boolean ($r);


		df_assert (

			(TRUE === $r) && (is_file ($destionationFilePathOptimizedForSeoAndUnique))

			,

			strtr (
						"[%method%]:\nСистеме не удалось переместить файл"
					.
						"\nс пути «%sourceFilePath%»"
					.
						"\nна путь «%destionationFilePathOptimizedForSeoAndUnique%»."
				,
					array (
						'%method%' => __METHOD__
						,
						'%sourceFilePath%' => $sourceFilePath
						,
						'%destionationFilePathOptimizedForSeoAndUnique%' =>
							$destionationFilePathOptimizedForSeoAndUnique
					)

			)
		)
		;





        $result =
			str_replace (
				$ioObject->dirsep()
				,

				/**
				 * Похоже, в качества разделителя частей пути в данном случае
				 * надо всегда использовать именно символ /
				 */
				'/'
				,
				str_replace (
					df_helper ()->core ()->path ()->adjustSlashes (
						$this->_getConfig()->getBaseMediaPath() . DS
					)
					,
					Df_Core_Const::T_EMPTY
					,
					$destionationFilePathOptimizedForSeoAndUnique
				)
			)
		;



		df_result_string ($result);

		return $result;
    }






	/**
	 * @param  Mage_Catalog_Model_Product $product
	 * @return Df_Catalog_Model_Product_Attribute_Backend_Media
	 */
	private function setProduct (Mage_Catalog_Model_Product $product) {
		$this->_product = $product;
		return $this;
	}

	/**
	 * @return Mage_Catalog_Model_Product
	 */
	private function getProduct () {
		return $this->_product;
	}



	/**
	 * @var Mage_Catalog_Model_Product
	 */
	private $_product;

}
