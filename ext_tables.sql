CREATE TABLE tx_ipandlanguageredirect_domain_model_iptocountry (
	uid int(11) NOT NULL auto_increment,

	ipRangeStart varchar(255) DEFAULT '' NOT NULL,
	ipRangeEnd varchar(255) DEFAULT '' NOT NULL,
	countryCode varchar(255) DEFAULT '' NOT NULL,

	PRIMARY KEY (uid)
);
