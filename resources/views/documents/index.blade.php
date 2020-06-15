@extends('layout.app')

@section('content')
<div class="container documents-management">
    @if(Auth::user()->isAdmin() || Auth::user()->isManager())
    <div class="row">
        <div class="col-md-12">
            <nav class="nav page-nav">
                <a class="nav-link" href="{{ url('/documents/upload/index') }}">Upload Documents</a>
            </nav>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <document-browse-index>
                <template v-slot:csrf>
                    {{ csrf_field() }}
                </template>
            </document-browse-index>
        </div>
    </div>
</div>
@endsection

@section('body-scripts')
<script>
    
</script>
@endsection
