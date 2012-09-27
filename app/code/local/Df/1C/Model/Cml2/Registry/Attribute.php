<?php

class Df_1C_Model_Cml2_Registry_Attribute extends Df_Core_Model_Abstract {



	/**
	 * @param string $attributeLabel
	 * @return string
	 */
	public function generateImportedAttributeCodeByLabel ($attributeLabel) {

		df_param_string ($attributeLabel, 0);


		/** @var string $result  */
		$result = null;

		/** @var int $counter  */
		$attempt = 1;


		/** @var Mage_Catalog_Model_Resource_Eav_Attribute $attribute  */
		$attribute =
			df_model (
				'catalog/resource_eav_attribute'
			)
		;

		df_assert ($attribute instanceof Mage_Catalog_Model_Resource_Eav_Attribute);


		while (true) {

			$result =
				$this->generateImportedAttributeCodeByLabelUsingSuffix (
					$attributeLabel
					,
					$attempt
				)
			;

			df_assert_string ($result);


			$attribute
				->loadByCode (
					df_helper()->eav()->getProductEntityTypeId()
					,
					$result
				)
			;

			if (1 > intval ($attribute->getId())) {
				break;
			}

			$attribute->setData (array ());

			$attempt++;

		};


		df_result_string ($result);

		return $result;

	}





	/**
	 * @param string $attributeLabel
	 * @param int $attempt [optional]
	 * @return string
	 */
	private function generateImportedAttributeCodeByLabelUsingSuffix ($attributeLabel, $attempt = 1) {

		df_param_string ($attributeLabel, 0);
		df_param_integer ($attempt, 1);


		/** @var string $result  */
		$result =
			substr (
				implode (
					'__'
					,
					array (
						'rm_1c'
						,
						strtr (
							df_output()->transliterate ($attributeLabel)
							,
							array (
								'-' => '_'
							)
						)
					)
				)
				,
				0
				,
					(1 === $attempt)
				?
					Mage_Eav_Model_Entity_Attribute::ATTRIBUTE_CODE_MAX_LENGTH
				:
						Mage_Eav_Model_Entity_Attribute::ATTRIBUTE_CODE_MAX_LENGTH
					-
						(1 + strlen (df_string ($attempt)))
			)
		;


		if (1 < $attempt) {
			$result =
				implode (
					'_'
					,
					array (
						$result
						,
						$attempt
					)
				)
			;
		}

		df_result_string ($result);

		return $result;

	}





	/**
	 * @static
	 * @return string
	 */
	public static function getClass () {
		return 'Df_1C_Model_Cml2_Registry_Attribute';
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


