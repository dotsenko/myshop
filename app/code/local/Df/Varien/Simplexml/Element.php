<?php

class Df_Varien_Simplexml_Element extends Varien_Simplexml_Element {



	/**
	 * @param array $attributes
	 * @return Df_Varien_Simplexml_Element
	 */
	public function addAttributes (array $attributes) {

		foreach ($attributes as $name => $value) {

			/** @var string $name */
			/** @var string $value */

			df_assert_string ($name);
			df_assert_string ($value);


			$this->addAttribute ($name, $value);

		}

		return $this;

	}




	/**
	 * @param string $tagName
	 * @param string $valueAsText
	 * @return Df_Varien_Simplexml_Element
	 */
	public function addChildText ($tagName, $valueAsText) {

		/** @var Df_Varien_Simplexml_Element $result  */
		$result = $this->addChild ($tagName);

		/**
		 * Обратите внимание, что
		 * SimpleXMLElement::addChild создаёт и возвращает не просто SimpleXMLElement,
		 * как говорит документация, а объект класса родителя.
		 * Поэтому в нашем случае addChild создаст объект Df_Varien_Simplexml_Element.
		 */
		df_assert ($result instanceof Df_Varien_Simplexml_Element);

		$result->setCData ($valueAsText);

		return $result;

	}





	/**
	 * @param array $array
	 * @param array $wrapInCData [optional]
	 * @return Df_Varien_Simplexml_Element
	 */
	public function importArray (array $array, array $wrapInCData = array ()) {

		foreach ($array as $key => $value) {

			/** @var string $key */
			/** @var mixed $value */

			if (!is_array ($value)) {
				$this->importString ($key, $value, $wrapInCData);
			}
			else {
				if (df_is_assoc($value)) {

					/** @var Df_Varien_Simplexml_Element $childNode  */
					$childNode = $this->addChild (df_string ($key));


					/** @var array|null $childData */
					$childData = $value;

					/**
					 * Данный программный код позволяет импортировать атрибуты тэгов
					 *
					 * @var array|null $attributes
					 */
					$attributes = df_a ($value, self::KEY__ATTRIBUTES);

					if (!is_null ($attributes)) {
						df_assert_array ($attributes);
						$childNode->addAttributes ($attributes);

						/**
						 * Если $value содержит атрибуты,
						 * то дочерние значения должны содержаться
						 * не непосредственно в $value, а в подмассиве с ключём self::KEY__VALUE
						 */
						$childData = df_a ($value, self::KEY__VALUE);
					}

					if (!is_null ($childData)) {

						/**
						 * $childData запросто может не быть массивом.
						 * Например, в такой ситуации:
						 *
							(
								[_attributes] => Array
									(
										[Код] => 796
										[НаименованиеПолное] => Штука
										[МеждународноеСокращение] => PCE
									)

								[_value] => шт
							)
						 *
						 * Здесь $childData — это «шт».
						 */
						if (!is_array ($childData)) {

							$childNode
								->importString (
									/**
									 * null означает, что метод importString
									 * не должен создавать дочерний тэг $key,
									 * а должен добавить текст
									 * в качестве единственного содержимого текущего тэга
									 */
									$key = null
									,
									$childData
									,
									$wrapInCData
								)
							;
						}
						else {
							$childNode->importArray ($value, $wrapInCData);
						}
					}
				}
				else {
					/**
					 * Данный код позволяет импортировать структуры с повторяющимися тегами.
					 *
					 * Например, нам надо сформировать такой документ:
					 *
						<АдресРегистрации>
							<АдресноеПоле>
								<Тип>Почтовый индекс</Тип>
								<Значение>127238</Значение>
							</АдресноеПоле>
							<АдресноеПоле>
								<Тип>Улица</Тип>
								<Значение>Красная Площадь</Значение>
							</АдресноеПоле>
						</АдресРегистрации>
					 *
					 * Для этого мы вызываем:
					 *
						$this->getDocument()
							->importArray(
								array (
					 				'АдресРегистрации' =>
										array (
											'АдресноеПоле' =>
												array (
													array (
														'Тип' => 'Почтовый индекс'
														,
														'Значение' => '127238'
													)
													,
													array (
														'Тип' => 'Улица'
														,
														'Значение' => 'Красная Площадь'
													)
												)
										)
								)
							)
						;
					 *
					 */

					foreach ($value as $valueItem) {

						/** @var array $valueItem  */
						df_assert_array ($valueItem);

						/** @var Df_Varien_Simplexml_Element $childNode  */
						$childNode = $this->addChild (df_string ($key));
						$childNode->importArray ($valueItem, $wrapInCData);
					}
				}
			}
		}

		return $this;
	}




