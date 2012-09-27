<?php


class Df_Dataflow_Model_Convert_Mapper_Url_Key extends Df_Dataflow_Model_Convert_Mapper_Abstract {



	/**
	 * @return string
	 */
	protected function getFeatureCode () {

		/** @var string $result  */
		$result = Df_Core_Feature::SEO;

		df_result_string ($result);

		return $result;
	}




	/**
	 * @param array $row
	 * @return array
	 */
	protected function processRow (array $row) {

		df_param_array ($row, 0);

		/** @var array $result  */
		$result =
			array_merge (
				$row
				,
				array (
					self::ATTRIBUTE_URL_KEY =>
						$this->getUrlHelper()->extendedFormat (
							df_a ($row, self::ATTRIBUTE_NAME)
						)
				)
			)
		;

		df_result_array ($result);

		return $result;
	}






	/**
	 * @return Df_Catalog_Helper_Product_Url
	 */
	private function getUrlHelper () {

		if (!isset ($this->_urlHelper)) {

			/** @var Df_Catalog_Helper_Product_Url $result  */
			$result =
				df_helper()->catalog()->product()->url()
			;


			df_assert ($result instanceof Df_Catalog_Helper_Product_Url);

			$this->_urlHelper = $result;

		}


		df_assert ($this->_urlHelper instanceof Df_Catalog_Helper_Product_Url);

		return $this->_urlHelper;

	}


	/**
	* @var Df_Catalog_Helper_Product_Url
	*/
	private $_urlHelper;




	const ATTRIBUTE_URL_KEY = 'url_key';
	const ATTRIBUTE_NAME = 'name';


}