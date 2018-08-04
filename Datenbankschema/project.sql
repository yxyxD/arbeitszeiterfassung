CREATE TABLE IF NOT EXISTS `aze`.`PROJECT` (
  `PROJECT_ID` INT NOT NULL AUTO_INCREMENT,
  `USER_ID` INT NOT NULL,
  `PROJECT_NAME` VARCHAR(1000) NOT NULL,
  `DATE_START` DATE NULL,
  `DATE_END` DATE NULL,
  `DESIRED_DAYLY_WORKTIME` TIME NULL,
  `DESIRED_HOURLY_WAGE` DECIMAL(65,2) NULL,
  `INCOME` DECIMAL(65,2) NULL,
  PRIMARY KEY (`PROJECT_ID`),
  UNIQUE INDEX `PROJECT_ID_UNIQUE` (`PROJECT_ID` ASC),
  INDEX `fk_PROJECT_1_idx` (`USER_ID` ASC),
  CONSTRAINT `fk_PROJECT_1`
    FOREIGN KEY (`USER_ID`)
    REFERENCES `aze`.`USER` (`USER_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
