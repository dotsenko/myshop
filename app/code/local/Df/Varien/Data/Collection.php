<?php



class Df_Varien_Data_Collection
	extends Varien_Data_Collection
	implements Zend_Validate_Interface {





	/**
	 * @return array
	 */
	public function getData () {

		/** @var array $result  */
		$result = $this->_items;

		df_result_array ($result);

		return $result;

	}





	/**
	 * Стандартный метод Varien_Data_Collection::toArray()
	 * преобразует в массив не только коллекцию, но и элементы коллекции.
	 *
	 * Наш метод Df_Varien_Data_Collection::toArrayOfObjects
	 * преобразует в массив коллекцию, но элементы коллекции массиве остаются объектами.
	 *
	 * @return array
	 */
	public function toArrayOfObjects () {

		/** @var array $result  */
		$result = array ();


		foreach ($this as $item) {
			/** @var Varien_Object $item */
			$result []= $item;
		}


		df_result_array ($result);

		return $result;

	}







    /**
     * Returns an array of message codes that explain why a previous isValid() call
     * returned false.
     *
     * If isValid() was never called or if the most recent isValid() call
     * returned true, then this method returns an empty array.
     *
     * This is now the same as calling array_keys() on the return value from getMessages().
     *
     * @return array
     * @deprecated Since 1.5.0
     */
    public function getErrors() {
		return array ();
	}






	/**
	 * Удаляет из коллекции элементы $items (если они есть в коллекции)
	 *
	 * @param  array|Traversable $items
	 * @return Df_Varien_Data_Collection
	 */
	public function subtract ($items) {

		/*************************************
		 * Проверка входных параметров метода
		 */
		df_param_collection ($items, $this->getItemClass(), 0);
		/*************************************/

		foreach ($items as $item) {
			/** @var Varien_Object $item */

			$itemId = $this->_getItemId($item);

			df_assert (!is_null ($itemId));

			$this->removeItemByKey ($itemId);

		}

		return $this;
	}



	/**
	 * @return Zend_Validate
	 */
	public function getValidator () {
		if (!isset ($this->_validator)) {
			$this->_validator = new Zend_Validate ();
		}
		return $this->_validator;
	}

	/**
	 * @var Zend_Validate
	 */
	private $_validator;



    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return boolean
     * @throws Zend_Validate_Exception If validation of $value is impossible
     */
    public function isValid($value) {
		return $this->getValidator()->isValid ($value);
	}



    /**
     * Returns an array of messages that explain why the most recent isValid()
     * call returned false. The array keys are validation failure message identifiers,
     * and the array values are the corresponding human-readable message strings.
     *
     * If isValid() was never called or if the most recent isValid() call
     * returned true, then this method returns an empty array.
     *
     * @return array
     */
    public function getMessages() {
		return $this->getValidator()->getMessages ();
	}



    /**
     * Adds a validator to the end of the chain
     *
     * If $breakChainOnFailure is true, then if the validator fails, the next validator in the chain,
     * if one exists, will not be executed.
     *
     * @param  Zend_Validate_Interface $validator
     * @param  boolean                 $breakChainOnFailure
     * @return Zend_Validate Provides a fluent interface
     */
    public function addValidator(Zend_Validate_Interface $validator, $breakChainOnFailure = false) {
        $this->getValidator()->addValidator ($validator, $breakChainOnFailure);
        return $this;
    }





    /**
     *
     * @param   array|Traversable $items
     * @return  Varien_Data_Collection
     */
    public function addItems ($items) {

		/*************************************
		 * Проверка входных параметров метода
		 */
		df_param_collection ($items, $this->_itemObjectClass, 0);
		/*************************************/

		foreach ($items as $item) {
			/** @var Varien_Object $item */

			$this->addItem ($item);
		}

		return $this;
    }



	/**
	 * @return string
	 */
	protected function getItemClass () {
		return $this->_itemObjectClass;
	}




    /**
     * Adding item to item array
     *
     * @param   Varien_Object $item
     * @return  Varien_Data_Collection
     */
    public function addItem(Varien_Object $item) {

		$itemClass = $this->getItemClass();
		df_assert ($item instanceof $itemClass);

		if ($this->isValid ($item)) {


			try {

				df_assert (!is_null ($item->getId ()));

			}
			catch (Exception $e) {

				df_bt ();

				df_error (
					"Программист пытается добавить в коллекцию объект без идентификатора.
					<br/>У добавляемых в коллекцию объектов должен быть идентификатор."
				);

			}


			/**
			 * Родительский класс возбуждает исключительную ситуацию,
			 * когда находит в коллекции идентификатор добавляемого элемента.
			 *
			 * Нам такое поведение не нужно,
			 * поэтому мы добавляем элемент
			 * только в случае отсутствия идентификатора элемента в коллекции.
			 */

			$itemId = $this->_getItemId ($item);

			if (
					is_null ($itemId)
				||
					is_null ($this->getItemById ($itemId))
			) {
				parent::addItem ($item);
			}
		}

		return $this;
    }






    /**
     * Иногда нам надо формировать коллекции из коллекций.
	 * Varien_Data_Collection не наследуется от Varien_Object,
	 * поэтому нам надо обойти запрет на добавление в коллекцию
	 * непотомков Varien_Object
     *
     * @param   object $item
     * @return  Varien_Data_Collection
     */
    public function addItemNotVarienObject ($item)
    {
        $itemId = $item->getId();

        if (!is_null($itemId)) {
            if (isset($this->_items[$itemId])) {
                throw new Exception('Item ('.get_class($item).') with the same id "'.$item->getId().'" already exist');
            }
            $this->_items[$itemId] = $item;
        } else {
            $this->_items[] = $item;
        }
        return $this;
    }




	/**
	 * @static
	 * @param  array|Traversable $items
	 * @param $class [optional]
	 * @return Df_Varien_Data_Collection
	 */
	public static function createFromCollection ($items, $class = NULL) {

		/*************************************
		 * Проверка входных параметров метода
		 */
		df_param_collection ($items, 'Varien_Object', 0);

		if (!is_null ($class)) {
			df_param_string ($class, 1);
		}
		/*************************************/



		if (is_null ($class)) {
			$class = self::getClass ();
		}


		$result = new $class ();
		/** @var Df_Varien_Data_Collection $result */


		$result->addItems ($items);

		df_assert ($result instanceof Df_Varien_Data_Collection);

		return $result;
	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Varien_Data_Collection';
	}



}