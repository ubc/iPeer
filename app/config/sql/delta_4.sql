CREATE INDEX user_id_index on user_enrols(user_id);
ALTER TABLE `personalizes` ADD INDEX ( `user_id` , `attribute_code` );
