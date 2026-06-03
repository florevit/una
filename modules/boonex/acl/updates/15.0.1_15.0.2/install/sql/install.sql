SET @sName = 'bx_acl';


-- TABLES
DELETE FROM `bx_acl_level_prices` WHERE `name`='standard';
INSERT INTO `bx_acl_level_prices` (`level_id`, `name`, `caption`, `description`, `details`, `period`, `period_unit`, `trial`, `price`, `immediate`, `added`, `active`, `order`) VALUES
(3, 'standard', '_bx_acl_txt_level_caption_standard', '_bx_acl_txt_level_description_standard', '_bx_acl_txt_level_details_standard', 0, '', 0, 0, 1, UNIX_TIMESTAMP(), 0, 1);


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `tlp`.*, `tl`.`Name` AS `level_name`, `tl`.`Icon` AS `level_icon` FROM `bx_acl_level_prices` AS `tlp` LEFT JOIN `sys_acl_levels` AS `tl` ON `tlp`.`level_id`=`tl`.`ID` WHERE `tlp`.`active`<>''0'' && `tl`.`Active`=''yes'' ' WHERE `object`='bx_acl_view';
