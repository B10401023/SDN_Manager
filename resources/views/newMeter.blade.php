<!-- views/pages/newMeter.blade.php -->

@extends('layouts.main')

@section('content')
	<html>
	<body>

	<h2>New Meter</h2>

	<form action="{{ url('/node/'.$id.'/newmeter/'.$count) }}" method="get">
	  Meter ID:<br>
	  <input type="text" name="meterId" value="{{ $count }}">
	  <br>
	  Drop rate:<br>
	  <input type="text" name="dropRate">
	  <br>
	  Meter name:<br>
	  <input type="text" name="meterName">
	  <br><br>
	  <input type="submit" value="Submit">
	</form>

	</body>
	</html>
@endsection