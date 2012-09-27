<?php


/* @var $installer Mage_Customer_Model_Entity_Setup */
$installer = $this;
$installer->startSetup();


$tableReward = $installer->getTable('df_reward/reward');

$tableRewardHistory = $installer->getTable('df_reward/reward_history');
$tableRewardRate = $installer->getTable('df_reward/reward_rate');
$tableRewardSalesRule = $installer->getTable('df_reward/reward_salesrule');


$tableRewardWrong = $installer->getTable($tableReward);
$tableRewardHistoryWrong = $installer->getTable($tableRewardHistory);
$tableRewardRateWrong = $installer->getTable($tableRewardRate);



$renames =
	array (
		$tableRewardWrong => $tableReward
		,
		$tableRewardHistoryWrong => $tableRewardHistory
		,
		$tableRewardRateWrong => $tableRewardRate
	)
;


foreach ($renames as $wrongName => $correctName) {
	if ($wrongName !== $correctName) {
		$installer->run("
			RENAME TABLE `{$wrongName}` TO `{$correctName}`;
		");
	}
}


$installer->endSetup();
