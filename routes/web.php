<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/submit', 'TopologyController@showTopology');

Route::get('/node/{node_id}', 'SwitchController@showFlow');

/*Route::get('/node/{node_id}/newmeter', function()
{
	return view('newMeter');
});*/

Route::get('/node/{node_id}/newmeter', 'MeterController@newMeter');

//Route::get('/node/{node_id}/newmeter/{count}', 'MeterController@newMeter');

Route::get('/node/{node_id}/editmeter', 'MeterController@editMeter');

Route::get('/node/{node_id}/deletemetermenu', 'MeterController@deleteMeterMenu');

Route::get('/node/{node_id}/deletemetermenu/{meter_name}', 'MeterController@deleteMeter');

Route::get('/node/{node_id}/newflow', 'FlowController@newFlow');

Route::get('/node/{node_id}/newflow/choosemeter', 'FlowController@chooseMeter');

Route::get('/node/{node_id}/deleteflowmenu', 'FlowController@deleteFlowMenu');

Route::get('/node/{node_id}/deleteflowmenu/table/{table_id}', 'FlowController@deleteFlowTable');

Route::get('/node/{node_id}/deleteflowmenu/table/{table_id}/{flow_name}', 'FlowController@deleteFlow');

