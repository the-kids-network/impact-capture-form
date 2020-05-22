@extends('layout.app')

@section('content')
<div class="container documents-management">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Documents</div>
                <div class="card-body">
                    @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                    <div class="upload link">
                        <a href="{{ url('/documents/upload/index') }}">Upload Documents</a>
                    </div>
                    @endif

                    <documents
                        usertype="{{ (isset(Auth::user()->role)) ? Auth::user()->role : 'mentor'}}">
                        <template v-slot:csrf>
                            {{ csrf_field() }}
                        </template>
                    </documents>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('body-scripts')
<script>
    
</script>
@endsection
