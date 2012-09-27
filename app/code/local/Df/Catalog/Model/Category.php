<?php


class Df_Catalog_Model_Category extends Mage_Catalog_Model_Category {




	/**
	 * @override
	 * @return string|null
	 */
	public function getDescription () {

		/** @var string|null $result  */
		$result = parent::getData ('description');

		/** @var int $pageNumber  */
		$pageNumber = intval (df_request ('p'));

		if (
				(1 < $pageNumber)
			&&
				df_enabled(Df_Core_Feature::SEO)
			&&
				df_cfg()->seo()->catalog()->category()->needHideDescriptionFromNonFirstPages()
		) {

			$result = null;

		}

		if (!is_null ($result)) {
			df_result_string ($result);
		}

		return $result;

	}





	/**
	 * @param  string $str
	 * @return string
	 */
	public function formatUrlKey($str) {
        return
				(
						df_enabled (Df_Core_Feature::SEO)
					&&
						df_cfg()->seo()->common()->getEnhancedRussianTransliteration()
				)
			?
				df_helper()->catalog()->product()->url()->extendedFormat ($str)
			:
				parent::formatUrlKey ($str)
		;			
	}
}