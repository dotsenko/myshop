<?php


/**
 *	Добавляем к объектам region поддержку метода save().
 *	Этот метод используется установочным скриптом этого модуля.
 *	В то же время, этот метод приводит к сбою в версиях Magento ранее 1.6,
 *	потому что там ресурсная модель объекта region нестандартна
 *	(не унаследована от базовой стандартной ресурсной модели).
 */
class Df_Directory_Model_Resource_Legacy_Region extends Mage_Core_Model_Mysql4_Abstract {

    /**
     * Table with localized region names
     *
     * @var string
     */
    protected $_regionNameTable;

	/**
	 * @override
	 * @return void
	 */
    protected function _construct()
    {
		/**
		 * Нельзя вызывать parent::_construct(),
		 * потому что это метод в родительском классе — абстрактный.
		 */

        $this->_init('directory/country_region', 'region_id');
        $this->_regionNameTable = $this->getTable('directory/country_region_name');
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Mage_Core_Model_Abstract $object
     *
     * @return Varien_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select  = parent::_getLoadSelect($field, $value, $object);
        $adapter = $this->_getReadAdapter();

        $locale       = Mage::app()->getLocale()->getLocaleCode();
        $systemLocale = Mage::app()->getDistroLocaleCode();

        $regionField = $adapter->quoteIdentifier($this->getMainTable() . '.' . $this->getIdFieldName());

        $condition = $adapter->quoteInto('lrn.locale = ?', $locale);
        $select->joinLeft(
            array('lrn' => $this->_regionNameTable),
            "{$regionField} = lrn.region_id AND {$condition}",
            array());

        if ($locale != $systemLocale) {
            $nameExpr  = $this->getCheckSql('lrn.region_id is null', 'srn.name', 'lrn.name');
            $condition = $adapter->quoteInto('srn.locale = ?', $systemLocale);
            $select->joinLeft(
                array('srn' => $this->_regionNameTable),
                "{$regionField} = srn.region_id AND {$condition}",
                array('name' => $nameExpr));
        } else {
            $select->columns(array('name'), 'lrn');
        }

        return $select;
    }

    /**
     * Load object by country id and code or default name
     *
     * @param Mage_Core_Model_Abstract $object
     * @param int $countryId
     * @param string $value
     * @param string $field
     *
     * @return Mage_Directory_Model_Resource_Region
     */
    protected function _loadByCountry($object, $countryId, $value, $field)
    {
        $adapter        = $this->_getReadAdapter();
        $locale         = Mage::app()->getLocale()->getLocaleCode();
        $joinCondition  = $adapter->quoteInto('rname.region_id = region.region_id AND rname.locale = ?', $locale);
        $select         = $adapter->select()
            ->from(array('region' => $this->getMainTable()))
            ->joinLeft(
                array('rname' => $this->_regionNameTable),
                $joinCondition,
                array('name'))
            ->where('region.country_id = ?', $countryId)
            ->where("region.{$field} = ?", $value);

        $data = $adapter->fetchRow($select);
        if ($data) {
            $object->setData($data);
        }

        $this->_afterLoad($object);

        return $this;
    }

    /**
     * Loads region by region code and country id
     *
     * @param Mage_Directory_Model_Region $region
     * @param string $regionCode
     * @param string $countryId
     *
     * @return Mage_Directory_Model_Resource_Region
     */
    public function loadByCode(Mage_Directory_Model_Region $region, $regionCode, $countryId)
    {
        return $this->_loadByCountry($region, $countryId, (string)$regionCode, 'code');
    }

    /**
     * Load data by country id and default region name
     *
     * @param Mage_Directory_Model_Region $region
     * @param string $regionName
     * @param string $countryId
     *
     * @return Mage_Directory_Model_Resource_Region
     */
    public function loadByName(Mage_Directory_Model_Region $region, $regionName, $countryId)
    {
        return $this->_loadByCountry($region, $countryId, (string)$regionName, 'default_name');
    }





    /**
     * Generate fragment of SQL, that check condition and return true or false value
     *
     * @param Zend_Db_Expr|Zend_Db_Select|string $expression
     * @param string $true  true value
     * @param string $false false value
     */
    private function getCheckSql($expression, $true, $false)
    {
        if ($expression instanceof Zend_Db_Expr || $expression instanceof Zend_Db_Select) {
            $expression = sprintf("IF((%s), %s, %s)", $expression, $true, $false);
        } else {
            $expression = sprintf("IF(%s, %s, %s)", $expression, $true, $false);
        }

        return new Zend_Db_Expr($expression);
    }


}


