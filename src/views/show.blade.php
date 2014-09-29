@extends('layouts.admin.layout')
@section('main')
<div class="lead">
    <?= $operation->comment ?>
</div>
{{$grid}}
@stop