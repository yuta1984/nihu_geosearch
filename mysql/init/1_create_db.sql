CREATE DATABASE IF NOT EXISTS locales;

CREATE TABLE IF NOT EXISTS locales.place(
  id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  attribute INT NOT NULL,
  shape TINYINT NOT NULL,
  latitude DOUBLE NOT NULL,
  longitude DOUBLE NOT NULL,
  latitude2 DOUBLE DEFAULT NULL,
  longitude2 DOUBLE DEFAULT NULL,
  source TEXT,
  source_description TEXT,
  note VARCHAR(1000),
  created_at TIMESTAMP NOT NULL default current_timestamp,
  updated_at TIMESTAMP NOT NULL default current_timestamp on update current_timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE locales.place AUTO_INCREMENT = 10000000;
CREATE TABLE IF NOT EXISTS locales.place_alias(
  id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  place_id BIGINT NOT NULL,
  name VARCHAR(100) NOT NULL,
  alias_name VARCHAR(100) NOT NULL,
  classification VARCHAR(1000),
  note VARCHAR(1000),
  created_at TIMESTAMP NOT NULL default current_timestamp,
  updated_at TIMESTAMP NOT NULL default current_timestamp on update current_timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS locales.place_attribute(
  id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  division VARCHAR(100),
  attribute_name VARCHAR(100) NOT NULL,
  has_higher BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP NOT NULL default current_timestamp,
  updated_at TIMESTAMP NOT NULL default current_timestamp on update current_timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS locales.parent_place(
  id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  attribute INT NOT NULL,
  shape TINYINT NOT NULL,
  latitude DOUBLE DEFAULT NULL,
  longitude DOUBLE DEFAULT NULL,
  latitude2 DOUBLE DEFAULT NULL,
  longitude2 DOUBLE DEFAULT NULL,
  source TEXT,
  source_description TEXT,
  note VARCHAR(1000),
  created_at TIMESTAMP NOT NULL default current_timestamp,
  updated_at TIMESTAMP NOT NULL default current_timestamp on update current_timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS locales.parent_place_alias(
  id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  place_id BIGINT NOT NULL,
  name VARCHAR(100) NOT NULL,
  alias_name VARCHAR(100) NOT NULL,
  classification VARCHAR(1000),
  note VARCHAR(1000),
  created_at TIMESTAMP NOT NULL default current_timestamp,
  updated_at TIMESTAMP NOT NULL default current_timestamp on update current_timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS locales.place_parent_place(
  id BIGINT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  place_id BIGINT NOT NULL,
  name VARCHAR(100) NOT NULL,
  higher_place_id BIGINT NOT NULL,
  higher_name VARCHAR(100) NOT NULL,
  classification VARCHAR(1000),
  note VARCHAR(1000),
  created_at TIMESTAMP NOT NULL default current_timestamp,
  updated_at TIMESTAMP NOT NULL default current_timestamp on update current_timestamp
) ENGINE=InnoDB DEFAULT CHARSET=utf8;