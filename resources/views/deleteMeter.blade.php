<!-- views/pages/deleteMeter.blade.php -->


@extends('layouts.main')

@section('content')
	<center>
		@foreach ($nodeList as $node)
	    	<a href="{{ url('/node/'.$node_id.'/deletemetermenu/'.$node['meter-name']) }}">{{ $node['meter-name'] }}</a>
		@endforeach
	</center>
@endsection