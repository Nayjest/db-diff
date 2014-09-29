<?php
namespace Nayjest\DbDiff;

use Config;

class Operation extends \Eloquent
{
    protected $guarded = [];

    protected $table = 'diff_operations';

    public function setFieldsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['fields'] = join(',', $value);
        } else {
            $this->attributes['fields'] = $value;
        }
    }

    public function getFieldsAttribute()
    {
        if (empty($this->attributes['fields']) or $this->attributes['fields'] === '*') {
            return '*';
        } else {
            return explode(',', $this->attributes['fields']);
        }
    }

    public function getFieldsAsTextAttribute()
    {
        $fields = $this->getFieldsAttribute();
        return is_array($fields)?join(',', $fields):$fields;
    }

    public function getDiffProcessor()
    {
        return new Diff($this->table1, $this->table2, $this->fields);
    }

    public function getDiffTableAttribute()
    {
        return Config::get('db-diff::db') . ".diff_{$this->id}";
    }



} 