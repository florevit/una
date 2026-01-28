SET @sName = 'bx_timeline';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_timeline_ef_links` (
  `event_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_id`)
);

CREATE TABLE IF NOT EXISTS `bx_timeline_ef_polls` (
  `event_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_id`)
);
