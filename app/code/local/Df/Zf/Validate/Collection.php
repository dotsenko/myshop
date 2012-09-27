<?php


class Df_Zf_Validate_Collection extends Zend_Validate_Abstract {



	/**
	 * @param  mixed $value
	 * @return bool
	 */
	public function isValid ($value) {

		if ($this->canBeNull() && (NULL === $value)) {
			$result = true;
		}

		else if (
			!(
					is_array ($value)
				||
					($value instanceof Traversable)
			)
		) {
			$this->_messageTemplates [self::COLLECTION_IS_NOT_TRAVERSABLE] =
				strtr (
					$this->_messageTemplates [self::COLLECTION_IS_NOT_TRAVERSABLE]
					,
					array (
						'%type%' => gettype ($value)
					)
				)
			;
			$result = false;
            $this->_error (self::COLLECTION_IS_NOT_TRAVERSABLE);
		}
		else {

			$result = true;

			foreach ($value as $item) {
				/** @var mixed $item */

				if (!is_object ($item) || !@is_a ($item, $this->getClassName())) {

					$this->_messageTemplates [self::INVALID_ITEM_CLASS] =
						strtr (
							$this->_messageTemplates [self::INVALID_ITEM_CLASS]
							,
							array (
								'%type%' => gettype ($value)
							)
						)
					;
					$result = false;
					$this->_error (self::INVALID_ITEM_CLASS);
					break;
				}
			}
		}

		return
			$result
		;
	}


	const INVALID_ITEM_CLASS = 'INVALID_ITEM_CLASS';
	const COLLECTION_IS_NOT_TRAVERSABLE = 'COLLECTION_IS_NOT_TRAVERSABLE';


    protected $_messageTemplates = array ();


	/**
	 * @var string
	 */
	private $_className;



	/**
	 * @var array
	 */
	private $_params;


	/**
	 * @param string $className
	 * @param array $params
	 */
	public function __construct ($className, array $params = array ()) {
		$this->_className = $className;
		$this->_params = $params;

		$this->_messageTemplates =
			array (

				self::COLLECTION_IS_NOT_TRAVERSABLE =>
						"Коллекция должна быть либо массивом, либо поддерживать интерфейс Traversable."
					.
						"\nВаша коллекция не удовлетворяет данным условиям (она — типа «%type%»)."
				,

				self::INVALID_ITEM_CLASS =>
					strtr (
						"Элементы коллекции должны быть класса «%class%», но найден элемент типа «%type%»."
						,
						array (
							 '%class%' => $this->_className
						)
					)
			)
		;

	}



	/**
	 * @return bool
	 */
	protected function canBeNull () {
		return $this->getParam ("canBeNull", false);
	}



	/**
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	protected function getParam ($name, $default = NULL) {
		return df_a ($this->_params, $name, $default);
	}


	/**
	 * @return string
	 */
	protected function getClassName () {
		return $this->_className;
	}

}