<!-- views/pages/nodes.blade.php -->

@extends('layouts.main')

@section('content')
	<center>
	    @foreach ($allNodes as $node)
	    	<a href="{{ url('/node/'.$node['node-id']) }}">{{ $node['node-id'] }}</a>
		@endforeach
	</center>
@endsection
