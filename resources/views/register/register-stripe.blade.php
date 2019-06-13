@extends('layout.app')

@section('content')
<register inline-template>
    <div>
        <div class="spark-screen container">
            <!-- Common Register Form Contents -->
            @include('register.register-common')
        </div>
    </div>
</register>
@endsection
