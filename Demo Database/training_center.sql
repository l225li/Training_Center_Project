DELIMITER $$
-- -----------------------------------------------------
-- Schema training_center
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `training_center`$$
CREATE SCHEMA IF NOT EXISTS `training_center` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci$$
USE `training_center`$$

-- -----------------------------------------------------
-- Table `training_center`.`person`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `training_center`.`person` (
  `person_id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `address` VARCHAR(45) NOT NULL,
  `zip_code` VARCHAR(5) NOT NULL,
  `town` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `mobile_phone` VARCHAR(10) NOT NULL,
  `phone` VARCHAR(10) NULL,
  `is_trainer` TINYINT(1) NOT NULL DEFAULT false,
  `is_admin` TINYINT(1) NOT NULL DEFAULT false,
  `password` VARCHAR(45) NOT NULL,
  `picture_location` VARCHAR(45) NULL,
  `created_at` DATETIME NOT NULL,
  `confirmed_at` DATETIME NULL,
  `confirmation_token` VARCHAR(45) NULL,
  `renew_password_token` VARCHAR(45) NULL,
  PRIMARY KEY (`person_id`),
  UNIQUE INDEX `un_person_email` (`email` ASC),
  UNIQUE INDEX `un_person_contact` (`first_name` ASC, `last_name` ASC, `address` ASC, `zip_code` ASC, `town` ASC, `mobile_phone` ASC))
ENGINE = InnoDB$$


-- -----------------------------------------------------
-- Table `training_center`.`class`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `training_center`.`class` (
  `class_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`class_id`),
  UNIQUE INDEX `un_class_name` (`name` ASC))
ENGINE = InnoDB$$


