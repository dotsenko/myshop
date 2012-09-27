<?php

class Df_Zf_Validate_Int extends Df_Zf_Validate
{

    /**
     * @param  string|integer $value
     * @return boolean
     */
    public function isValid ($value) {
		if ((null === $value) && !$this->isRequired()) {
			$result = true;
		}
		else {
			$result = (is_numeric($value) ? (intval($value) == $value) : false);
			if (!$result) {
				$this->_messageTemplates [self::NOT_INT] =
					strtr (
						$this->_messageTemplates [self::NOT_INT]
						,
						array (
							'%type%' => gettype ($value)
						)
					)
				;
				$this->_error(self::NOT_INT);
			}
		}
        return $result;
    }



	/**
	 * @var array
	 */
	protected $_messageTemplates = array(
		self::NOT_INT => "Требуется целое число, но вместо него получена переменная типа «%type%».",
	);


	const NOT_INT = 'NOT_INT';

}
