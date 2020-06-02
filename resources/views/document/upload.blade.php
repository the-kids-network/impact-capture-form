@extends('layout.app')

@section('content')
<div class="container documents-upload">
    <div class="row">
        <div class="col-md-12">
            <nav class="nav page-nav">
                <a class="nav-link" href="{{ url('/documents/index') }}">Browse Documents</a>
            </nav>
        </div>
    </div>
    <div class="row">
        <div></div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Document Upload</div>
                <div class="card-body">
                    <document-upload> </document-upload>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
