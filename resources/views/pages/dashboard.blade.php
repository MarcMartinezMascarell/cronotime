@extends('layouts.app')

@section('content')

@include('layouts.headers.cards')


<script>
    var chartData = @json($chartData);
    var labels = @json($labels);
</script>

<div class="container-fluid mt--8">

    <div class="card p-4">

    <div class="filters">

    </div>
        <canvas id="myChart" width="100" height="20"></canvas>
    </div>

</div>




@stop