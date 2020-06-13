@extends('layout.app')

@section('scripts')
@endsection

@section('content')
    <div class="container">  
        <div class="row">
            <div class="col-md-12">
                <div>
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
@endsection