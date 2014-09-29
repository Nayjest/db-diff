@extends('layouts.admin.layout')
@section('main')
<table class="table table-striped table-condensed">
    <thead>
        <tr>
            <th>ID</th>
            <th>Table1</th>
            <th>Table2</th>
            <th>Fields</th>
            <th>Condition</th>
            <th>Options</th>
            <th>Created at</th>
            <th>Comment</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($models as $model): ?>
            <tr>
                <td>
                    <a href="/admin/diff/show/<?= $model->id ?>">
                    <?= $model->id ?>
                    </a>
                </td>
                <td><?= $model->table1 ?></td>
                <td><?= $model->table2 ?></td>
                <td><?= $model->fields_as_text ?></td>
                <td><?= $model->condition ?></td>
                <td><?= $model->options ?></td>
                <td><?= $model->created_at ?></td>
                <td><?= $model->comment ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
@stop