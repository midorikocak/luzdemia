<?php
$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('magik_blog')};
CREATE TABLE {$this->getTable('magik_blog')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `title_slug` varchar(255) default NULL,
  `stores_selected` varchar(100) default NULL,
  `categories_selected` varchar(100) default NULL,
  `short_description` mediumtext default NULL,
  `blog_content` longtext default NULL,
  `short_blog_content` text default NULL,
  `tags` varchar(255) default NULL,
  `meta_keywords` text default NULL,
  `meta_description` text default NULL,
  `display_order` int(11) default '0',
  `enable_comment` smallint(6) default '1',
  `status` smallint(6) default '1',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;

DROP TABLE IF EXISTS {$this->getTable('magik_blog_category')};
CREATE TABLE {$this->getTable('magik_blog_category')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `title_slug` varchar(255) default NULL,
  `short_description` mediumtext default NULL,
  `meta_keywords` text default NULL,
  `meta_description` text default NULL,
  `display_order` int(11) default '0',
  `cat_pid` int(11) default '0',
  `subcategory` int(11) default NULL,
  `stores_selected` varchar(255) default NULL,
  `status` smallint(6) default '1',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;


DROP TABLE IF EXISTS {$this->getTable('magik_blog_comment')};
CREATE TABLE {$this->getTable('magik_blog_comment')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `blog_id` int(11) unsigned default NULL,
  `user_name` varchar(255) default NULL,
  `user_email` varchar(255) default NULL,
  `comment` text default NULL,
  `status` smallint(6) default '0',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 

	 