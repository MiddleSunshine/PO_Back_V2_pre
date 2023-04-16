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

```sql
create table Settings
(
    ID    int auto_increment
        primary key,
    Name  varchar(100)  null,
    Value varchar(1000) null
);
```

# API

## WhiteBord

- storeWhiteBord

```json
{
  "settings": [ ],
  "data": {
    "nodes": [
      {
        "id": "PointNodeCreator_1679562065770",
        "type": "PointNodeCreator",
        "data": [ ],
        "node_data": {},
        "position": {
          "x": 221.33333333333334,
          "y": 163.33333333333334
        },
        "width": 205,
        "height": 21
      },
      {
        "id": "output_1679562067329",
        "type": "output",
        "data": [ ],
        "node_data": {},
        "position": {
          "x": 394,
          "y": 304.6666666666667
        },
        "width": 150,
        "height": 22
      }
    ],
    "edges": [ ]
  }
}
```

- nodes.data 保存node的数据库数据
- nodes.node_data 保存node用以前端操作各种数据的
- nodes.setting 保存这个node的一些配置项目
