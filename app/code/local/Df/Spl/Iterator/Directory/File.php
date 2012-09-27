<?php


class Df_Spl_Iterator_Directory_File extends Df_Spl_Iterator_Directory_Filtered_Abstract {


	public function callback_pattern ($directoryElement) {
		$matches = array ();
		return 0 != preg_match($this->getPattern (), $directoryElement->getFilename (), $matches);
	}


	public static function callback_IsFile ($directoryElement) {
		return $directoryElement->isFile ();
	}


	protected function createValidator () {
		if (df_empty ($this->getPattern ())) {
			$result = $this->createIsFileValidator ();
		}
	    else {
			$result = new Zend_Validate ();
	        $result
				->addValidator ($this->createIsFileValidator ())
	            ->addValidator ($this->createPatternValidator ())
	        ;
	    }
	    return $result;
	}


	private function getPattern () {
		return $this->getParam ("pattern");
	}


	private function createIsFileValidator () {
		return new Df_Zf_Validate_Callback (array (get_class (), "callback_IsFile"));
	}

	private function createPatternValidator () {
		return new Df_Zf_Validate_Callback (array ($this, "callback_pattern"));
	}




	const PARAM_PATTERN = 'pattern';
}
