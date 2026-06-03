-- TABLES
ALTER TABLE `bx_antispam_dnsbl_rules` MODIFY `postvresp` varchar(255) NOT NULL;

UPDATE `bx_antispam_dnsbl_rules` SET `postvresp`='127.0.0.2,127.0.0.3,127.0.0.4,127.0.0.9', `recheck`='https://check.spamhaus.org/results/?query=%s' WHERE `chain`='spammers' AND `zonedomain`='sbl.spamhaus.org.';
