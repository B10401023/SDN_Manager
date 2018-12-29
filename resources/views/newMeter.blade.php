<!-- views/pages/newMeter.blade.php -->

@extends('layouts.main')

@section('content')
	<html>
	<body>

	<h2>New Meter</h2>

	<form method="put">
	  Meter ID:<br>
	  <input type="text" name="meterId" value="">
	  <br>
	  Drop rate:<br>
	  <input type="text" name="dropRate" value="">
	  <br>
	  Meter name:<br>
	  <input type="text" name="meterName" value="">
	  <br><br>
	  <input type="submit" onclick="myfun()" value="submit">
	</form> 
	<script>
		myfun()
		{
			var username = 'admin';
		var password = 'admin';

		$.ajax
		  (
		  	beforeSend: function (xhr) {
    xhr.setRequestHeader ("Authorization", "Basic YWRtaW46YWRtaW4=");
},
		  {
		    type: "PUT",
		    url: "http://localhost:8181/restconf/config/opendaylight-inventory:nodes/node/openflow:2/meter/2",
		    dataType: 'json',
		    async: false,
		    data: '{"flow-node-inventory:meter": [{"meter-id": 2, "meter-name": "s3toh2", "flags": "meter-kbps meter-burst", "container-name": "abcd","meter-band-headers": {"meter-band-header": [{"band-id": 0,"meter-band-types": {"flags": "ofpmbt-drop"},"drop-rate": 4000,"drop-burst-size": 100}]}}]}',
		    success: function (){
		    alert('Thanks for your comment!');
		    }
		});
		}
		
	</script>

	</body>
	</html>
@endsection