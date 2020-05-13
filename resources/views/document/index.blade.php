@extends('layout.app')

@section('content')
<div class="container">
    <div class="row">
        <div></div>
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
            
                <div class="panel-heading">Documents</div>

                <div class="panel-body documents home">
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
    jQuery(document).ready(function($) {
        $('[data-toggle="popover"]').popover({
            html: true,
            placement: 'auto top'
        });
        $('.table').on('post-body.bs.table', function () {
            $('[data-toggle="popover"]').popover()
        });
    });
</script>
@endsection
