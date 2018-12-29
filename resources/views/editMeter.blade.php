<!-- views/pages/meter.blade.php -->

@extends('layouts.main')

@section('content')
	<html>
	<body>

	<h2>New Meter</h2>

	<form action="" method="post">
	  Meter ID:<br>
	  <input type="text" name="meterId" value="">
	  <br>
	  Drop rate:<br>
	  <input type="text" name="dropRate" value="">
	  <br>
	  Meter name:<br>
	  <input type="text" name="meterName" value="">
	  <br><br>
	  <input type="submit" value="submit" onclick="newMeter();" />
	</form>

	</body>
	</html>
@endsection
