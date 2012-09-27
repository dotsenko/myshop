<?php


class Df_Directory_Helper_Data extends Mage_Directory_Helper_Data {



	/**
	 * @return string
	 */
	public function __ () {

		/** @var array $args  */
		$args = func_get_args();

		df_assert_array ($args);


		/** @var string $result  */
		$result =
			df_helper()->localization()->translation()->translateByParent ($args, $this)
		;

		df_result_string ($result);


		return $result;
	}
	
	
	
	/**
	 * @return Df_Directory_Helper_Assert
	 */
	public function assert () {

		/** @var Df_Directory_Helper_Assert $result  */
		$result = Mage::helper (Df_Directory_Helper_Assert::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Directory_Helper_Assert);

		return $result;

	}
	
	
	
	
	/**
	 * @return Df_Directory_Helper_Check
	 */
	public function check () {

		/** @var Df_Directory_Helper_Check $result  */
		$result = Mage::helper (Df_Directory_Helper_Check::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Directory_Helper_Check);

		return $result;

	}




	/**
	 * @return Df_Directory_Helper_Country
	 */
	public function country () {

		/** @var Df_Directory_Helper_Country $result  */
		$result = Mage::helper (Df_Directory_Helper_Country::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Directory_Helper_Country);

		return $result;

	}

	
	
	


	/**
	 * @return Df_Directory_Helper_Currency
	 */
	public function currency () {

		/** @var Df_Directory_Helper_Currency $result  */
		$result = Mage::helper (Df_Directory_Helper_Currency::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Directory_Helper_Currency);

		return $result;

	}





	/**
	 * @return Df_Directory_Helper_Finder
	 */
	public function finder () {

		/** @var Df_Directory_Helper_Finder $result  */
		$result = Mage::helper (Df_Directory_Helper_Finder::getNameInMagentoFormat());

		df_assert ($result instanceof Df_Directory_Helper_Finder);

		return $result;

	}

	
	
	
	/**
	 * @return Zend_Locale
	 */
	public function getLocaleEnglish () {
	
		if (!isset ($this->_localeEnglish)) {
	
			/** @var Zend_Locale $result  */
			$result = 
				new Zend_Locale (
					Mage_Core_Model_Locale::DEFAULT_LOCALE
				)
			;
	
	
			df_assert ($result instanceof Zend_Locale);
	
			$this->_localeEnglish = $result;
	
		}
	
	
		df_assert ($this->_localeEnglish instanceof Zend_Locale);
	
		return $this->_localeEnglish;
	
	}
	
	
	/**
	* @var Zend_Locale
	*/
	private $_localeEnglish;	





    /**
     * Retrieve regions data json
     *
     * @return string
     */
    public function getRegionJson()
    {

        Varien_Profiler::start('TEST: '.__METHOD__);
        if (!$this->_regionJson) {
            $cacheKey = 'DIRECTORY_REGIONS_JSON_STORE'.Mage::app()->getStore()->getId();
            if (Mage::app()->useCache('config')) {
                $json = Mage::app()->loadCache($cacheKey);
            }
            if (empty($json)) {
                $countryIds = array();
                foreach ($this->getCountryCollection() as $country) {
                    $countryIds[] = $country->getCountryId();
                }
                $collection = Mage::getModel('directory/region')->getResourceCollection()
                    ->addCountryFilter($countryIds)
                    ->load();
                $regions = array();

                foreach ($collection as $region) {
                    if (!$region->getRegionId()) {
                        continue;
                    }


					/**
					 * BEGIN PATCH
					 */
                    $regions[$region->getCountryId()][] = array(
                        'code' => $region->getCode(),
                        'name' => $this->__($region->getName())
						,
						'id' => $region->getRegionId()
                    );
					/**
					 * END PATCH
					 */

                }

                $json = df_mage()->coreHelper()->jsonEncode($regions);

                if (Mage::app()->useCache('config')) {
                    Mage::app()->saveCache($json, $cacheKey, array('config'));
                }
            }
            $this->_regionJson = $json;
        }

        Varien_Profiler::stop('TEST: '.__METHOD__);
        return $this->_regionJson;
    }




	/**
	 * @param int $regionId
	 * @return string
	 */
	public function getRegionFullNameById ($regionId) {

		df_param_integer ($regionId, 0);


		/** @var Mage_Directory_Model_Region $region  */
		$region = $this->getRussianRegions()->getItemById ($regionId);

		df_assert ($region instanceof Mage_Directory_Model_Region);


		/** @var string $result  */
		$result = $region->getName();


		df_result_string ($result);

		return $result;

	}




