<?php
namespace Nayjest\DbDiff;

class Model extends \Eloquent
{
    public static $current_table;

    public function __construct(array $attributes = array())
    {
        $this->table = static::$current_table;
        parent::__construct($attributes);
    }
} 