-- -----------------------------------------------------
-- Table `training_center`.`project`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `training_center`.`project` (
  `project_id` INT NOT NULL AUTO_INCREMENT,
  `owner_id` INT NOT NULL,
  `class_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT NOW(),
  `deadline` DATETIME NOT NULL,
  `subject` VARCHAR(1024) NOT NULL,
  PRIMARY KEY (`project_id`),
  INDEX `fk_project_member` (`owner_id` ASC),
  INDEX `fk_project_class` (`class_id` ASC),
  CONSTRAINT `fk_project_member`
    FOREIGN KEY (`owner_id`)
    REFERENCES `training_center`.`person` (`person_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_project_class`
    FOREIGN KEY (`class_id`)
    REFERENCES `training_center`.`class` (`class_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB$$


-- -----------------------------------------------------
-- Table `training_center`.`team`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `training_center`.`team` (
  `team_id` INT NOT NULL AUTO_INCREMENT,
  `project_id` INT NOT NULL,
  `owner_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `summary` VARCHAR(45) NULL,
  PRIMARY KEY (`team_id`),
  INDEX `fk_team_project` (`project_id` ASC),
  INDEX `fk_team_member` (`owner_id` ASC),
  CONSTRAINT `fk_team_project`
    FOREIGN KEY (`project_id`)
    REFERENCES `training_center`.`project` (`project_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_team_member`
    FOREIGN KEY (`owner_id`)
    REFERENCES `training_center`.`person` (`person_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB$$


-- -----------------------------------------------------
-- Table `training_center`.`document`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `training_center`.`document` (
  `document_id` INT NOT NULL AUTO_INCREMENT,
  `author_id` INT NOT NULL,
  `team_id` INT NOT NULL,
  `location` VARCHAR(45) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`document_id`),
  INDEX `fk_document_member` (`author_id` ASC),
  INDEX `fk_document_team` (`team_id` ASC),
  CONSTRAINT `fk_document_member`
    FOREIGN KEY (`author_id`)
    REFERENCES `training_center`.`person` (`person_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_document_team`
    FOREIGN KEY (`team_id`)
    REFERENCES `training_center`.`team` (`team_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB$$


-- -----------------------------------------------------
-- Table `training_center`.`team_member`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `training_center`.`team_member` (
  `team_id` INT NOT NULL,
  `student_id` INT NOT NULL,
  INDEX `fk_team_member_team` (`team_id` ASC),
  INDEX `fk_team_member_person` (`student_id` ASC),
  PRIMARY KEY (`team_id`, `student_id`),
  CONSTRAINT `fk_team_member_team`
    FOREIGN KEY (`team_id`)
    REFERENCES `training_center`.`team` (`team_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_team_member_person`
    FOREIGN KEY (`student_id`)
    REFERENCES `training_center`.`person` (`person_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB$$


-- -----------------------------------------------------
-- Table `training_center`.`class_member`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `training_center`.`class_member` (
  `person_id` INT NOT NULL,
  `class_id` INT NOT NULL,
  INDEX `fk_class_member_member` (`person_id` ASC),
  INDEX `fk_class_member_class` (`class_id` ASC),
  PRIMARY KEY (`person_id`, `class_id`),
  CONSTRAINT `fk_class_member_member`
    FOREIGN KEY (`person_id`)
    REFERENCES `training_center`.`person` (`person_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_class_member_class`
    FOREIGN KEY (`class_id`)
    REFERENCES `training_center`.`class` (`class_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB$$

-- -----------------------------------------------------
-- Function inicap():
-- This function is to capitalize the first letter of
-- each word in the input text
-- -----------------------------------------------------

DROP FUNCTION IF EXISTS initcap$$
CREATE FUNCTION initcap(p_string text) RETURNS text CHARSET utf8 DETERMINISTIC
BEGIN
  DECLARE v_left, v_right TEXT;
  SET v_left='';
  SET v_right='';
  WHILE p_string LIKE '% %' DO -- if it contains a space
    SELECT SUBSTRING_INDEX(p_string,' ', 1) INTO v_left;
    SELECT SUBSTRING(p_string, LOCATE(' ', p_string) + 1) INTO p_string;
    SELECT CONCAT(v_right, ' ',
      CONCAT(UPPER(SUBSTRING(v_left, 1, 1)),
        LOWER(SUBSTRING(v_left, 2)))) INTO v_right;
  END WHILE;
  RETURN LTRIM(CONCAT(v_right, ' ', CONCAT(UPPER(SUBSTRING(p_string,1,1)), LOWER(SUBSTRING(p_string,2)))));
END$$

-- -----------------------------------------------------
-- Trigger project_before_insert_trigger:
-- Raise user exception when deadline is sooner than
-- creation date, or when title is empty
-- -----------------------------------------------------

DROP TRIGGER IF EXISTS project_before_insert_trigger$$
CREATE TRIGGER project_before_insert_trigger BEFORE INSERT ON project
FOR EACH ROW
BEGIN
  SET NEW.created_at = CURRENT_TIMESTAMP;
  IF NEW.deadline < NEW.created_at THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT='Deadline is sonner than creation date', MYSQL_ERRNO=3000;
  END IF;
  SET NEW.title = trim(initcap(NEW.title));
  IF NEW.title REGEXP '^ *$' THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Title is empty', MYSQL_ERRNO=3000;
  END IF;
END$$

-- -----------------------------------------------------
-- Trigger project_before_update_trigger:
-- Raise user exception when deadline is sooner than
-- creation date, or when title is empty
-- -----------------------------------------------------

DROP TRIGGER IF EXISTS project_before_update_trigger$$
CREATE TRIGGER project_before_update_trigger BEFORE UPDATE ON project
FOR EACH ROW
BEGIN
  IF NEW.deadline < NEW.created_at THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT='Deadline is sonner than creation date', MYSQL_ERRNO=3000;
  END IF;
  SET NEW.title = trim(initcap(NEW.title));
  IF NEW.title REGEXP '^ *$' THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Title is empty', MYSQL_ERRNO=3000;
  END IF;
END$$

-- -----------------------------------------------------
-- Trigger team_before_insert_trigger:
-- set the creation date at current datatime
-- -----------------------------------------------------
DROP TRIGGER IF EXISTS team_before_insert_trigger$$
CREATE TRIGGER team_before_insert_trigger BEFORE INSERT ON team
FOR EACH ROW
BEGIN
  SET NEW.created_at = CURRENT_TIMESTAMP;
END$$


-- -----------------------------------------------------
-- Trigger person_before_insert_trigger:
-- Raise user exception when first name or last name is 
-- empty
-- -----------------------------------------------------
DROP TRIGGER IF EXISTS person_before_insert_trigger$$
CREATE TRIGGER person_before_insert_trigger BEFORE INSERT ON person
FOR EACH ROW
BEGIN
  SET NEW.first_name = trim(initcap(NEW.first_name));
  SET NEW.last_name = trim(initcap(NEW.last_name));
  SET NEW.address = trim(initcap(NEW.address));
  SET NEW.town = trim(initcap(NEW.town));
  IF NEW.first_name REGEXP '^ *$' OR New.last_name REGEXP '^ *$' THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'First Name or Last Name is empty', MYSQL_ERRNO=3000;
  END IF;
  SET NEW.created_at = CURRENT_TIMESTAMP;
END$$

-- -----------------------------------------------------
-- Trigger person_before_update_trigger:
-- Raise user exception when first name or last name is 
-- empty
-- -----------------------------------------------------
DROP TRIGGER IF EXISTS person_before_update_trigger$$
CREATE TRIGGER person_before_update_trigger BEFORE UPDATE ON person
FOR EACH ROW
BEGIN
  SET NEW.first_name = trim(initcap(NEW.first_name));
  SET NEW.last_name = trim(initcap(NEW.last_name));
  SET NEW.address = trim(initcap(NEW.address));
  SET NEW.town = trim(initcap(NEW.town));
  IF NEW.first_name REGEXP '^ *$' OR New.last_name REGEXP '^ *$' THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'First Name or Last Name is empty', MYSQL_ERRNO=3000;
  END IF;
  SET NEW.created_at = CURRENT_TIMESTAMP;
END$$

-- -----------------------------------------------------
-- Trigger class_before_insert_trigger:
-- Raise user exception when class name is empty
-- -----------------------------------------------------
DROP TRIGGER IF EXISTS class_before_insert_trigger$$
CREATE TRIGGER class_before_insert_trigger BEFORE INSERT ON class
FOR EACH ROW
BEGIN
  SET NEW.name = trim(initcap(NEW.name));
  IF NEW.name REGEXP '^ *$' THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Class name is empty', MYSQL_ERRNO=3000;
  END IF;
END$$

-- -----------------------------------------------------
-- Trigger class_before_update_trigger:
-- Raise user exception when class name is empty
-- -----------------------------------------------------
DROP TRIGGER IF EXISTS class_before_update_trigger$$
CREATE TRIGGER class_before_insert_trigger BEFORE UPDATE ON class
FOR EACH ROW
BEGIN
  SET NEW.name = trim(initcap(NEW.name));
  IF NEW.name REGEXP '^ *$' THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Class name is empty', MYSQL_ERRNO=3000;
  END IF;
END$$

-- -----------------------------------------------------
-- Trigger document_before_update_trigger:
-- Set update time at current datetime
-- -----------------------------------------------------
DROP TRIGGER IF EXISTS document_before_update_trigger$$
CREATE TRIGGER document_before_update_trigger BEFORE UPDATE ON document
FOR EACH ROW
BEGIN
  SET NEW.updated_at = CURRENT_TIMESTAMP;
END$$


-- -----------------------------------------------------
-- Procedure training_center_reset:
-- This procedure is to reset some demo data inside 
-- the training_center schema
-- -----------------------------------------------------

DROP PROCEDURE IF EXISTS training_center_reset$$
CREATE PROCEDURE training_center_reset()
BEGIN
  SET FOREIGN_KEY_CHECKS = 0;
  TRUNCATE TABLE person;
  TRUNCATE TABLE class;
  TRUNCATE TABLE project;
  TRUNCATE TABLE team;
  TRUNCATE TABLE class_member;
  TRUNCATE TABLE document;
  TRUNCATE Table team_member;

  SET FOREIGN_KEY_CHECKS = 1;
  BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
      ROLLBACK;
      SELECT 'Insertion cancelled. The database is empty currently';
    END;
    START TRANSACTION;

    INSERT INTO person(`person_id`,`first_name`,`last_name`,`address`,`zip_code`,`town`,`email`,`mobile_phone`,`phone`,`is_trainer`,`is_admin`,`password`,`picture_location`,`created_at`,`confirmed_at`,`confirmation_token`,`renew_password_token`)
    VALUES
    (1,'peter','li','123 NY Street','75015','paris','hello@hotmail.com','0773138313', NULL, 1, 0, '1234', NULL, NULL, NULL, NULL, NULL),
    (2,'sebastian','g','address', '75000','paris','seb@gmail.com','0723421234', NULL, 0, 1, '1234', NULL, NULL, NULL, NULL, NULL),
    (3,'nicole','andrea','address','75000','paris','nicole@gmail.com','0723422413', NULL, 0, 0, '1234', NUll, NULL, NULL, NULL, NULL),
    (4,'adrien','ali','address','75000','paris','adrien@gmail.com','0132341364', NULL, 0, 0, '1234',NULL, NULL, NULL, NULL, NULL);


    INSERT INTO class(`class_id`,`name`)
    VALUES
    (1, 'advanced database'),
    (2, 'web development'),
    (3, 'java'),
    (4, 'advanced algorithm'),
    (5, 'advanced c');
    INSERT INTO class_member(`person_id`,`class_id`)
    VALUES
    (1, 1),(1, 2),(1, 4),(1, 5),
    (3, 1),(3, 3),(3, 4),(3, 5),
    (4, 1),(4, 2),(4, 3),(4, 4),(4, 5);
 
    INSERT INTO project(`project_id`,`owner_id`,`class_id`,`title`,`created_at`,`deadline`,`subject`)
    VALUES
    (1, 1, 2, 'training_center', NULL, '2017-03-01','this is a placeholder for subject'),
    (2, 1, 3, 'java web', NULL, '2017-03-01', 'this is a placeholder for subject');

    INSERT INTO team(`team_id`,`project_id`,`owner_id`,`created_at`,`summary`)
    VALUES
    (1, 1, 1, NULL, 'This is placeholder for summary'),
    (2, 1, 4, NULL, 'This is placeholder for summary'),
    (3, 2, 2, NULL, 'placeholder for summary'),
    (4, 2, 4, NULL, 'placeholder for summary');

    INSERT INTO team_member(`team_id`,`student_id`)
    VALUES
    (1, 1),(1, 2),(2, 4),(3, 2),(4, 3),(4, 4);

    INSERT INTO document(`document_id`,`author_id`,`team_id`,`location`,`created_at`,`updated_at`)
    VALUES
    (1,1,1,'location', CURRENT_TIMESTAMP, NULL),
    (2,2,3,'location', CURRENT_TIMESTAMP, NULL),
    (3,2,1,'location', CURRENT_TIMESTAMP, NULL),
    (4,4,2,'location', CURRENT_TIMESTAMP, NULL),
    (5,4,4,'location', CURRENT_TIMESTAMP, NULL);
    COMMIT;
  END;
END$$

CALL training_center_reset()$$


/* Create a user to be used in PHP for the connection,
 * and give him all grants on the DB.
 */
-- Delete the user ...
DELETE FROM mysql.user WHERE user='demo_user' $$
-- and his grants
DELETE FROM mysql.db WHERE user='demo_user' $$
DELETE FROM mysql.tables_priv WHERE user='demo_user' $$
FLUSH PRIVILEGES $$
-- Create him
CREATE USER demo_user@localhost IDENTIFIED by 'demo_password' $$
-- Grant him rights on the DB ...
GRANT ALL ON demo.* TO demo_user@localhost $$
-- and on the stored procedure
GRANT SELECT ON mysql.proc TO demo_user@localhost $$



