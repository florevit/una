-- ICONSET
INSERT INTO `sys_objects_iconset` (`object`, `title`, `override_class_name`, `override_class_file`) VALUES
('bx_fontawesome', 'Font Awesome Pro', 'BxFontAwesomeIconset', 'modules/boonex/fontawesome/classes/BxFontAwesomeIconset.php');


-- Studio page and widget

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_fontawesome', '_bx_fontawesome', '_bx_fontawesome', 'bx_fontawesome@modules/boonex/fontawesome/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_fontawesome', 'integrations', '{url_studio}module.php?name=bx_fontawesome', '', 'bx_fontawesome@modules/boonex/fontawesome/|std-icon.svg', '_bx_fontawesome', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

