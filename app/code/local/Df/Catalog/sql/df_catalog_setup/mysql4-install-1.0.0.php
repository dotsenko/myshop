<?php

/**
 * Товарищ!
 * После изменения структуры БД не забывай удалить кеш, потому что Magento кеширует структуру БД!
 */

/** @var Df_Catalog_Model_Resource_Setup $this */

$installer = $this;
/** @var Df_Catalog_Model_Resource_Setup $installer */

$installer->startSetup();


if (!$installer->getAttributeId ('catalog_category', 'thumbnail')) {
	/** Magento < 1.5 */

	$entityTypeId     = $installer->getEntityTypeId('catalog_category');
	$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);

	$installer->addAttribute('catalog_category', 'df_thumbnail', array(
		'type'              => 'varchar',
		'backend'           => 'catalog/category_attribute_backend_image',
		'frontend'          => '',
		'label'             => 'Thumbnail Image (Magento 1.4)',
		'input'             => 'image',
		'class'             => '',
		'source'            => '',
		'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
		'visible'           => true,
		'required'          => false,
		'user_defined'      => false,
		'default'           => '',
		'searchable'        => false,
		'filterable'        => false,
		'comparable'        => false,
		'visible_on_front'  => false,
		'unique'            => false,
	));

	$installer
		->addAttributeToGroup(
			$entityTypeId

			,
			$attributeSetId

			/**
			 * Не используем синтаксис
			 * $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId)
			 *
			 * потому что он при предварительно включенной русификации
			 * может приводить к созданию дополнительной вкладки ("Основное")
			 * вместо размещения свойства на главнйо вкладке ("Главное").
			 */
			,
			'General Information'

			,
			'df_thumbnail'

			,
			4
		)
	;
}


$installer->endSetup();


