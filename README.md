# PO_Back_V2_pre
PO_V2项目后端重构先行版

## 数据库修改

```sql
create table Users
(
    ID             int auto_increment
        primary key,
    Name           varchar(30) null,
    Password       varchar(30) null,
    Token          varchar(30) null,
    AddTime        datetime    null,
    LastUpdateTime datetime    null
);

create unique index login
    on Users (Name, Password);
```