	/**
	 * @param mixed $value
	 * @return Df_Varien_Simplexml_Element
	 */
	public function setValue ($value) {
		/**
		 * @link http://stackoverflow.com/a/3153704/254475
		 */
		$this->{0} = $value;

		return $this;
	}




	/**
	 * @param string|null $key
	 * @param mixed $value
	 * @param array $wrapInCData [optional]
	 * @return Df_Varien_Simplexml_Element
	 */
	private function importString ($key, $value, array $wrapInCData = array ()) {

		/**
		 * null означает, что метод importString
		 * не должен создавать дочерний тэг $key,
		 * а должен добавить текст
		 * в качестве единственного содержимого текущего тэга
		 */
		if (!is_null ($key)) {
			df_param_string ($key, 0);
		}

		/** @var string $keyAsString */
		$keyAsString =
				is_null ($key)
			?
				$this->getName()
			:
				df_string ($key)
		;


		/**
		 * @var bool $valueIsString
		 */
		$valueIsString = is_string ($value);

		try {
			/** @var string $valueAsString  */
			$valueAsString = df_string ($value);
		}
		catch (Exception $e) {
			df_error (
				sprintf (
					"Не могу сконвертировать значение ключа «%s» в строку.\r\n%s"
					,
					$keyAsString
					,
					$e->getMessage()
				)

			);
		}


		/** @var bool $needWrapInCData  */
		$needWrapInCData = false;

		if (
				$valueIsString
			&&
				!df_empty ($valueAsString)
		) {
			/**
			 * Поддержка синтаксиса
			 *
				 array (
					'Представление' =>
						Df_Varien_Simplexml_Element::markAsCData (
							$this->getAddress()->format(
								Mage_Customer_Model_Attribute_Data::OUTPUT_FORMAT_TEXT
							)
						)
				 )
			 *
			 * Обратите внимание, что проверка на синтаксис [[]] должна предшествовать
			 * проверке на принадлежкость ключа $keyAsString в массиве $wrapInCData,
			 * потому что при соответствии синтаксису [[]] нам надо удалить из значения символы [[]].
			 */
			/** @var string $pattern */
			$pattern = "#\[\[([\s\S]*)\]\]#mu";

			/** @var array $matches  */
			$matches = array ();

			if (1 === preg_match($pattern, $valueAsString, $matches)) {
				$valueAsString = $matches[1];
				$needWrapInCData = true;
			}

			if (!$needWrapInCData) {
				if (in_array ($keyAsString, $wrapInCData)) {
					$needWrapInCData = true;
				}
			}
		}


		/** @var Df_Varien_Simplexml_Element $result  */
		$result =
				$needWrapInCData
			?
				(
						is_null ($key)
					?
						$this->setCData ($valueAsString)
					:
						$this->addChildText ($keyAsString, $valueAsString)
				)
			:
				(
						is_null ($key)
					?
						$this->setValue ($valueAsString)
					:
						$this->addChild ($keyAsString, $valueAsString)
				)
		;

		df_assert ($result instanceof Df_Varien_Simplexml_Element);

		return $result;
	}





	/**
	 * @param string $text
	 * @return Df_Varien_Simplexml_Element
	 */
	public function setCData ($text) {

		/** @var DOMElement $domElement */
		$domElement = dom_import_simplexml ($this);

		/** @var DOMDocument $result  */
		$domDocument = $domElement->ownerDocument;

		$domElement
			->appendChild (
				$domDocument->createCDATASection (
					$text
				)
			)
		;

		return $this;

	}






	/**
	 * @static
	 * @param string $tag
	 * @param array $attributes [optional]
	 * @return Df_Varien_Simplexml_Element
	 */
	public static function createNode ($tag, array $attributes = array ()) {

		/** @var Df_Varien_Simplexml_Element $result  */
		$result =
			new Df_Varien_Simplexml_Element (
				sprintf (
					'<%s/>', $tag
				)
			)
		;

		foreach ($attributes as $name => $value) {
			$result->addAttribute ($name, $value);
		}

		return $result;

	}





	/**
	 * @static
	 * @param string $text
	 * @return string
	 */
	public static function markAsCData ($text) {

		df_param_string ($text, 0);

		/** @var string $result */
		$result =
			sprintf (
				'[[%s]]'
				,
				$text
			)
		;

		df_result_string ($result);

		return $result;
	}



	const KEY__ATTRIBUTES = '_attributes';
	const KEY__VALUE = '_value';

}


