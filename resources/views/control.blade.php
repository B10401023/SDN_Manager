<!-- views/pages/control.blade.php -->


@extends('layouts.main')

@section('content')
	<h1 style="text-align: center">
        - Menu -
    </h1>
    <center>
		@foreach ($nodes as $node)
	    	<a>{{ $node }}</a><br>
		@endforeach
		<!--<a href="{{ url('/node/'.$node.'/meter') }}">control meter</a>
    	<a href="{{ url('/node/'.$node.'/flow') }}">control flow</a>-->
    	<form>
			<input type="button" value="new meter" onclick="window.location.href='{{ url('/node/'.$currentNode.'/newmeter') }}'"/>
		</form>
		<form>
			<input type="button" value="edit meter" onclick="window.location.href='{{ url('/node/'.$currentNode.'/editmeter') }}'"/>
		</form>
		<form>
			<input type="button" value="delete meter" onclick="window.location.href='{{ url('/node/'.$currentNode.'/deletemetermenu') }}'"/>
		</form>
		<form>
			<input type="button" value="new flow" onclick="window.location.href='{{ url('/node/'.$currentNode.'/newflow') }}'"/>
		</form>
		<form>
			<input type="button" value="edit flow" onclick="window.location.href='{{ url('/node/'.$currentNode.'/editflow') }}'"/>
		</form>
		<form>
			<input type="button" value="delete flow" onclick="window.location.href='{{ url('/node/'.$currentNode.'/deleteflowmenu') }}'"/>
		</form>
	</center>
@endsection