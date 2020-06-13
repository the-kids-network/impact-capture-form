@extends('layout.app')

@section('scripts')
@endsection

@section('content')
<settings inline-template>
    <div class="container settings">
        <div class="row">
            <!-- Tabs -->
            <div class="col-md-4">
                <div class="card card-flush menu">
                    <div class="card-header">
                        Settings
                    </div>

                    <div class="card-body">
                        <div class="settings-tabs">
                            <ul class="nav flex-column menu-list" role="tablist">
                                <!-- Profile Link -->
                                <li role="presentation" class="menu-item">
                                    <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
                                        <span class="fas fa-edit"></span> Profile
                                    </a>
                                </li>

                                <!-- Security Link -->
                                <li role="presentation" class="menu-item">
                                    <a href="#security" aria-controls="security" role="tab" data-toggle="tab">
                                        <span class="fas fa-key"></span> Security
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Panels -->
            <div class="col-md-8">
                <div class="tab-content">
                    <!-- Profile -->
                    <div role="tabpanel" class="tab-pane active" id="profile">
                        @include('settings.profile')
                    </div>

                    <!-- Security -->
                    <div role="tabpanel" class="tab-pane" id="security">
                        @include('settings.security')
                    </div>
                </div>
            </div>
        </div>
    </div>
</settings>
@endsection
