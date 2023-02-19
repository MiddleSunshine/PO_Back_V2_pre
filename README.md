# PO_Back_V2_pre
PO_V2项目后端重构先行版

## 数据库修改

```sql
CREATE TABLE `Users` (
  `ID` int NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `Name` varchar(30) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `Token` varchar(30) DEFAULT NULL,
  `AddTime` datetime DEFAULT NULL COMMENT 'Create Time',
  `LastUpdateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
```
