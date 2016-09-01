
DROP TABLE IF EXISTS `clndr_user`;

CREATE TABLE IF NOT EXISTS `clndr_user` (

  `id`            INT NOT NULL AUTO_INCREMENT,
  `username`      VARCHAR(128) NOT NULL,
  `name`          VARCHAR(45) NOT NULL,
  `surname`       VARCHAR(45) NOT NULL,
  `password`      VARCHAR(255) NOT NULL,
  `salt`          VARCHAR(255) NOT NULL,
  `access_token`  VARCHAR(255) NULL DEFAULT NULL,
  `create_date`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),

  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `access_token_UNIQUE` (`access_token` ASC))

ENGINE = InnoDB;



DROP TABLE IF EXISTS `clndr_access`;

CREATE TABLE IF NOT EXISTS `clndr_access` (

  `id`            INT NOT NULL AUTO_INCREMENT,
  `user_owner`    INT NOT NULL,
  `user_guest`    INT NOT NULL,
  `date`          DATE NOT NULL,

  PRIMARY KEY (`id`),

  INDEX `fk_clndr_access_1_idx` (`user_owner` ASC),
  INDEX `fk_clndr_access_2_idx` (`user_guest` ASC),

  CONSTRAINT `fk_clndr_access_1`
  FOREIGN KEY (`user_owner`)
  REFERENCES `clndr_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,

  CONSTRAINT `fk_clndr_access_2`
  FOREIGN KEY (`user_guest`)
  REFERENCES `clndr_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)

ENGINE = InnoDB;


DROP TABLE IF EXISTS `clndr_calendar`;

CREATE TABLE IF NOT EXISTS `clndr_calendar` (

  `id`            INT NOT NULL AUTO_INCREMENT,
  `text`          TEXT NOT NULL,
  `creator`       INT NOT NULL,
  `date_event`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),

  INDEX `fk_evrnt_note_1_idx` (`creator` ASC),

  CONSTRAINT `fk_evrnt_note_1`
  FOREIGN KEY (`creator`)
  REFERENCES `clndr_user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)

ENGINE = InnoDB;