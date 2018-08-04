CREATE TABLE IF NOT EXISTS `aze`.`WORK_SESSION` (
  `SESSION_ID` INT NOT NULL AUTO_INCREMENT,
  `PROJECT_ID` INT NOT NULL,
  `DATE` DATE NOT NULL,
  `TIME_FROM` TIME NOT NULL,
  `TIME_TO` TIME NOT NULL,
  `COMMENT` VARCHAR(4000) NULL,
  PRIMARY KEY (`SESSION_ID`),
  UNIQUE INDEX `SESSION_ID_UNIQUE` (`SESSION_ID` ASC),
  INDEX `fk_WORK_SESSION_1_idx` (`PROJECT_ID` ASC),
  CONSTRAINT `fk_WORK_SESSION_1`
    FOREIGN KEY (`PROJECT_ID`)
    REFERENCES `aze`.`PROJECT` (`PROJECT_ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);
