<?php

class Df_Sales_Block_Admin_Widget_Grid_Column_Renderer_Products
	extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {


    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render (Varien_Object $row) {


		/** @var Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products $renderer  */
		$renderer =

			df_block (
				Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products
					::getNameInMagentoFormat()
				,
				null
				,
				array (
					Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products
						::PARAM__ORIGINAL_RENDERER => $this

					,
					Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products
						::PARAM__ROW => $row
				)
			)
		;

		df_assert ($renderer instanceof Df_Sales_Block_Admin_Widget_Grid_Column_RendererDf_Products);


		/** @var string $result  */
		$result = $renderer->toHtml();

		df_result_string ($result);

        return $result;
    }





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Sales_Block_Admin_Widget_Grid_Column_Renderer_Products';
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

