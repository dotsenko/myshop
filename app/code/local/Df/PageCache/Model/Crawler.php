<?php

class Df_PageCache_Model_Crawler extends Mage_Core_Model_Abstract
{


    /**
     * Crawl all system urls
     * @return Df_PageCache_Model_Crawler
     */
    public function crawl() {

		try {

			/** @var array $storesInfo  */
			$storesInfo = $this->getStoresInfo();

			$dfUrlCount = 0;
			$dfMaxUrlCount = 20;


			foreach ($storesInfo as $info) {
				$options    = array(CURLOPT_USERAGENT => self::USER_AGENT);
				$storeId    = $info['store_id'];

				if (!Mage::app()->getStore($storeId)->getConfig(self::XML_PATH_CRAWLER_ENABLED)) {
					continue;
				}
				$threads = (int)Mage::app()->getStore($storeId)->getConfig(self::XML_PATH_CRAWLER_THREADS);
				if (!$threads) {
					$threads = 1;
				}
				$stmt       = $this->_getResource()->getUrlStmt($storeId);
				$baseUrl    = $info['base_url'];
				if (!empty($info['cookie'])) {
					$options[CURLOPT_COOKIE] = $info['cookie'];
				}
				$urls = array();
				$urlsCount = 0;
				$totalCount = 0;

				$this->request (array ($baseUrl), $options);

				while ($row = $stmt->fetch()) {

					//Mage::log ($row['request_path']);

					$urls[] =
						implode (
							Df_Core_Const::T_EMPTY
							,
							array (
								$baseUrl
								,
								$this->encodeUrlPath ($row['request_path'])
							)
						)
					;
					$urlsCount++;
					$totalCount++;
					if ($urlsCount==$threads) {

						$this->request ($urls, $options);

						$dfUrlCount += count ($urls);
						if ($dfUrlCount > $dfMaxUrlCount) {
							//break;
						}

						$urlsCount = 0;
						$urls = array();
					}
				}
				if (!empty($urls)) {
					$this->request ($urls, $options);
					$dfUrlCount += count ($urls);
					if ($dfUrlCount > $dfMaxUrlCount) {
						//break;
					}
				}
			}

		}

		catch (Exception $e) {
			df_handle_entry_point_exception ($e);
		}

        return $this;
    }




	/**
	 * Если в адресе присутствуют символы кириллицы, то кодируем их.
	 * Дело в том, что браузер будет кодировать символы кириллицы в любом случае.
	 * И, если бы мы из сейчас не кодировали,
	 * при загрузке той же самой страницы из браузера
	 * ключ кэша был бы иным, и от нашего формируемого сейчас кэша не было бы толку.
	 *
	 * @param string $path
	 * @return string
	 */
	private function encodeUrlPath ($path) {

		df_param_string ($path, 0);

		/** @var string $result  */
		$result =
			implode (
				Df_Core_Const::T_URL_PATH_SEPARATOR
				,
				array_map (
					'rawurlencode'
					,
					explode (
						Df_Core_Const::T_URL_PATH_SEPARATOR
						,
						$path
					)
				)
			)
		;

		df_result_string ($result);

		return $result;

	}




	/**
	 * @param array $urls
	 * @param array $options
	 * @return Df_PageCache_Model_Crawler
	 */
	private function request (array $urls, array $options) {

		$this->getCurl()->multiRequest($urls, $options);

		return $this;

	}
	
	
	
	
	/**
	 * @return Varien_Http_Adapter_Curl
	 */
	private function getCurl () {
	
		if (!isset ($this->_curl)) {
	
			/** @var Varien_Http_Adapter_Curl $result  */
			$result = new Varien_Http_Adapter_Curl();
	
			df_assert ($result instanceof Varien_Http_Adapter_Curl);
	
			$this->_curl = $result;
	
		}
	
	
		df_assert ($this->_curl instanceof Varien_Http_Adapter_Curl);
	
		return $this->_curl;
	
	}
	
	
	/**
	* @var Varien_Http_Adapter_Curl
	*/
	private $_curl;	








    /**
     * Get configuration for stores base urls.
     * array(
     *  $index => array(
     *      'store_id'  => $storeId,
     *      'base_url'  => $url,
     *      'cookie'    => $cookie
     *  )
     * )
     * @return array
     */
    private function getStoresInfo()
    {
        $baseUrls = array();

        foreach (Mage::app()->getStores() as $store) {
            $website = Mage::app()->getWebsite($store->getWebsiteId());
            $defaultWebsiteStore = $website->getDefaultStore();
            $defaultWebsiteBaseUrl      = $defaultWebsiteStore->getBaseUrl();
            $defaultWebsiteBaseCurrency = $defaultWebsiteStore->getDefaultCurrencyCode();

            $baseUrl            = Mage::app()->getStore($store)->getBaseUrl();
            $defaultCurrency    = Mage::app()->getStore($store)->getDefaultCurrencyCode();

            $cookie = '';
            if (($baseUrl == $defaultWebsiteBaseUrl) && ($defaultWebsiteStore->getId() != $store->getId())) {
                $cookie = 'store='.$store->getCode().';';
            }

            $baseUrls[] = array(
                'store_id' => $store->getId(),
                'base_url' => $baseUrl,
                'cookie'   => $cookie,
            );
            if ($store->getConfig(self::XML_PATH_CRAWL_MULTICURRENCY)
                && $store->getConfig(Df_PageCache_Model_Processor::XML_PATH_CACHE_MULTICURRENCY)) {
                $currencies = $store->getAvailableCurrencyCodes(true);
                foreach ($currencies as $currencyCode) {
                    if ($currencyCode != $defaultCurrency) {
                        $baseUrls[] = array(
                            'store_id' => $store->getId(),
                            'base_url' => $baseUrl,
                            'cookie'   => $cookie.'currency='.$currencyCode.';'
                        );
                    }
                }
            }
        }
        return $baseUrls;
    }





    const XML_PATH_CRAWLER_ENABLED     = 'df_speed/page_cache/auto_crawling__enabled';
    const XML_PATH_CRAWLER_THREADS     = 'df_speed/page_cache/auto_crawling__num_threads';
    const XML_PATH_CRAWL_MULTICURRENCY = 'df_speed/page_cache/auto_crawling__multicurrency';


    const USER_AGENT = 'MagentoCrawler';

    protected function _construct()
    {
        $this->_init('df_pagecache/crawler');
    }

}
