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

| Key                  | Type        |  Description                                                                    |
|----------------------|-------------|---------------------------------------------------------------------------------|
| db                   | string      | Database for storing diff results (your app's current db by default)   |
| ignored_db           | string[]    | Databases that you will never use for diff operations, usually some system db's   |


### License

© 2014 &mdash; 2015 Vitalii Stepanenko

Licensed under the MIT License.
