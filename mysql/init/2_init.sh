#!/bin/sh
mysql --version
mysql -uroot -pmmjmmj --local-infile locales -e "LOAD DATA LOCAL INFILE '/docker-entrypoint-initdb.d/place.txt' INTO TABLE place FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES"
mysql -uroot -pmmjmmj --local-infile locales -e "LOAD DATA LOCAL INFILE '/docker-entrypoint-initdb.d/place_alias.txt' INTO TABLE place_alias FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES"
mysql -uroot -pmmjmmj --local-infile locales -e "LOAD DATA LOCAL INFILE '/docker-entrypoint-initdb.d/place_attribute.txt' INTO TABLE place_attribute FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES"
mysql -uroot -pmmjmmj --local-infile locales -e "LOAD DATA LOCAL INFILE '/docker-entrypoint-initdb.d/parent_place.txt' INTO TABLE parent_place FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES"
mysql -uroot -pmmjmmj --local-infile locales -e "LOAD DATA LOCAL INFILE '/docker-entrypoint-initdb.d/parent_place_alias.txt' INTO TABLE parent_place_alias FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES"
mysql -uroot -pmmjmmj --local-infile locales -e "LOAD DATA LOCAL INFILE '/docker-entrypoint-initdb.d/place_parent_place.txt' INTO TABLE place_parent_place FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES"