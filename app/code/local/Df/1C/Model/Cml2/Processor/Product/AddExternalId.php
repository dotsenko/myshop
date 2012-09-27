<?php

class Df_1C_Model_Cml2_Processor_Product_AddExternalId extends Df_Core_Model_Abstract {


	/**
	 * @return Df_1C_Model_Cml2_Processor_Product_AddExternalId
	 */
	public function process () {

		/**
		 * Данный товар не был импортирован из 1С:Управление торговлей,
		 * а был создан администратором магазина вручную.
		 * Назначаем этому товару внешний идентификатор.
		 */
		Mage
			::log (
				sprintf (
							'У товара «%s» отсутствует внешний идентификатор'
						.
							"\r\nНазначаем этому товару идентификатор «%s»"
					,
					$this->getProduct()->getName()
					,
					$this->getExternalId()
				)
			)
		;


		/**
		 * Добавляем к прикладному типу товаров
		 * свойство для учёта внешнего идентификатора товара в 1С:Управление торговлей
		 */

		df_helper()->_1c()->cml2()->attributeSet()
			->addExternalIdToAttributeSet (
				$this->getProduct()->getAttributeSetId()
			)
		;

		Mage
			::log (
				sprintf (
					'Добавили к прикладному типу товаров №%d группу свойств «%s»'
					,
					$this->getProduct()->getAttributeSetId()
					,
					Df_1C_Const::PRODUCT_ATTRIBUTE_GROUP_NAME
				)
			)
		;

		df_helper()->catalog()->product()
			->saveAttributes (
				$this->getProduct()
				,
				array (
					Df_1C_Const::ENTITY_1C_ID => $this->getExternalId()
				)
				/**
				 * Единое значение для всех витрин
				 */
				,
				$storeId = null
			)
		;

		$this->getProduct()->setData (Df_1C_Const::ENTITY_1C_ID, $this->getExternalId());



		/** @var Df_Catalog_Model_Product $testProduct */
		$testProduct = df_model (Df_Catalog_Model_Product::getNameInMagentoFormat());

		$testProduct->load ($this->getProduct()->getId());

		if ($this->getExternalId() !== $testProduct->getData (Df_1C_Const::ENTITY_1C_ID)) {

			df_error (
				sprintf (
					'Не удалось добавить внешний идентификатор к товару «%s»'
					,
					$this->getProduct()->getName()
				)
			);

		}
		else {
			df_helper()->_1c()
				->log (
					sprintf (
						'Товару «%s» назначен внешний идентификатор «%s»'
						,
						$this->getProduct()->getName()
						,
						$this->getProduct()->getData (Df_1C_Const::ENTITY_1C_ID)
					)
				)
			;
		}

		return $this;
	}



	/**
	 * @return string
	 */
	private function getExternalId () {
		return $this->cfg (self::PARAM__EXTERNAL_ID);
	}



	/**
	 * @return Df_Catalog_Model_Product
	 */
	private function getProduct () {
		return $this->cfg (self::PARAM__PRODUCT);
	}


	/**
	 * @override
	 * @return void
	 */
	protected function _construct () {
		parent::_construct ();
		$this
			->addValidator (
				self::PARAM__EXTERNAL_ID, new Df_Zf_Validate_String()
			)
			->validateClass (
				self::PARAM__PRODUCT, Df_Catalog_Model_Product::getNameInMagentoFormat()
			)
		;
	}


	const PARAM__EXTERNAL_ID = 'external_id';
	const PARAM__PRODUCT = 'product';



	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Processor_Product_AddExternalId';
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



	/**
	 * @param Df_Catalog_Model_Product $product
	 * @param string $externalId
	 */
	public static function processStatic (Df_Catalog_Model_Product $product, $externalId) {

		df_param_string ($externalId, 1);

		/** @var Df_1C_Model_Cml2_Processor_Product_AddExternalId $processor */
		$processor =
			df_model (
				self::getNameInMagentoFormat()
				,
				array (
					self::PARAM__PRODUCT => $product
					,
					self::PARAM__EXTERNAL_ID => $externalId
				)
			)
		;

		df_assert ($processor instanceof Df_1C_Model_Cml2_Processor_Product_AddExternalId);

		$processor->process();
	}

}


