<?php

class Df_Tag_Block_Popular extends Mage_Tag_Block_Popular {


	/**
	 * @return string|null
	 */
	public function getTemplate () {

		/** @var string $result  */
		$result = parent::getTemplate();

		if (
				df_module_enabled (Df_Core_Module::TWEAKS)
			&&
				df_enabled (Df_Core_Feature::TWEAKS)
			&&
				(
						df_cfg()->tweaks()->tags()->popular()->getRemoveFromAll()
					||
						(
								df_cfg()->tweaks()->tags()->popular()->getRemoveFromFrontpage ()
							&&
								df_handle_presents(Df_Cms_Const::LAYOUT_HANDLE__INDEX_INDEX)
						)
					||
						(
								df_cfg()->tweaks()->tags()->popular()->getRemoveFromCatalogProductList ()
							&&
								df_handle_presents(Df_Catalog_Const::LAYOUT_HANDLE__CATEGORY_VIEW)
						)
					||
						(
								df_cfg()->tweaks()->tags()->popular()->getRemoveFromCatalogProductView ()
							&&
								df_handle_presents(Df_Catalog_Const::LAYOUT_HANDLE__PRODUCT_VIEW)
						)
				)
		) {

			/**
			 * Обратите внимание,
			 * что в демо-данных для главной страницы блок tag/popular
			 * создаётся синтаксисом {{block type="tag/popular" template="tag/popular.phtml"}}
			 * уже после события controller_action_layout_generate_blocks_after.
			 *
			 * Поэтому приходится скрывать блок перекрытием метода getTemplate
			 *
			 */
			$result = null;

		}


		return $result;

	}


}

