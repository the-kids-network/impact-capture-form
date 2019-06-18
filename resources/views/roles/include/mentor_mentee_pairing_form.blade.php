<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Assign Mentor to Mentee
                </div>
                <div class="panel-body">

                    @include('shared.errors')

                    <form class="form-horizontal" role="form" method="POST" action="/roles/assign-mentor">
                    {{ csrf_field() }}

                        <!-- Mentor's Name -->
                        <div class="form-group">
                            <label class="col-md-4 control-label">Mentor Name</label>
                            <div class="col-md-6">
                                <select class="form-control" name="mentor_id">
                                    @foreach($mentors as $mentor)
                                        <option value="{{ $mentor->id }}">{{ $mentor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Mentee's Name -->
                        <div class="form-group">
                            <label class="col-md-4 control-label">Mentee Name</label>
                            <div class="col-md-6">
                                <select class="form-control" name="mentee_id" dusk="mentee-list">
                                    @foreach($mentees as $mentee)
                                        <option value="{{ $mentee->id }}">{{ $mentee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Assign Mentor to Mentee
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>