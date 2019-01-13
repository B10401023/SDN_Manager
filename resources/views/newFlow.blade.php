<!-- views/pages/newFlow.blade.php -->

@extends('layouts.main')

@section('content')
    <h1 style="text-align: center">
        - New Flow -
    </h1>
    <center>
        <form action="{{ url('/node/'.$id.'/newflow/submit') }}" style="margin-top: 5%" method="get">
            Flow ID:<br>
            <input type="text" name="flowId" value="{{ $count }}">
            <br>
            Flow name:<br>
            <input type="text" name="flowName">
            <br>
            Meter:
            <select name="meter">
                @foreach ($meters as $meter)
                <option value="{{ $meter['meter-id'] }}">{{ $meter['meter-id'] }}</option>
                @endforeach
            </select><br>
            From:
            <select name="from">
                @foreach ($arr as $port)
                <option value="{{ $port }}">{{ $port }}</option>
                @endforeach
            </select>
            To:
            <select name="to">
                @foreach ($arr as $port)
                <option value="{{ $port }}">{{ $port }}</option>
                @endforeach
            </select><br>
            <br><br>
            <input type="submit" value="Submit">
        </form>
    </center>
@endsection