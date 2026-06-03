SET @sName = 'bx_forum';


-- FORMS
UPDATE `sys_form_inputs` SET `checker_func`='Length', `checker_params`='a:2:{s:3:"min";i:3;s:3:"max";i:160;}' WHERE `object`='bx_forum' AND `name`='title';
