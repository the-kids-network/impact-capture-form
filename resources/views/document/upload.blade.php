@extends('layout.app')

@section('content')
<div class="container">
        <div class="row">
            <div></div>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Document Upload</div>
                    <div class="panel-body">
                        <document-upload> </document-upload>
                        <br />
                        <a href="{{ url('/documents/index') }}">Browse Documents</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
