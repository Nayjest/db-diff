<?php
namespace Nayjest\DbDiff\Schema;

use Config;
use DB;

class Schema
{
    public function tables()
    {
//        $sql = "
//        SELECT
//            CONCAT(TABLE_SCHEMA, '.', TABLE_NAME)
//        FROM
//	        information_schema.`TABLES`
//        WHERE
//	        TABLE_SCHEMA not in ('information_schema') and
//	        TABLE_NAME not like 'diff_%'
//	    ";
        $ignored_db = Config::get('db-diff::ignored_db', []);
        return DB::table('information_schema.TABLES')
            ->select(DB::raw("CONCAT(TABLE_SCHEMA, '.', TABLE_NAME) as table_name"))
            ->whereNotIn('TABLE_SCHEMA', $ignored_db)
            ->whereRaw("TABLE_NAME not like 'diff_%'")
            ->orderBy('TABLE_SCHEMA', 'ASC')
            ->orderBy('TABLE_NAME', 'ASC')
            ->lists('table_name');
    }

} 