db-diff
=======

Database diff tool for Laravel & MySQL

### Overview

This package allows to compare data in tables with same structure & save results to database in format convenient for further processing.

### Actions

| Default URL           | Description                               |
|-----------------------|-------------------------------------------|
| /admin/diff/index     | Form for making diff                      |
| /admin/diff/show[id]  | View diff results                         |
| /admin/diff/list      | Archive of performad diff operations      | 

### Commands

#### db-diff:make

##### Supported options

| Name                 | Shortcut | Description                               |
|----------------------|----------|-------------------------------------------|
|table1                 | a       | Table to compare                          |
|table2                 | b       | Table to compare                          |
|fields                 | f       | Fields that must be compared (without pk) |

### Configuration options

See vendor/nayjest/db-diff/src/config/config.php

| key                  | type        |  Description                                                                    |
| db                   | string      | Database where diff results will be stored (your app's current db by default)   |
| ignored_db           | string[]    | Databases that you will not use for diff operations                             |
