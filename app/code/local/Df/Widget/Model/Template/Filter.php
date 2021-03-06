<?php


/**
 * Наследуемся от Mage_Cms_Model_Template_Filter, потому что нам надо перекрыть сразу 3 класса:
 *
 * Mage_Core_Model_Email_Template_Filter
 * Mage_Cms_Model_Template_Filter
 * Mage_Widget_Model_Template_Filter
 */
class Df_Widget_Model_Template_Filter extends Mage_Widget_Model_Template_Filter {

	/**
	 * @override
	 * Retrieve Skin URL directive
	 *
	 * @param array $construction
	 * @return string
	 */
	public function skinDirective($construction) {

		$params = $this->_getIncludeParameters($construction[2]);

		$params['_absolute'] = $this->_useAbsoluteLinks;


		/************
		 * BEGIN PATCH
		 */

		if (df_cfg()->admin()->wysiwyg()->fixImages()) {
			$params =
				array_merge (
					array (
					'_area' => 'frontend'
					)
					,
					$params
				)
			;
		}

		/************
		 * END PATCH
		 */


		/** @var string $result  */
		/**
		 * При переносе магазина с промышленного сервера на локальный с адресом типа
		 * http://localhost.com:695/  картинки, вставленные в статичный блок посредством разметки типа
		 * 	<img
				src="http://localhost.com:695/index.php/admin/cms_wysiwyg/directive/___directive/e3tza2luX3VybH19//images/cards-img-1.gif"
				alt=""
			/>
		 * могут давать сбой при обработки стандартным методом skinDirective:
		 *
		 * ключ «url» может отсутствовать в массиве $params.
		 *
		 * @var string $result
		 */
		$result =
				!isset($params['url'])
			?
				Df_Core_Const::T_EMPTY
			:
				Mage::getDesign()->getSkinUrl($params['url'], $params)
		;

		return $result;
	}

}


