# tangomango_php
The PHP code from Tango Mango

Contributing to this repository means that you agree to
DCO-Developers_Certificate_Origin.txt


Local development

Start  MYSQL

`mysqld &`

Adding the 'virtual' event type:


ALTER TABLE `bayareat_tangomango1`.`Events` ADD COLUMN `virtual` tinyint(1) NOT NULL DEFAULT '0' COMMENT '' AFTER `other`;

ALTER TABLE `bayareat_tangomango1`.`Users` ADD COLUMN `virtual` tinyint(1) NOT NULL DEFAULT '0' COMMENT '' AFTER `other`;
