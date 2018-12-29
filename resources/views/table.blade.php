<!-- views/pages/deleteFlow.blade.php -->


@extends('layouts.main')

@section('content')
	<h1 style="text-align: center">
        - Flow Menu -
    </h1>
	<center>
		@foreach ($tables as $table)
	    	<a>table ID : </a><a href="{{ url('/node/'.$node_id.'/deleteflowmenu/table/'.$table['id']) }}">{{ $table['id'] }}</a>
		@endforeach
	</center>
@endsection