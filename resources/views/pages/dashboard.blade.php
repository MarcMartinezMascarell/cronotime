@extends('layouts.app')

@section('content')

@include('layouts.headers.cards')


<script>
    var chartData = @json($chartData);
    var labels = @json($labels);
</script>

<div class="container-fluid mt--8">

<div class="row row-cols-2">
    <div class="col">
        <div class="card p-4">
            <div class="row mb-4">
                <div class="col">
                    <h3>Horas por meses</h3>
                </div>
                <div class="col-2">
                    <select name="filter" id="filter-select" class="form-control">
                        <option value="all">2021</option>
                        <option value="normal">2022</option>
                        <option value="ausencia">2023</option>
                    </select>
                </div>
            </div>
            <canvas id="myChart" width="50" height="20"></canvas>
        </div>
    </div>
    <div class="col">
        <div class="card p-4">
            <h3>Horas por meses</h3>
            <canvas id="myChart" width="50" height="20"></canvas>
        </div>
    </div>
</div>

</div>




@stop