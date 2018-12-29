<!-- views/pages/newFlow.blade.php -->

@extends('layouts.main')

@section('content')
    <h1 style="text-align: center">
        - Homepage -
    </h1>
    <center>
        <form onsubmit="myfun()" style="margin-top: 5%">
            <select name="meter">
            	@foreach ($meters as $meter)
                <option value="{{ $meter['meter-name'] }}">{{ $meter['meter-name'] }}</option>
                @endforeach
            </select>
            <input type="submit" value="Submit">
        </form>
    </center>

    <script type="text/javascript">
    	function myfun() 
    	{
		  alert("Submitted!!");
		}
    </script>
@endsection