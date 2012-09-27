<?php


class Df_Zf_Validate_Boolean extends Df_Zf_Validate {


	/**
	 * @param  mixed $value
	 * @return bool
	 */
	public function isValid ($value) {
		if ((null === $value) && !$this->isRequired()) {
			$result = true;
		}
		else if (!is_bool ($value)) {
			$result = false;

			$this->_messageTemplates [self::IS_NOT_BOOLEAN] =
				strtr (
					$this->_messageTemplates [self::IS_NOT_BOOLEAN]
					,
					array (
						'%type%' => gettype ($value)
					)
				)
			;
			$this->_error (self::IS_NOT_BOOLEAN);
		}
		else {
			$result = true;
		}
		return
			$result
		;
	}


	const IS_NOT_BOOLEAN = 'IS_NOT_BOOLEAN';


    protected $_messageTemplates = array(
        self::IS_NOT_BOOLEAN => "Требуется значение логического типа («да/нет»), но вместо неё получена переменная типа «%type%».",
    );

}