
CREATE TABLE jobs_listing (
  lid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  status int(3) NOT NULL default '1',
  expire char(3) NOT NULL default '',
  type varchar(100) NOT NULL default '',
  company varchar(100) NOT NULL default '',
  desctext text NOT NULL,
  requirements text NOT NULL,
  tel varchar(30) NOT NULL default '',
  price varchar(100) NOT NULL default '',
  typeprice varchar(100) NOT NULL default '',
  contactinfo mediumtext NOT NULL,
  contactinfo1 mediumtext NOT NULL,
  contactinfo2 mediumtext NOT NULL,
  date int(10) NOT NULL default '0',
  email varchar(100) NOT NULL default '',
  submitter varchar(60) NOT NULL default '',
  usid varchar(6) NOT NULL default '',
  town varchar(100) NOT NULL default '',
  state varchar(100) NOT NULL default '',
  valid int(3) NOT NULL default '1',
  premium tinyint(2) NOT NULL default '0',
  photo varchar(100) NOT NULL default '',
  view varchar(10) NOT NULL default '0',
  PRIMARY KEY  (lid)
) ENGINE=MyISAM;

INSERT INTO jobs_listing VALUES (2, 1, 'Example Job','1', '14', 'Full Time', 'Example Company', 'Here you can put a complete description of the job you are offering.', 'Here you can put all the requirements you have for the Job being offered.', '', '16.00', 'Per Hour', 'Some Examples would be:\r\n\r\n1. Send Resume to:\r\n   Example Company\r\n   22 Example Adrress\r\n   Southington, Ct. 06489\r\n\r\n2. Reply in person', '', '', '1083798448', 'admin@jlmzone.com', 'john', '1', 'Southington', 'Ct', '1', '0', '', '0');
# --------------------------------------------------------

CREATE TABLE jobs_resume (
  lid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  name varchar(100) NOT NULL default '',
  title varchar(100) NOT NULL default '',
  status int(3) NOT NULL default '1',
  exp varchar(100) NOT NULL default '',
  expire char(3) NOT NULL default '',
  private varchar(100) NOT NULL default '',
  tel varchar(30) NOT NULL default '',
  salary varchar(100) NOT NULL default '',
  typeprice varchar(100) NOT NULL default '',
  date int(10) NOT NULL default '0',
  email varchar(100) NOT NULL default '',
  submitter varchar(60) NOT NULL default '',
  usid varchar(6) NOT NULL default '',
  town varchar(100) NOT NULL default '',
  state varchar(100) NOT NULL default '',
  valid int(3) NOT NULL default '1',
  rphoto varchar(100) NOT NULL default '',
  resume varchar(100) NOT NULL default '',
  view varchar(10) NOT NULL default '0',
  PRIMARY KEY  (lid)
) ENGINE=MyISAM;

CREATE TABLE jobs_categories (
  cid int(11) NOT NULL auto_increment,
  pid int(5) unsigned NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  img varchar(150) NOT NULL default '',
  ordre int(5) NOT NULL default '0',
  affprice int(5) NOT NULL default '0',
  PRIMARY KEY  (cid)
) ENGINE=MyISAM;

INSERT INTO jobs_categories VALUES (1, 0, 'Job Listings', 'default.gif', 0, 1);

CREATE TABLE jobs_res_categories (
  cid int(11) NOT NULL auto_increment,
  pid int(5) unsigned NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  img varchar(150) NOT NULL default '',
  ordre int(5) NOT NULL default '0',
  affprice int(5) NOT NULL default '0',
  PRIMARY KEY  (cid)
) ENGINE=MyISAM;

INSERT INTO jobs_res_categories VALUES (1, 0, 'Medical', 'default.gif', 0, 1);

CREATE TABLE jobs_type (
  id_type int(11) NOT NULL auto_increment,
  nom_type varchar(150) NOT NULL default '',
  PRIMARY KEY  (id_type)
) ENGINE=MyISAM;

INSERT INTO jobs_type VALUES (1,'Full Time');
INSERT INTO jobs_type VALUES (2,'Part Time');

CREATE TABLE jobs_price (
  id_price int(11) NOT NULL auto_increment,
  nom_price varchar(150) NOT NULL default '',
  PRIMARY KEY  (id_price)
) ENGINE=MyISAM;

INSERT INTO jobs_price VALUES (1,'Per Hour');
INSERT INTO jobs_price VALUES (2,'Annual');

CREATE TABLE jobs_companies (
  comp_id int(11) NOT NULL auto_increment,
  comp_pid int(5) unsigned NOT NULL default '0',
  comp_name varchar(100) NOT NULL default '',
  comp_address varchar(100) NOT NULL default '',
  comp_address2 varchar(100) NOT NULL default '',
  comp_city varchar(100) NOT NULL default '',
  comp_state varchar(100) NOT NULL default '',
  comp_zip varchar(20) NOT NULL default '',
  comp_phone varchar(30) NOT NULL default '',
  comp_fax varchar(30) NOT NULL default '',
  comp_url varchar(150) NOT NULL default '',
  comp_img varchar(150) NOT NULL default '',
  comp_usid varchar(6) NOT NULL default '',
  comp_user1 varchar(6) NOT NULL default '',
  comp_user2 varchar(6) NOT NULL default '',
  comp_contact text NOT NULL,
  comp_user1_contact text NOT NULL,
  comp_user2_contact text NOT NULL,
  comp_date_added int(10) NOT NULL default '0',
  PRIMARY KEY  (comp_id),
  KEY comp_name (comp_name)
) ENGINE=MyISAM;

CREATE TABLE jobs_replies (
  r_lid int(11) NOT NULL auto_increment,
  lid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  date int(10) NOT NULL default '0',
  submitter varchar(60) NOT NULL default '',
  message text NOT NULL,
  resume varchar(100) NOT NULL default '',
  tele varchar(30) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  r_usid int(11) NOT NULL default '0',
  company varchar(100) NOT NULL default '',
  PRIMARY KEY  (r_lid),
  KEY lid (lid)
) ENGINE=MyISAM;

CREATE TABLE jobs_created_resumes (
  res_lid int(11) NOT NULL auto_increment,
  lid int(11) NOT NULL default '0',
  made_resume text NOT NULL,
  date int(10) NOT NULL default '0',
  usid int(11) NOT NULL default '0',
  PRIMARY KEY  (res_lid),
  KEY lid (lid)
) ENGINE=MyISAM;

CREATE TABLE jobs_pictures (
  cod_img int(11) NOT NULL auto_increment,
  title varchar(255) NOT NULL,
  date_added int(10) NOT NULL default '0',
  date_modified int(10) NOT NULL default '0',
  lid int(11) NOT NULL default '0',
  uid_owner varchar(50) NOT NULL,
  url text NOT NULL,
  PRIMARY KEY  (cod_img)
) ENGINE=MyISAM  ;


CREATE TABLE jobs_region (
  rid int(11) NOT NULL auto_increment,
  pid int(5) unsigned NOT NULL default '0',
  name CHAR(40) NOT NULL,
  abbrev CHAR(2) NOT NULL,
  PRIMARY KEY  (rid)
) ENGINE=MyISAM;








