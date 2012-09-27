<?php


class Df_Page_Block_Html_Header extends Mage_Page_Block_Html_Header {


	/**
	 * @return string
	 */
    public function getWelcome()
    {
        $result = parent::getWelcome();

	    if (
				/**
				 * Избегаем зависимости модуля Df_Page от наличия модуля Df_Tweaks
				 */
				df_module_enabled (Df_Core_Module::TWEAKS)
			&&
				df_installed ()
			&&
				df_enabled (Df_Core_Feature::TWEAKS)
		) {
		    if (df_mage()->customer()->session()->isLoggedIn()) {
				if (df_cfg()->tweaks()->header()->needRemoveWelcomeForLoggedIn()) {
					$result = '';
				}
			    else {
					if (df_cfg()->tweaks()->header()->getShowOnlyFirstName()) {
						$result =
							$this->__(
								'Welcome, %s!'
								,
								$this->escapeHtml(
									df_helper()->tweaks()->customer()->getFirstNameWithPrefix ()
								)
							)
						;
					}
			    }
		    }
	    }

	    return $result;
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


}
