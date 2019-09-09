@extends('layout.app')

@section('content')
<div class="container">
        <div class="row">
            <div></div>
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                
                    <div class="panel-heading">Documents</div>

                    <div class="panel-body">
                        @if(Auth::user()->isAdmin() || Auth::user()->isManager())
                        <a href="{{ url('/document/upload') }}">Upload Documents</a>
                        @endif

                        @include('shared.errors')

                        <table class="table documents" 
                                data-toggle="table" 
                                data-search="true" 
                                data-pagination="true">
                            <thead>
                                <tr>
                                    <th data-sortable="true">Type</th>
                                    <th data-sortable="true">Title</th>
                                    <th data-sortable="false">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($documents as $document)
                                <tr>
                                    <td class="preview">
                                        <span class="hidden">{{ $document->extension() }}</span>
                                        <span class="file-icon far fa-2x {{ App\Utils\FileIcons::getIcon($document->extension()) }}"
                                            data-toggle="popover" data-trigger="hover" data-placement="top" data-content="File type: {{ $document->extension() }}">
                                        </span>
                                    </td>
                                    <td class="title">
                                        {{$document->title}}
                                    </td>
                                    <td class="actions">
                                        <a class="item" href="{{ url('/document/'.$document->id) }}"
                                            data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{!! 'Download' !!}">
                                            <span class="glyphicon glyphicon-download"></span>
                                        </a>

                                        @if(Auth::user()->isManager() || Auth::user()->isAdmin())
                                            @if($document->trashed())
                                            <form class="item" id="restore-{{$document->id}}" action="{{ url('/document/'.$document->id.'/restore') }}" method="post">
                                                {{ csrf_field() }}
                                                <a href="javascript:void(0);" onclick="document.getElementById('restore-{{$document->id}}').submit()"
                                                    data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{!! 'Restore' !!}">
                                                    <span class="glyphicon glyphicon-backward"></span>
                                                </a>
                                            </form>
                                            <form class="item" id="delete-{{$document->id}}" action="{{ url('/document/'.$document->id) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <input type="hidden" name="really_delete" value="1">
                                                <a href="javascript:void(0);" onclick="document.getElementById('delete-{{$document->id}}').submit()" 
                                                    data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{!! 'Permenantly Delete' !!}">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </a>
                                            </form>
                                            @else
                                            <form class="item" id="trash-{{$document->id}}" action="{{ url('/document/'.$document->id) }}" method="post">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                                <input type="hidden" name="really_delete" value="0">
                                                <a href="javascript:void(0);" onclick="document.getElementById('trash-{{$document->id}}').submit()"
                                                    data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{!! 'Trash' !!}">
                                                    <span class="glyphicon glyphicon-trash"></span>
                                                </a>
                                            </form>
                                            @endif
                                            @if(!$document->is_shared)
                                            <form class="item"  id="share-{{$document->id}}" action="{{ url('/document/'.$document->id.'/share') }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="share" value="true">
                                                <a href="javascript:void(0);" onclick="document.getElementById('share-{{$document->id}}').submit()"
                                                    data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{!! 'Share' !!}">
                                                    <span class="glyphicon glyphicon-share-alt"></span>
                                                </a>
                                            </form>
                                            @else
                                            <form class="item"  id="unshare-{{$document->id}}" action="{{ url('/document/'.$document->id.'/share') }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="share" value="false">
                                                <a href="javascript:void(0);" onclick="document.getElementById('unshare-{{$document->id}}').submit()"
                                                    data-toggle="popover" data-trigger="hover" data-placement="top" data-content="{!! 'Unshare' !!}">
                                                    <span class="glyphicon glyphicon-share-alt icon-flipped"></span>
                                                </a>
                                            </form>
                                            @endif
                                        @endif
                                    </td>
                                @endforeach
                            </tbody>
                        </table>
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
