<?php


class Df_Localization_Helper_Translation extends Mage_Core_Helper_Abstract {



    /**
	 * @param array $args
	 * @param $object
     * @return string
     */
    public function translateByParent (array $args, $object) {

		df_assert (is_object ($object));

		/** @var string $parentClass  */
		$parentClass =
			get_parent_class (
				$object
			)
		;

		df_assert_string ($parentClass);



		/** @var string $currentClass  */
		$currentClass =
			get_class (
				$object
			)
		;

		df_assert_string ($currentClass);


		df_assert (
			$parentClass !== $currentClass
			,
			sprintf (
				'[%s] Класс объекта совпадает с его родительским классом: «%s».
				Видимо, программист ошибся.'
				,
				__METHOD__
				,
				$currentClass
			)
		)
		;



		/** @var string $result  */
        $result =
			$this->translateByModule (
				$args
				,
				df()->reflection()->getModuleName (
					get_parent_class (
						$object
					)
				)
			)
		;

		df_result_string ($result);


	    return $result;
    }




    /**
	 * @param array $args
	 * @param string $module
     * @return string
     */
    public function translateByModule (array $args, $module) {

		df_param_string ($module, 1);

		/** @var Mage_Core_Model_Translate_Expr $expr */
        $expr = new Mage_Core_Model_Translate_Expr(array_shift($args), $module);

		df_assert ($expr instanceof Mage_Core_Model_Translate_Expr);


		array_unshift($args, $expr);


		/** @var string $result  */
        $result = Mage::app()->getTranslator()->translate($args);

		df_result_string ($result);


	    return $result;
    }




    /**
	 * @param array $args
	 * @param array $modules
     * @return string
     */
    public function translateByModules (array $args, array $modules) {

		/** @var string $prevModule  */
		$prevModule = null;


		reset ($args);


		/** @var string $originalText */
		$originalText = current($args);

		if (is_null ($originalText)) {


			/**
			 * Вот почему-то Mage_Adminhtml_Block_System_Email_Template_Grid_Filter_Type::_getOptions
			 * в Magento CE 1.6.2.0 передаёт первым параметром NULL:
			 *
				protected function _getOptions()
				{
					$result = array();
					foreach (self::$_types as $code=>$label) {
						$result[] = array('value'=>$code, 'label'=>Mage::helper('adminhtml')->__($label));
					}

					return $result;
				}
			 *
			 * В этом случае мы получаем в текущий метод array (NULL)
			 */


			/** @var string $result */
			$result = Df_Core_Const::T_EMPTY;

		}

		else {
			/** @var string $result */
			$result = $originalText;


			df_assert_string ($originalText);


			/** @var string $result */
			$result = $originalText;



			/**
			 * @see http://magento-forum.ru/topic/2066/
			 */

			//if (Mage_Core_Model_Locale::DEFAULT_LOCALE !== Mage::app()->getLocale()->getLocaleCode()) {

				foreach ($modules as $module) {

					/** @var string $module */
					df_assert_string ($module);

					if ($result !== $originalText) {
						break;
					}

					if ($prevModule === $module) {
						break;
					}

					$result = $this->translateByModule ($args, $module);

				}
			//}


		}


		df_result_string ($result);


	    return $result;
    }




	/**
	 * @return Df_Localization_Model_Translation_FileStorage
	 */
	public function getRussianFileStorage () {

		/** @var Df_Localization_Model_Translation_FileStorage $result  */
		$result =
			$this->getFileStorageByCode (
				'ru_DF'
			)
		;

		df_assert ($result instanceof Df_Localization_Model_Translation_FileStorage);

		return $result;

	}





	/**
	 * @return Df_Localization_Model_Translation_FileStorage
	 */
	public function getDefaultFileStorage () {

		/** @var Df_Localization_Model_Translation_FileStorage $result  */
		$result =
			$this->getFileStorageByCode (
				Mage_Core_Model_Locale::DEFAULT_LOCALE
			)
		;

		df_assert ($result instanceof Df_Localization_Model_Translation_FileStorage);

		return $result;

	}


	
	/**
	 * @param string $code
	 * @return Df_Localization_Model_Translation_FileStorage
	 */
	public function getFileStorageByCode ($code) {

		df_param_string ($code, 0);

		if (!isset ($this->_fileStorageByCode [$code])) {

			/** @var Df_Localization_Model_Translation_FileStorage $result  */
			$result =
				df_model (
					Df_Localization_Model_Translation_FileStorage::getNameInMagentoFormat()
					,
					array (
						Df_Localization_Model_Translation_FileStorage::PARAM__CODE => $code
					)
				)
			;


			df_assert ($result instanceof Df_Localization_Model_Translation_FileStorage);

			$this->_fileStorageByCode [$code] = $result;

		}


		df_assert ($this->_fileStorageByCode [$code] instanceof Df_Localization_Model_Translation_FileStorage);

		return $this->_fileStorageByCode [$code];

	}


	/**
	* @var array
	*/
	private $_fileStorageByCode;




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Localization_Helper_Translation';
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