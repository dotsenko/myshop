<?php


class Df_Page_Block_Html_Head extends Mage_Page_Block_Html_Head {


    /**
     * Add HEAD Item
     *
     * Allowed types:
     *  - js
     *  - js_css
     *  - skin_js
     *  - skin_css
     *  - rss
     *
     * @param string $type
     * @param string $name
     * @param string $params
     * @param string $if
     * @param string $cond
     * @return Df_Page_Block_Html_Head
     */
    public function addItem($type, $name, $params=null, $if=null, $cond=null) {

		if (self::PREPEND !== $params) {
			parent::addItem ($type, $name, $params, $if, $cond);
		}
		else {

			$params = null;

			df_array_unshift_assoc (
				$this->_data['items']
				,
				$type.'/'.$name
				,
				array (
					'type'   => $type,
					'name'   => $name,
					'params' => $params,
					'if'     => $if,
					'cond'   => $cond,
			   )
			)
			;

		}
        return $this;
    }





    /**
     * @return string
     */
    public function __ () {

		/** @var array $args  */
        $args = func_get_args();

		df_assert_array ($args);


		/** @var string $result  */
        $result =
			df_helper()->localization()->translation()->translateByParent ($args, $this)
		;

		df_result_string ($result);


	    return $result;
    }






	/**
	 * @override
	 *
     * Merge static and skin files of the same format into 1 set of HEAD directives or even into 1 directive
     *
     * Will attempt to merge into 1 directive, if merging callback is provided. In this case it will generate
     * filenames, rather than render urls.
     * The merger callback is responsible for checking whether files exist, merging them and giving result URL
     *
     * @param string $format - HTML element format for sprintf('<element src="%s"%s />', $src, $params)
     * @param array $staticItems - array of relative names of static items to be grabbed from js/ folder
     * @param array $skinItems - array of relative names of skin items to be found in skins according to design config
     * @param callback $mergeCallback
     * @return string
	 */
	protected function &_prepareStaticAndSkinElements ($format, array $staticItems, array $skinItems, $mergeCallback = null) {


		/** @var Df_Page_Model_Html_Head $adjuster */
		$adjuster = df_model (Df_Page_Model_Html_Head::getNameInMagentoFormat());

		df_assert ($adjuster instanceof Df_Page_Model_Html_Head);


		if (is_null ($mergeCallback)) {

			$staticItems = $adjuster->addVersionStamp ($staticItems);

			/**
			 * Обратите внимание, что для ресурсов темы мы добавляем параметр v по-другому:
			 * в методе Df_Core_Model_Design_Package::getSkinUrl
			 *
			 * Здесь нам добавлять v было нельзя: ведь getSkinUrl работает с именами файлов
			 * и просто не найдёт файл с именем file.css?v=1.33.3
			 */
		}


		/** @var string $additionalTags  */
		$additionalTags = $adjuster->prependAdditionalTags ($format, $staticItems);


		/** @var string $result  */
		$result =
			implode (
				"\r\n"
				,
				df_clean (
					array (
						$additionalTags
						,
						parent::_prepareStaticAndSkinElements ($format, $staticItems, $skinItems, $mergeCallback)
					)
				)
			)
		;

		df_result_string ($result);

		return $result;

	}





	const PREPEND = 'prepend';

}