	/**
	 * @return Mage_Directory_Model_Resource_Region_Collection|Mage_Directory_Model_Mysql4_Region_Collection
	 */
	public function getRegions () {
	
		if (!isset ($this->_regions)) {
	
			/** @var Mage_Directory_Model_Resource_Region_Collection|Mage_Directory_Model_Mysql4_Region_Collection $result  */
			$result = Mage::getResourceModel ('directory/region_collection');

			df_helper()->directory()->assert()->regionCollection($result);

			$this->normalizeRegions ($result);
	
			$this->_regions = $result;
	
		}

		df_helper()->directory()->assert()->regionCollection($this->_regions);
	
		return $this->_regions;
	
	}
	
	
	/**
	* @var Mage_Directory_Model_Resource_Region_Collection|Mage_Directory_Model_Mysql4_Region_Collection
	*/
	private $_regions;





	/**
	 * @param int $regionId
	 * @return string|null
	 */
	public function getRegionNameById ($regionId) {

		if (
				!isset ($this->_regionNameById [$regionId])
			&&
				!df_a ($this->_regionNameByIdIsNull, $regionId, false)
		) {

			/** @var Mage_Directory_Model_Region|null $region */
			$region = $this->getRegions()->getItemById ($regionId);


			/** @var string|null $result  */
			$result =
					is_null ($region)
				?
					null
				:
					df_a ($region->getData (), 'original_name', $region->getName())
			;


			if (!is_null ($result)) {
				df_assert_string ($result);
			}
			else {
				$this->_regionNameByIdIsNull [$regionId] = true;
			}

			$this->_regionNameById [$regionId] = $result;

		}


		if (!is_null ($this->_regionNameById [$regionId])) {
			df_result_string ($this->_regionNameById [$regionId]);
		}


		return $this->_regionNameById [$regionId];

	}


	/**
	* @var string[]
	*/
	private $_regionNameById = array ();

	/**
	 * @var bool[]
	 */
	private $_regionNameByIdIsNull = array ();



	
	/**
	 * @return Mage_Directory_Model_Resource_Region_Collection|Mage_Directory_Model_Mysql4_Region_Collection
	 */
	public function getRussianRegions () {
	
		if (!isset ($this->_russianRegions)) {
	
			/** @var Mage_Directory_Model_Resource_Region_Collection|Mage_Directory_Model_Mysql4_Region_Collection $result  */
			$result =
				df_helper()->directory()->country()->getRussia()->getRegions()
			;

			df_helper()->directory()->assert()->regionCollection($result);

			$this->normalizeRegions ($result);
	
			$this->_russianRegions = $result;
		}

		df_helper()->directory()->assert()->regionCollection($this->_russianRegions);
	
		return $this->_russianRegions;
	}
	
	
	/**
	* @var Mage_Directory_Model_Resource_Region_Collection|Mage_Directory_Model_Mysql4_Region_Collection
	*/
	private $_russianRegions;
	




    /**
	 * @param array $args
     * @return string
     */
    public function translateByParent (array $args) {

		/** @var string $result  */
        $result =
			df_helper()->localization()->translation()->translateByModule (
				$args, self::DF_PARENT_MODULE
			)
		;

		df_result_string ($result);

	    return $result;
    }







	/**
	 * @param string $locationName
	 * @return string
	 */
	public function normalizeLocationName ($locationName) {

		df_param_string ($locationName, 0);


		/** @var string $result  */
		$result =
			df_trim (
				strtr (
					mb_strtoupper (
						$locationName
					)
					,
					array (
						'Ё' => 'Е'
					)
				)
			)
		;


		df_result_string ($result);

		return $result;

	}




	/**
	 * @param Varien_Data_Collection_Db $regions
	 * @return Mage_Directory_Model_Resource_Region_Collection|Mage_Directory_Model_Mysql4_Region_Collection
	 */
	public function normalizeRegions (Varien_Data_Collection_Db $regions) {

		df_helper()->directory()->assert()->regionCollection ($regions);

		if (
				!df_cfg()->directory()->regions()->ru()->getEnabled()
			||
				!df_enabled (Df_Core_Feature::DIRECTORY)
		) {

			Df_Directory_Model_Handler_ProcessRegionsAfterLoading
				::addTypeToNameStatic (
					$regions
				)
			;
		}

		return $this;
	}





	const DF_PARENT_MODULE = 'Mage_Directory';




	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_Directory_Helper_Data';
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