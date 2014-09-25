<?php /** @var Nayjest\DbDiff\Schema\Schema $schema */ ?>
@extends('layouts.admin.layout')
@section('main')
<form method="get" action="<?= action('Nayjest\DbDiff\Controller@getMake') ?>">
    <div class="row">
        <div class="col-md-6">
            <label for="table_1">Table 1</label>
            <select
                class="form-control"
                name="table_1"
                id="table_1"
                size="50"
                >
                <?php foreach ($schema->tables() as $table): ?>
                    <option value="<?= $table ?>"><?= $table ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="table_2">Table 2</label>
            <select
                class="form-control"
                name="table_2"
                id="table_2"
                size="50"
                >
                <?php foreach ($schema->tables() as $table): ?>
                    <option value="<?= $table ?>"><?= $table ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>

    <label>Fields</label>
    <input type="text" name="fields" class="form-control">

    <div class="row">
        <label>Condition</label>
        <div class="col-md-4">
            <input class="form-control" name="condition_field">
        </div>
        <div class="col-md-4">
            <input class="form-control" name="condition_compare">
        </div>
        <div class="col-md-4">
            <input class="form-control" name="condition_value">
        </div>
    </div>

    <input type="submit" class="btn btn-lg btn-primary">
</form>
@stop