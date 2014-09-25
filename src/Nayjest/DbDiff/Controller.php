<?php
namespace Nayjest\DbDiff;

use Config;
use Redirect;
use Illuminate\Routing\Controller as Base;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Nayjest\DbDiff\Schema\Schema;
use Nayjest\DbDiff\Schema\Table;
use Nayjest\Grids\Components\Header;
use Nayjest\Grids\EloquentDataProvider;
use Nayjest\Grids\FieldConfig;
use Nayjest\Grids\Grid;
use Nayjest\Grids\GridConfig;

class Controller extends Base
{

    public function getMake()
    {
        $table1 = Input::get('table_1');
        $table2 = Input::get('table_2');
        $fields = Input::get('fields');
        if ($fields) {
            $fields = explode(',', $fields);
        } else {
            $fields = '*';
        }


        $op = new Operation([
            'table1' => $table1,
            'table2' => $table2,
            'fields' => join(',',$fields)
        ]);
        $op->save();
        $diff = new Diff($table1, $table2, $fields);
        $diff->intoTempTable($op->id);
        return Redirect::action('Nayjest\DbDiff\Controller@getShow', [$op->id]);
    }

    public function getIndex()
    {
        $schema = new Schema;
        //dd($s->tables());
        return \View::make('db-diff::index', compact('schema'));
    }

    public function getShow($id)
    {
        $table_name = "diff_$id";

        Model::$current_table = $table_name;
        $model = new Model();

        $dp = new EloquentDataProvider($model->newQuery());
        $config = new GridConfig;
        $config->setDataProvider($dp);


        $db = Config::get('db-diff::db');
        $schema = new Table("$db.$table_name");

        foreach ($schema->columnNames() as $name) {
            $config->addColumn(
                (new FieldConfig)
                    ->setName($name)
                    ->setIsSortable(true)
            );
        }
        $config->setComponents([
            new Header()
        ]);

        $grid = new Grid($config);
        return \View::make('db-diff::show', compact('grid'));
    }
} 