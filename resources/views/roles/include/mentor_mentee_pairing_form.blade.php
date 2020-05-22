<div class="card">
    <div class="card-header">
        Assign Mentor to Mentee
    </div>
    <div class="card-body">
        @include('shared.errors')

        <form class="form-horizontal" role="form" method="POST" action="/roles/assign-mentor">
            {{ csrf_field() }}

            <!-- Mentor's Name -->
            <div class="form-group row">
                <label class="col-md-4 col-form-label">Mentor Name</label>
                <div class="col-md-6">
                    <select class="form-control" name="mentor_id">
                        @foreach($mentors as $mentor)
                            <option value="{{ $mentor->id }}">{{ $mentor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- Mentee's Name -->
            <div class="form-group row">
                <label class="col-md-4 col-form-label">Mentee Name</label>
                <div class="col-md-6">
                    <select class="form-control mentee-select" name="mentee_id">
                        @foreach($mentees as $mentee)
                            <option value="{{ $mentee->id }}">{{ $mentee->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- Submit Button -->
            <div class="form-group row">
                <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Assign Mentor to Mentee
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
