<?php

/**
 * Товарищ!
 * После изменения структуры БД не забывай удалить кеш, потому что Magento кеширует структуру БД!
 */

/** @var Df_Directory_Model_Resource_Setup $this */


$this->startSetup();



$this->install_2_0_0 ();



$this->endSetup();




