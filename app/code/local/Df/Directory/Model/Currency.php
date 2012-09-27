<?php

class Df_Directory_Model_Currency extends Mage_Directory_Model_Currency
{

	/**
	 * @param string|int|float $price
	 * @param array $options
	 * @return string
	 */
    public function formatTxt ($price, $options=array())
    {
       // try {
			$result =
					(
							df_enabled(Df_Core_Feature::LOCALIZATION)
						&&
							df_area (
								df_cfg ()->localization ()->translation()->frontend()->needHideDecimals()
								,
								df_cfg ()->localization ()->translation()->admin()->needHideDecimals()
							)
					)
				?
					$this->formatTxtDf ($price, $options)
				:
					parent::formatTxt ($price, $options)
			;
//        }
//        catch (Exception $e) {
//	        $result = 0;
//        }

	    return $result;
    }



	/**
	 * @param string|int|float $price
	 * @param array $options
	 * @return string
	 */
    private function formatTxtDf ($price, $options=array())
    {
        return
			parent::formatTxt (
				$price
				,
				array_merge (
					$options
					,
					array (
					      'precision' => df_helper()->directory()->currency()->getPrecision ()
					)
				)

			)
		;
    }



	/**
	 * @param string|int|float $price
	 * @param array $options
	 * @param bool $includeContainer
	 * @param bool $addBrackets
	 * @return string
	 */
	public function format ($price, $options=array(), $includeContainer = true, $addBrackets = false)
    {
        return
				(
						df_enabled(Df_Core_Feature::LOCALIZATION)
					&&
						df_area (
							df_cfg ()->localization ()->translation()->frontend()->needHideDecimals()
							,
							df_cfg ()->localization ()->translation()->admin()->needHideDecimals()
						)
				)
			?
				$this->formatDf ($price, $options, $includeContainer, $addBrackets)
			:
				parent::format ($price, $options, $includeContainer, $addBrackets)
		;
    }


	/**
	 * @param string|int|float $price
	 * @param array $options
	 * @param bool $includeContainer
	 * @param bool $addBrackets
	 * @return string
	 */
    private function formatDf ($price, $options=array(), $includeContainer = true, $addBrackets = false)
    {
	    return
			$this->formatPrecision(
				$price
				,
				df_helper()->directory()->currency()->getPrecision ()
				,
				$options
				,
				$includeContainer
				,
				$addBrackets
			)
		;
    }
}
