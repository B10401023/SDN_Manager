<!-- views/pages/flowTable.blade.php -->


@extends('layouts.main')

@section('content')
	<h1 style="text-align: center">
        - Flows -
    </h1>
    <center>
		@foreach ($nodes as $node)
    	<a href="{{ url('/node/'.$currentNode.'/newflow/choosemeter') }}">{{ $node }}</a><br>
		@endforeach
	</center>

@endsection