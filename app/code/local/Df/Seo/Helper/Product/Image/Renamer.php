<?php


class Df_Seo_Helper_Product_Image_Renamer extends Mage_Core_Helper_Abstract {


	/**
	 * @param  string $initialFileName
	 * @param  Mage_Catalog_Model_Product $product
	 * @return string
	 */
	public function getSeoFileName ($initialFileName, Mage_Catalog_Model_Product $product) {

		df_param_string ($initialFileName, 0);
		df_assert ($product instanceof Mage_Catalog_Model_Product);


		/** @var array $fileInfo */
		$fileInfo = pathinfo ($initialFileName);

		df_assert_array ($fileInfo);


		/** @var string $dirname  */
		$dirname = df_a ($fileInfo, 'dirname');

		df_assert_string ($dirname);



		/** @var string $extension  */
		$extension = df_a ($fileInfo, 'extension');

		df_assert_string ($extension);



		$result =
			implode (
				DS
				,
				df_clean (
					array (
						$dirname
						,
						implode (
							Df_Core_Const::T_FILE_EXTENSION_SEPARATOR
							,
							df_clean (
								array (
									df_output()
										->transliterate (
											$product->getName ()
										)
									,
									$extension
								)
							)
						)
					)
				)
			)
		;


		df_result_string ($result);

		return $result;

	}



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Seo_Helper_Product_Image_Renamer';
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
