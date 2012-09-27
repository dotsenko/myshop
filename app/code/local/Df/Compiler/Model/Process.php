<?php


class Df_Compiler_Model_Process extends Mage_Compiler_Model_Process {


	/**
	 * @param  $classes
	 * @param  $scope
	 * @return string
	 */
    protected function _getClassesSourceCode($classes, $scope)
    {
		if (@class_exists('Mage_Shell_Compiler', false)) {
			Mage::helper ('df/lib');
		}

		return
				!(
					// Видимо, улучшенную компиляцию нельзя отрубать даже по истичению лицензии,
					// иначе при неправильной компиляции сайт может перестать работать
					//
					//	df_enabled ("df-tweaks-admin")
					//&&
						df_cfg()->admin()->system()->tools()->compilation()->getFix()
				)
			?
				parent::_getClassesSourceCode ($classes, $scope)
			:
				$this->_getClassesSourceCodeDf ($classes, $scope)

		;
    }




	/**
	 * @param  $classes
	 * @param  $scope
	 * @return string
	 */
    protected function _getClassesSourceCodeDf ($classes, $scope)
    {
		$sortedClasses = array();
        foreach ($classes as $className) {

			if (!@class_exists ($className)) {
				continue;
			}

            $implements = array_reverse(class_implements($className));
            foreach ($implements as $class) {
                if (!in_array($class, $sortedClasses) && !in_array($class, $this->_processedClasses) && strstr($class, '_')) {
                    $sortedClasses[] = $class;
                    if ($scope == 'default') {
                        $this->_processedClasses[] = $class;
                    }
                }
            }
            $extends    = array_reverse(class_parents($className));
            foreach ($extends as $class) {
                if (!in_array($class, $sortedClasses) && !in_array($class, $this->_processedClasses) && strstr($class, '_')) {
                    $sortedClasses[] = $class;
                    if ($scope == 'default') {
                        $this->_processedClasses[] = $class;
                    }
                }
            }
            if (!in_array($className, $sortedClasses) && !in_array($className, $this->_processedClasses)) {
                $sortedClasses[] = $className;
                    if ($scope == 'default') {
                        $this->_processedClasses[] = $className;
                    }
            }
        }

        $classesSource = "<?php\n";
        foreach ($sortedClasses as $className) {
            $file = $this->_includeDir.DS.$className.'.php';
            if (!file_exists($file)) {
                continue;
            }
            $content = file_get_contents($file);


			/*************************************
			 * Начало заплатки
			 *************************************/

			/** @var string $contentBeforeRemovingBom  */
			$contentBeforeRemovingBom = $content;

			df_assert_string ($contentBeforeRemovingBom);


			/** @var string $content  */
			$content = df_text()->bomRemove ($content);

			df_assert_string ($content);


			if ($content !== $contentBeforeRemovingBom) {
				Mage
					::log (
						sprintf (
							'Российская сборка Magento предотвратила сбой компиляции,
							удалив недопустимый символ BOM из файла %s.'
							,
							$file
						)
					)
				;
			}


            $content = ltrim($content, '<?php');

            $content = rtrim($content, "\n\r\t?>");


	        
            $classesSource.=
		        sprintf (
			        "\n\nif (!class_exists ('%s', false) && !(interface_exists ('%s', false))) {\n%s\n}"
		            ,
			        $className
			        ,
			        $className
		            ,
			        $content
		        )
            ;


			/*************************************
			 * Конец заплатки
			 *************************************/

        }
        return $classesSource;
    }

}