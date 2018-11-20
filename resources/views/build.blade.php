@extends('layouts.app')

@section('content')
    <div class="container" style="margin-bottom: 5%">
        <!--<div class="row mt-5">-->
        <div class="col-md-10 offset-md-1">
            <build></build>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $('#build-navi').addClass('active');
    </script>
@endsection