<?php
$installer = $this;
$installer->startSetup();
$installer->run("


DROP TABLE IF EXISTS {$this->getTable('magik_socialbar')};
CREATE TABLE IF NOT EXISTS {$this->getTable('magik_socialbar')} (id int not null auto_increment, name varchar(200),show_socialsites varchar(255), social_block_code text, show_pagelocation varchar(150), 
			      show_category varchar(255),store_id varchar(100), primary key(id));


DROP TABLE IF EXISTS {$this->getTable('magik_socialsites')};
CREATE TABLE IF NOT EXISTS {$this->getTable('magik_socialsites')} (id int not null auto_increment, name varchar(255) NOT NULL, favicon varchar(255) NOT NULL,
  url text NOT NULL,
  primary key(id));

INSERT INTO {$this->getTable('magik_socialsites')} VALUES (1,'Delicious','delicious.png','http://delicious.com/post?url=PERMALINK&amp;title=TITLE&amp;notes=EXCERPT'),(2,'Design Float','designfloat.png','http://www.designfloat.com/submit.php?url=PERMALINK&amp;title=TITLE'),(3,'Digg','digg.png','http://digg.com/submit?phase=2&amp;url=PERMALINK&amp;title=TITLE&amp;bodytext=EXCERPT'),(4,'Diigo','diigo.png','http://www.diigo.com/post?url=PERMALINK&amp;title=TITLE'),(5,'DZone','dzone.png','http://www.dzone.com/links/add.html?url=PERMALINK&amp;title=TITLE'),(6,'Facebook','facebook.png','http://www.facebook.com/share.php?u=PERMALINK&amp;t=TITLE'),(7,'FriendFeed','friendfeed.png','http://www.friendfeed.com/share?title=TITLE&amp;link=PERMALINK'),(8,'Google Bookmark','googlebookmark.png','http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=PERMALINK&amp;title=TITLE&amp;annotation=EXCERPT'),(9,'LinkedIn','linkedin.png','http://www.linkedin.com/shareArticle?mini=true&amp;url=PERMALINK&amp;title=TITLE&amp;source=BLOGNAME&amp;summary=EXCERPT'),(10,'Live','live.png','https://favorites.live.com/quickadd.aspx?marklet=1&amp;url=PERMALINK&amp;title=TITLE'),(11,'Mixx','mixx.png','http://www.mixx.com/submit?page_url=PERMALINK&amp;title=TITLE'),(12,'MyShare','myshare.png','http://myshare.url.com.tw/index.php?func=newurl&amp;url=PERMALINK&amp;desc=TITLE'),(13,'MySpace','myspace.png','http://www.myspace.com/Modules/PostTo/Pages/?u=PERMALINK&amp;t=TITLE'),(14,'Posterous','posterous.png','http://posterous.com/share?linkto=PERMALINK&amp;title=TITLE&amp;selection=EXCERPT'),(15,'Reddit','reddit.png','http://reddit.com/submit?url=PERMALINK&amp;title=TITLE'),(16,'StumbleUpon','stumbleupon.png','http://www.stumbleupon.com/submit?url=PERMALINK&amp;title=TITLE'),(17,'Tumblr','tumblr.png','http://www.tumblr.com/share?v=3&amp;u=PERMALINK&amp;t=TITLE&amp;s=EXCERPT'),
(18,'Twitter','twitter.png','http://twitter.com/home?status=TITLE%20-%20PERMALINK');


");


$installer->endSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->addAttribute('catalog_product', 'product_socialbar', array(
        'group'             => 'Magik Socialbar',
        'type'              => 'int',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Disable Social Bar for this product',
        'input'             => 'boolean',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => true,
        'default'           => '1',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false, 
        'visible_on_front'  => false,
        'unique'            => false,        
        'is_configurable'   => false
    ));	 



