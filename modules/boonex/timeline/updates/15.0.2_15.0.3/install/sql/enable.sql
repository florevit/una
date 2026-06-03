SET @sName = 'bx_timeline';


-- SETTINGS
UPDATE `sys_options` SET `extra`='FileHtml,Memcache,Memcached,APC,XCache,Redis' WHERE `name`='bx_timeline_cache_item_engine';
