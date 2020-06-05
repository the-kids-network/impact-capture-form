@extends('layout.app')

@section('content')
    <div class="container session-report list">
        <div class="row">
            <div class="col-md-12">
                <session-reports
                    :mentors="{{ json_encode($mentors) }}"></session-reports>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <style>
        .clickable-row{
            cursor: pointer;
        }
    </style>
@endsection

@section('body-scripts')
    <script>
        jQuery(document).ready(function($) {
            $(".table").on("click", ".clickable-row", function() {
                window.location = $(this).data("href");
            });
        });
    </script>
@endsection
