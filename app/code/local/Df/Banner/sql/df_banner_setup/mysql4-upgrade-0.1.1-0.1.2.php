<?php
$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('df_banner_item')} ADD `banner_order` int(11) default 0;

");
$installer->endSetup();
?>