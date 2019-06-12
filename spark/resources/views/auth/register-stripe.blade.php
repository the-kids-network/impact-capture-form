@extends('spark::layouts.app')

@section('content')
<spark-register inline-template>
    <div>
        <div class="spark-screen container">
            <!-- Common Register Form Contents -->
            @include('spark::auth.register-common')
        </div>
    </div>
</spark-register>
@endsection
