<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
$installer->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable('vc_freegift_salesrule_actions')}` (
	`id` int(10) NOT NULL,
	`rule_id` int(10) NOT NULL DEFAULT '0',
	`promo_items` text,
	`promo_qty` int(10) NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `{$this->getTable('vc_freegift_salesrule_actions')}`
	ADD PRIMARY KEY (`id`);
ALTER TABLE `{$this->getTable('vc_freegift_salesrule_actions')}`
	MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
");

$installer->endSetup(); 

