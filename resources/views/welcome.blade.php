<!-- views/pages/home.blade.php -->

@extends('layouts.main')

@section('content')
    <h1 style="text-align: center">
        - Homepage -
    </h1>
    <center>
        <form action="/submit" method="get" style="margin-top: 5%">
            <select name="methods">
                <option value="get">GET</option>
                <option value="post">POST</option>
                <option value="put">PUT</option>
                <option value="delete">DELETE</option>
            </select>
            <input type="text" name="url" size="100">
            <input type="submit" value="Send">
        </form>
    </center>
@endsection
