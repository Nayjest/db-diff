<?php
namespace Nayjest\DbDiff\Schema;

use Illuminate\Support\Facades\DB;

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
        return DB::table('information_schema.TABLES')
            ->select(DB::raw("CONCAT(TABLE_SCHEMA, '.', TABLE_NAME) as table_name"))
            ->whereRaw("TABLE_SCHEMA not in ('information_schema','performance_schema', 'mysql')")
            ->whereRaw("TABLE_NAME not like 'diff_%'")
            ->orderBy('TABLE_SCHEMA', 'ASC')
            ->orderBy('TABLE_NAME', 'ASC')
            ->lists('table_name');
    }

} 