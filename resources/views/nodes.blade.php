<!-- views/pages/nodes.blade.php -->

@extends('layouts.main')

@section('content')
	<h1 style="text-align: center">
        - Switch Table -
    </h1>
	<center>
	    @foreach ($allNodes as $node)
	    	<a href="{{ url('/node/'.$node['node-id']) }}">{{ $node['node-id'] }}</a>
		@endforeach
	</center>
@endsection
