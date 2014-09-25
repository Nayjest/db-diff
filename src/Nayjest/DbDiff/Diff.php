<?php
namespace Nayjest\DbDiff;

use Illuminate\Support\Facades\DB;
use Nayjest\DbDiff\Schema\Table;

class Diff
{
    /**
     * @var Schema
     */
    protected $table1;

    /**
     * @var Schema
     */
    protected $table2;

//    protected $columns;


    protected $diff_columns;

    protected $load_only_diff = true;

    protected $use_temp_table = true;

    protected $create_diff_column = false;

    public function setDiffColumns($columns = '*')
    {
        if ($columns === '*') {
            $this->diff_columns = $this->table1->notPkColumnNames();
        } else {
            $this->diff_columns = $columns;
        }
    }

    protected $condition = [];


    /**
     * @param $table1
     * @param $table2
     * @param string|array $diff_columns
     */
    public function __construct($table1, $table2, $diff_columns = '*')
    {
        $this->table1 = new Table($table1);
        $this->table2 = new Table($table2);
        $this->setDiffColumns($diff_columns);
    }

    protected function getConditionSql($table, $before = 'and')
    {
        $parts = [];
        foreach ($this->condition as $cond) {
            $parts[] = "{$table}.{$cond[0]} $cond[1] $cond[2]";
        }
        if (!empty($parts)) {
            return $before . ' ' .  join(' and ', $parts);
        } else {
            return '';
        }

    }

    protected function getJoinCondition()
    {
        $on  = [];
        foreach($this->table1->pk() as $column) {
            $on[] = "{$this->table1->full_name}.{$column} = {$this->table2->full_name}.{$column}";
        }
        return join(' and ', $on);
    }

    protected function getColumnsSql($columns, $priority = null)
    {
        if (!$priority) $priority = $this->table1->full_name;
        $pk = $this->table1->pk();
        $names = [];
        foreach($columns as $column) {
            if (in_array($column, $pk)) {
                $names[] = "\n\t{$priority}.{$column}";
            } else {
                $names[] = "\n\t{$this->table1->full_name}.{$column} as {$column}_1";
                $names[] = "\n\t{$this->table2->full_name}.{$column} as {$column}_2";
                if ($this->create_diff_column)
                {
                    $names[] = "\n\t({$this->table2->full_name}.{$column} - {$this->table1->full_name}.{$column}) as {$column}_diff";
                }

            }
        }
        return join(', ', $names);
    }


    public function make()
    {
        return $this->getDiffSql();
    }

    public function getDiffSql()
    {
        $selected_columns = array_merge($this->table1->pk(), $this->diff_columns);
        $columns12 = $this->getColumnsSql($selected_columns, $this->table1->fullName());
        $columns21 = $this->getColumnsSql($selected_columns, $this->table2->fullName());
        $cond1 = $this->getConditionSql($this->table1->full_name);
        $cond2 = $this->getConditionSql($this->table2->full_name);
        $on_condition = $this->getJoinCondition();
        $pk = $this->table1->pk();
        $pk_first = array_pop($pk);
        $sql = "(
            SELECT $columns12 FROM
              {$this->table1->full_name}
            LEFT JOIN
              {$this->table2->full_name}
            ON $on_condition
            WHERE {$this->table2->full_name}.{$pk_first} IS NULL $cond1
        )
        UNION
        (
            SELECT $columns21 FROM
              {$this->table2->full_name}
            LEFT JOIN
              {$this->table1->full_name}
            ON $on_condition
            WHERE {$this->table1->full_name}.{$pk_first} IS NULL $cond2
        )
        UNION
        (
            SELECT $columns12 FROM
              {$this->table1->full_name}
            LEFT JOIN
              {$this->table2->full_name}
            ON $on_condition
            WHERE {$this->table2->full_name}.{$pk_first} IS NOT NULL $cond1
        )";

        return $sql;
    }

    public function getIntoTempTableSql($name)
    {
        $diff_sql = $this->getDiffSql();
        $sql = "CREATE TABLE diff_{$name} AS SELECT * FROM ($diff_sql) Q";
        return $sql;
    }

    public function intoTempTable($name)
    {
        DB::statement($this->getIntoTempTableSql($name));
    }

    /**
     * @param boolean $use_temp_table
     */
    public function setUseTempTable($use_temp_table)
    {
        $this->use_temp_table = $use_temp_table;
    }

    /**
     * @param boolean $load_only_diff
     */
    public function setLoadOnlyDiff($load_only_diff)
    {
        $this->load_only_diff = $load_only_diff;
    }

    /**
     * @param boolean $create_diff_column
     */
    public function setCreateDiffColumn($create_diff_column)
    {
        $this->create_diff_column = $create_diff_column;
    }

    /**
     * @param mixed $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }
} 