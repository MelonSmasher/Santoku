@extends('layouts.session')

@section('content')
    <input disabled="disabled" readonly="readonly" style="display: none;" type="text" id="session-token"
           name="session-token" value="{{$token}}"/>
    <input disabled="disabled" readonly="readonly" style="display: none;" type="text" id="ws"
           name="ws" value="{{$ws}}"/>
    <session></session>
@endsection
@section('scripts')
@endsection