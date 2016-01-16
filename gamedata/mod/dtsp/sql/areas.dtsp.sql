(
  _id smallint unsigned NOT NULL default '0',
  `n` varchar(15) NOT NULL default '',
  `c` varchar(5) not null default '',
  `r` tinyint unsigned NOT NULL default '0',
  info varchar(255) not null default '',
  
  PRIMARY KEY  (_id),
  INDEX REGION (r)
)