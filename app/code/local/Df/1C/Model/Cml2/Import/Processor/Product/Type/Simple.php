<?php

class Df_1C_Model_Cml2_Import_Processor_Product_Type_Simple
	extends Df_1C_Model_Cml2_Import_Processor_Product_Type_Simple_Abstract {



	/**
	 * @override
	 * @return Df_1C_Model_Cml2_Import_Processor
	 */
	public function process () {

		if ($this->getEntityOffer()->isTypeSimple()) {

			$this->getImporter()->import();

			/** @var Mage_Catalog_Model_Product $product */
			$product = $this->getImporter()->getProduct();

			df_assert ($product instanceof Mage_Catalog_Model_Product);
			df_assert_between (intval ($product->getId()), 1);

			df_helper()->_1c()
				->log (
					sprintf (
						'%s товар «%s».'
						,
						!is_null ($this->getExistingMagentoProduct()) ? 'Обновлён' : 'Создан'
						,
						$product->getName()
					)
				)
			;

			df_helper()->dataflow()->registry()->products()->addEntity ($product);

			df_assert (
				!is_null (
					$this->getExistingMagentoProduct()
				)
			);

		}

		return $this;

	}




	/**
	 * @override
	 * @return string
	 */
	protected function getSku () {

		if (!isset ($this->_sku)) {

			/** @var string $result  */
			$result = null;

 			if (!is_null ($this->getExistingMagentoProduct())) {
				$result = $this->getExistingMagentoProduct()->getSku();
			}
			else {
				$result = $this->getEntityProduct()->getSku();

				if (is_null($result)) {
					df_helper()->_1c()
						->log (
							sprintf (
								'У товара %s в 1С отсутствует артикул.'
								,
								$this->getEntityProduct()->getName()
							)
						)
					;

					$result = $this->getEntityOffer()->getExternalId();
				}


				if (df_helper()->catalog()->product()->getIdBySku($result)) {
					/**
					 * Вдруг товар с данным артикулом уже присутствует в системе?
					 */

					df_helper()->_1c()
						->log (
							sprintf (
								'Товар с артикулом %s уже присутствует в магазине.'
								,
								$result
							)
						)
					;


					df_assert (
						$result !== $this->getEntityOffer()->getExternalId()
					);

					$result = $this->getEntityOffer()->getExternalId();
				}

			}


			df_assert_string ($result);

			$this->_sku = $result;

		}


		df_result_string ($this->_sku);

		return $this->_sku;

	}


	/**
	* @var string
	*/
	private $_sku;





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Import_Processor_Product_Type_Simple';
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


