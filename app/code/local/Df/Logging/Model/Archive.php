<?php


/**
 * Log archive file model
 */
class Df_Logging_Model_Archive extends Varien_Object
{
    /**
     * Full system name to current file, if set
     *
     * @var string
     */
    protected $_file = '';


	
	/**
	 * @return string
	 */
	public function getBasePath () {
	
		if (!isset ($this->_basePath)) {
	
			/** @var string $result  */
			$result = 
				implode (
					DS
					,
					array (
						Mage::getBaseDir ('var'), 'log', 'df', 'admin', 'actions'
					)
				)
			;
	
	
			df_assert_string ($result);
	
			$this->_basePath = $result;
	
		}
	
	
		df_result_string ($this->_basePath);
	
		return $this->_basePath;
	
	}
	
	
	/**
	* @var string
	*/
	private $_basePath;	
	
	
	
	

    /**
     * Check base name syntax
     *
     * @param string $baseName
     * @return bool
     */
    protected function _validateBaseName($baseName)
    {
        return (bool)preg_match('/^[0-9]{10}\.csv$/', $baseName);
    }

    /**
     * Search the file in storage by base name and set it
     *
     * @param string $baseName
     * @return Df_Logging_Model_Archive
     */
    public function loadByBaseName($baseName)
    {
        $this->_file = '';
        $this->unsBaseName();
        if (!$this->_validateBaseName($baseName)) {
            return $this;
        }
        $filename = $this->generateFilename($baseName);
        if (!file_exists($filename)) {
            return $this;
        }
        $this->setBaseName($baseName);
        $this->_file = $filename;
        return $this;
    }


	/**
	 * Generate a full system filename from base name
	 *
	 * @param string $baseName
	 * @return string
	 */
	public function generateFilename ($baseName) {

		/** @var string $result  */
		$result =
			implode (
				DS
				,
				array (
					$this->getBasePath()
					,
					substr($baseName, 0, 4)
					,
					substr($baseName, 4, 2)
					,
					$baseName
				)
			)
		;


		df_result_string ($result);

		return $result;

	}




    /**
     * Full system filename getter
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->_file;
    }

    /**
     * Get file contents, if any
     *
     * @return string
     */
    public function getContents()
    {
        if ($this->_file) {
            return file_get_contents($this->_file);
        }
        return '';
    }

    /**
     * Mime-type getter
     *
     * @return string
     */
    public function getMimeType()
    {
        return 'text/csv';
    }

    /**
     * Attempt to create a new file using specified base name
     * Or generate a base name from current date/time
     *
     * @param string $baseName
     * @return bool
     */
    public function createNew($baseName = '')
    {
        if (!$baseName) {
            $baseName =
					Zend_Date::now()->toString('YMMdH')
				.
					'.csv'
			;
        }
        if (!$this->_validateBaseName($baseName)) {
            return false;
        }

        $file = new Varien_Io_File();
        $filename = $this->generateFilename($baseName);
        $file->setAllowCreateFolders(true)->createDestinationDir(dirname($filename));
        unset($file);
        if (!touch($filename)) {
            return false;
        }
        $this->loadByBaseName($baseName);
        return true;
    }
}
