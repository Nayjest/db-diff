<?php
namespace Nayjest\DbDiff;

use Config;
use Illuminate\Support\Facades\View;
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
        $op = new Operation(compact('table1', 'table2', 'fields'));
        $op->save();
        $op->getDiffProcessor()->intoTempTable($op->id);
        return Redirect::action('Nayjest\DbDiff\Controller@getShow', [$op->id]);
    }

    public function getIndex()
    {
        $schema = new Schema;
        //dd($s->tables());
        return \View::make('db-diff::index', compact('schema'));
    }

    public function getList()
    {
        $models = Operation::paginate(50);
        return View::make('db-diff::list', compact('models'));
    }

    public function getShow($id)
    {
        /** @var Operation $operation $op */
        $operation = Operation::findOrFail($id);


        Model::$current_table = $operation->getDiffTableAttribute();
        $model = new Model();
        $query = $model->newQuery();
        $diff = $operation->getDiffProcessor();
        foreach ($diff->getPkColumns() as $name) {
            $query->addSelect($name);
        }
        foreach ($diff->getDiffColumns() as $name) {
            $query->addSelect("{$name}_1");
            $query->addSelect("{$name}_2");
            $query->addSelect(DB::raw("{$name}_1 - {$name}_2 as {$name}_diff"));
        }

        $dp = new EloquentDataProvider($query);
        $config = new GridConfig;
        $config->setDataProvider($dp);

        foreach ($diff->getPkColumns() as $name) {
            $config->addColumn(
                (new FieldConfig)
                    ->setName($name)
                    ->setIsSortable(true)
            );
        }
        foreach ($diff->getDiffColumns() as $name) {
            $config->addColumn(
                (new FieldConfig)
                    ->setName("{$name}_1")
                    ->setIsSortable(true)
            );
            $config->addColumn(
                (new FieldConfig)
                    ->setName("{$name}_2")
                    ->setIsSortable(true)
            );
            $config->addColumn(
                (new FieldConfig)
                    ->setName("{$name}_diff")
                    ->setIsSortable(true)
            );
        }
        $config->setComponents([
            new Header()
        ]);

        $grid = new Grid($config);
        return \View::make('db-diff::show', compact('grid', 'operation'));
    }
} 