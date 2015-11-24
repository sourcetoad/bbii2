CREATE  TABLE `bbii_upvoted` (
  `member_id` INT UNSIGNED NOT NULL ,
  `post_id` INT UNSIGNED NOT NULL ,
  INDEX `idx_upvoted_member` (`member_id` ASC) ,
  INDEX `idx_upvoted_post` (`post_id` ASC) );

ALTER TABLE `bbii_post` ADD COLUMN `upvoted` SMALLINT(6) NULL DEFAULT '0'  AFTER `change_reason` ;

ALTER TABLE `bbii_topic` ADD COLUMN `upvoted` SMALLINT(6) NULL DEFAULT '0'  AFTER `moved` ;
