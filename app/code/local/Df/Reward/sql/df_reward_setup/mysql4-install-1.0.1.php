<?php


/* @var $installer Mage_Customer_Model_Entity_Setup */
$installer = $this;
$installer->startSetup();



$tableReward = $installer->getTable('df_reward/reward');
$tableRewardHistory = $installer->getTable('df_reward/reward_history');
$tableRewardRate = $installer->getTable('df_reward/reward_rate');
$tableRewardSalesRule = $installer->getTable('df_reward/reward_salesrule');





$installer->run("


CREATE TABLE IF NOT EXISTS `{$tableReward}` (
	`reward_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`customer_id` int(10) unsigned NOT NULL DEFAULT '0',
	`website_id` SMALLINT(5) UNSIGNED DEFAULT NULL,
	`points_balance` int(11) unsigned NOT NULL DEFAULT '0',
	`website_currency_code` CHAR(3) DEFAULT NULL,
	PRIMARY KEY (`reward_id`),
	UNIQUE KEY `UNQ_CUSTOMER_WEBSITE` (`customer_id`,`website_id`),
	INDEX `FK_REWARD_WEBSITE_ID` (`website_id`),
	CONSTRAINT `FK_REWARD_CUSTOMER_ID`
		FOREIGN KEY (`customer_id`)
		REFERENCES `{$installer->getTable('customer_entity')}` (`entity_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");



$installer->run("
CREATE TABLE IF NOT EXISTS `{$tableRewardHistory}` (
	`history_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`reward_id` int(11) unsigned NOT NULL DEFAULT '0',
	`website_id` smallint(5) unsigned NOT NULL DEFAULT '0',
	`store_id` SMALLINT(5) UNSIGNED DEFAULT NULL,
	`action` tinyint(3) NOT NULL DEFAULT '0',
	`entity` int(11) DEFAULT NULL,
	`points_balance` int(11) unsigned NOT NULL DEFAULT '0',
	`points_delta` int(11) NOT NULL DEFAULT '0',
	`points_used` int(11) NOT NULL DEFAULT '0',
	`currency_amount` decimal(12,4) unsigned NOT NULL DEFAULT '0.0000',
	`currency_delta` decimal(12,4) NOT NULL DEFAULT '0.0000',
	`base_currency_code` varchar(5) NOT NULL,
	`additional_data` text NOT NULL,
	`comment` text NOT NULL,
	`created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`expired_at_static` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`expired_at_dynamic` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
	`is_expired` tinyint(3) NOT NULL DEFAULT '0',
	`is_duplicate_of` int(11) unsigned DEFAULT NULL,
	`notification_sent` tinyint(3) NOT NULL DEFAULT '0',


	PRIMARY KEY (`history_id`),
	KEY `IDX_REWARD_ID` (`reward_id`),
	KEY `IDX_WEBSITE_ID` (`website_id`),
	KEY `IDX_STORE_ID` (`store_id`),
	CONSTRAINT `FK_REWARD_HISTORY_REWARD_ID`
		FOREIGN KEY (`reward_id`)
		REFERENCES `{$tableReward}` (`reward_id`) ON DELETE CASCADE ON UPDATE CASCADE,

	CONSTRAINT `FK_REWARD_HISTORY_WEBSITE_ID`
		FOREIGN KEY (`website_id`)
		REFERENCES `{$installer->getTable('core_website')}` (`website_id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE,

	CONSTRAINT `FK_REWARD_HISTORY_STORE_ID`
	FOREIGN KEY (`store_id`)
		REFERENCES `{$installer->getTable('core_store')}` (`store_id`)
		ON DELETE SET NULL
		ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");



$installer->run("
CREATE TABLE IF NOT EXISTS `{$tableRewardRate}` (
  `rate_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `website_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `customer_group_id` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
  `direction` tinyint(3) NOT NULL DEFAULT '1',
  `points` int(11) NOT NULL DEFAULT '0',
  `currency_amount` decimal(12,4) unsigned NOT NULL DEFAULT '0.0000',
  PRIMARY KEY (`rate_id`),
  UNIQUE KEY `IDX_WEBSITE_GROUP_DIRECTION` (`website_id`,`customer_group_id`,`direction`),
  KEY `IDX_WEBSITE_ID` (`website_id`),
  KEY `IDX_CUSTOMER_GROUP_ID` (`customer_group_id`),
  CONSTRAINT `FK_REWARD_RATE_WEBSITE_ID`
  	FOREIGN KEY (`website_id`)
  	REFERENCES `{$installer->getTable('core_website')}` (`website_id`)
  	ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");



$installer->run("
CREATE TABLE IF NOT EXISTS `{$tableRewardSalesRule}` (
    `rule_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `points_delta` int(11) UNSIGNED NOT NULL DEFAULT '0',
    KEY `FK_REWARD_SALESRULE_RULE_ID` (`rule_id`),
    CONSTRAINT `FK_REWARD_SALESRULE_RULE_ID`
    	FOREIGN KEY (`rule_id`)
    	REFERENCES `{$installer->getTable('salesrule/rule')}` (`rule_id`)
    	ON DELETE CASCADE
    	ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8
");




$installer->addAttribute('quote', 'use_reward_points', array('type' => 'int'));
$installer->addAttribute('quote', 'reward_points_balance', array('type' => 'int'));

/**
 * Эти 2 строки в оригинальном коде почему-то дублируется.
 * Может быть, программист имел в виду добавить указанные свойства
 * не только к объекту quote, но и к какому-то другому объекту?
 */
$installer->addAttribute('quote', 'base_reward_currency_amount', array('type' => 'decimal'));
$installer->addAttribute('quote', 'reward_currency_amount', array('type' => 'decimal'));



$installer->addAttribute('quote_address', 'reward_points_balance', array('type' => 'int'));
$installer->addAttribute('quote_address', 'base_reward_currency_amount', array('type' => 'decimal'));
$installer->addAttribute('quote_address', 'reward_currency_amount', array('type' => 'decimal'));

$installer->addAttribute('order', 'reward_points_balance', array('type' => 'int'));
$installer->addAttribute('order', 'base_reward_currency_amount', array('type' => 'decimal'));
$installer->addAttribute('order', 'reward_currency_amount', array('type' => 'decimal'));
$installer->addAttribute('order', 'base_reward_currency_amount_invoiced', array('type' => 'decimal'));
$installer->addAttribute('order', 'reward_currency_amount_invoiced', array('type' => 'decimal'));
$installer->addAttribute('order', 'base_reward_currency_amount_refunded', array('type' => 'decimal'));
$installer->addAttribute('order', 'reward_currency_amount_refunded', array('type' => 'decimal'));



/**
 * Эта строка в оригинальном коде почему-то дублируется.
 * Может быть, программист имел в виду добавить reward_points_balance_refunded
 * не только к объекту order, но и к какому-то другому объекту?
 */
$installer->addAttribute('order', 'reward_points_balance_refunded', array('type' => 'int'));



$installer->addAttribute('invoice', 'reward_points_balance', array('type' => 'int'));
$installer->addAttribute('invoice', 'base_reward_currency_amount', array('type' => 'decimal'));
$installer->addAttribute('invoice', 'reward_currency_amount', array('type' => 'decimal'));

$installer->addAttribute('creditmemo', 'reward_points_balance', array('type' => 'int'));
$installer->addAttribute('creditmemo', 'base_reward_currency_amount', array('type' => 'decimal'));
$installer->addAttribute('creditmemo', 'reward_currency_amount', array('type' => 'decimal'));



$installer->addAttribute('order', 'reward_points_balance_to_refund', array('type' => 'int'));
$installer->addAttribute('creditmemo', 'reward_points_balance_to_refund', array('type' => 'int'));





$installer->addAttribute('order', 'reward_salesrule_points', array('type' => 'int'));



$installer->getConnection()
	->delete($installer->getTable('cms/page'), '`identifier` = \'reward-points\'');

$now = new Zend_Date(time());
$now = $now->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
$installer->getConnection()->insert($installer->getTable('cms/page'), array(
    'title'           => 'Накопительная программа',
    'root_template'   => 'one_column',
    'identifier'      => 'reward-points',
    'content_heading' => 'Накопительная программа',
    'creation_time'   => $now,
    'update_time'     => $now,
    'is_active'       => 1,
    'content' => '<p>По накопительной программе наш магазин начисляет Вам баллы за покупки
	и некоторые полезные поступки
	(приглашение друзей, написание обзоров к товарам — смотрите подробности ниже)
</p>

<h2>Зачем нужны баллы?</h2>
<p>Баллы идут в счёт оплаты новых Ваших заказов. Вы платите за заказ меньше!</p>

<h2>Как получить баллы?</h2>
<p>Баллы начисляются в следующих случаях:</p>
<ul>
<li>За покупки: Вы получаете баллы за каждую покупку в нашем магазине.</li>
<li>За регистрацию в нашем магазине.</li>
<li>За подписку на новостную рассылку.</li>
<li>За приглашение друзей в наш магазин.</li>
<li>За регистрацию приглашённых Вами друзей в нашем магазине.</li>
<li>За покупки Ваших приглашённых Вами друзей в нашем магазине.</li>
<li>За написание обзоров к товарам.</li>
<li>За добавление меток (тегов) к товаром</li>
</ul>

<h2>Как баллы влияют на списание стоимости заказа?</h2>
<p>1 балл = 1 рубль.</p>

<h2>Как оплатить покупку баллами?</h2>
<p>При оформлении заказа, если Вы накопили уже достаточное количество баллов,
	магазин предложит Вам зачесть баллы в счёт стоимости заказа.
	Оставшуюся часть стоимости заказа Вы можете оплатить другими, традиционными способами.
</p>

<h2>Где увидеть мои баллы?</h2>
<p>Информация о собранных и потраченных Вами баллах
	находится в Вашем <a href="{{store url="customer/account"}}">Личном кабинете</a>.
</p>
',
));

$pageId = $installer->getConnection()->lastInsertId();
$installer->getConnection()->insert(
    $installer->getTable('cms/page_store'), array('page_id' => $pageId, 'store_id' => 0)
);




$columnsToMove = array(
    'reward_update_notification',
    'reward_warning_notification'
);

foreach ($columnsToMove as $column) {
    $installer->addAttribute('customer', $column,
        array('type' => 'int', 'visible' => 0, 'visible_on_front' => 1)
    );
}



$installer->endSetup();
