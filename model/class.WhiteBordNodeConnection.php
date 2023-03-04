<?php

class WhiteBordNodeConnection extends BaseUserModel {

    protected static function getTableName(): string
    {
        return 'WhiteBord_Node_Connection';
    }

    protected static function getTableTemplate(): string
    {
        return <<<EOD
(
  `W_ID` int NOT NULL,
  `N_ID` int NOT NULL,
  `Label` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`W_ID`,`N_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
EOD;
    }
}