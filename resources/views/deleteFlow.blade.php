<!-- views/pages/deleteFlow.blade.php -->


@extends('layouts.main')

@section('content')
	<h1 style="text-align: center">
        - Flow Table -
    </h1>
	<center>
		@foreach ($flows_array as $flow_array)
	    	<a href="{{ url('/node/'.$node_id.'/deleteflowmenu/table/'.$table_id.'/'.$flow_array['flow-name']) }}">{{ $flow_array['flow-name'] }}</a><br>
		@endforeach
	</center>
@endsection