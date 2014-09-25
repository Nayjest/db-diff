<?php
namespace Nayjest\DbDiff\Schema;

use DB;
use Illuminate\Database\Query\Builder;

/**
 * Class Table
 *
 * @property string $full_name
 * @property string $pk
 * @property $column_names
 *
 * @package Nayjest\DbDiff
 */
class Table
{
    protected $db;
    protected $table;

    protected function parseTableName($table)
    {
        $parts = explode('.',$table);
        if (count($parts) === 2) {
            $this->db = $parts[0];
            $this->table = $parts[1];
        } else {
            $this->table = $parts[0];
        }
    }

    public function __construct($table)
    {
        $this->parseTableName($table);
    }

    public function pk()
    {
        return $this
            ->columnsQuery()
            ->where('COLUMN_KEY', 'PRI')
            ->lists('COLUMN_NAME');
    }

    protected function columnsQuery()
    {
        return DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', $this->db)
            ->where('TABLE_NAME', $this->table);
    }

    public function columnNames()
    {
        return $this->columnsQuery()->lists('COLUMN_NAME');
    }

    public function notPkColumnNames()
    {
        return array_diff($this->columnNames(), $this->pk());
    }



    public function fullName()
    {
        if ($this->db) {
            return "$this->db.$this->table";
        } else {
            return $this->table;
        }
    }

    public function __get($name)
    {
        $method = camel_case($name);
        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        } else {
            throw new \Exception("Trying to get undefined property");
        }

    }
} 