<?php

namespace App\Domains\SessionReports\Services;

use App\Domains\SessionReports\Events\SessionReportDeleted;
use App\Domains\SessionReports\Emails\ReportSubmittedToManager;
use App\Domains\SessionReports\Emails\ReportSubmittedToMentor;
use App\Domains\SessionReports\Emails\SafeguardingConcernAlert;
use App\Domains\SessionReports\Models\Report;
use App\Domains\SessionReports\Models\SessionSearch;
use App\Exceptions\NotAuthorisedException;
use App\Exceptions\NotFoundException;
use App\Domains\UserManagement\Models\Mentee;
use App\Domains\UserManagement\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SessionReportService {

    public function getReportsUsing(SessionSearch $sessionSearch) {
        $query = Report::canSee();
        
        if ($sessionSearch->mentorId) {
            $query->whereMentorId($sessionSearch->mentorId);
        }

        if ($sessionSearch->menteeId) {
            $query->whereMenteeId($sessionSearch->menteeId);
        }

        // must null check as value can be validly zero
        if ($sessionSearch->safeguardingId != null) {
            $query->whereSafeguardingConcern($sessionSearch->safeguardingId);
        }

        if ($sessionSearch->sessionRatingId) {
            $query->whereRatingId($sessionSearch->sessionRatingId);
        }

        if ($sessionSearch->sessionDateRangeStart) {
            $query->where('session_date', '>=', Carbon::createFromFormat('d-m-Y', $sessionSearch->sessionDateRangeStart)->setTime(0,0,0));
        }

        if ($sessionSearch->sessionDateRangeEnd) {
            $query->where('session_date', '<=', Carbon::createFromFormat('d-m-Y', $sessionSearch->sessionDateRangeEnd)->setTime(0,0,0));
        }

        return $query->orderBy('created_at','desc')->get();
    }

    public function getReportsForMentor($mentorId) {
        $reports = Report::canSee()
            ->whereMentorId($mentorId)
            ->orderBy('created_at','desc')
            ->get();

        return $reports;
    }

    public function menteeHasReports($menteeId) {
        $count = Report::canSee()
            ->whereMenteeId($menteeId)
            ->count();

        return $count > 0;
    }

    public function getReports() {
        return Report::canSee()
            ->orderBy('created_at','desc')
            ->get();
    }

    public function getReport($id) {
        if (!Report::find($id)) throw new NotFoundException("Report with ID not found");
        $report = Report::canSee()->whereId($id)->first();
        if(!$report) throw new NotAuthorisedException("Current user cannot get report with ID");
        return $report;
    }

    public function createReport($keyValuePairs) {
        $mentee = Mentee::canSee()->whereId($keyValuePairs['mentee_id'])->first();
        if (!$mentee) throw new NotAuthorisedException('Current user cannot create report for mentee');

        // Create report
        $report = new Report();
        $report->mentor_id = $keyValuePairs['mentor_id'];
        $report->mentee_id = $keyValuePairs['mentee_id'];
        $report->session_date = Carbon::createFromFormat('d-m-Y', $keyValuePairs['session_date'])->setTime(0,0,0);
        $report->rating_id = $keyValuePairs['rating_id'];
        $report->length_of_session = $keyValuePairs['length_of_session'];
        $report->activity_type_id = $keyValuePairs['activity_type_id'];
        $report->location = $keyValuePairs['location'];
        $report->safeguarding_concern = $keyValuePairs['safeguarding_concern'];
        $report->emotional_state_id = $keyValuePairs['emotional_state_id'];
        $report->meeting_details = $keyValuePairs['meeting_details'];
        $report->save();

        // Send the Mentor an Email
        Mail::to($report->mentor)->send(new ReportSubmittedToMentor($report));

        // Send the Assigned Manager if any an Email
        if($report->mentor->manager){
            Mail::to($report->mentor->manager)->send(new ReportSubmittedToManager($report));
        }

        // Send email if safeguarding concern
        if($report->safeguarding_concern){
            $mail = ($report->mentor->manager) 
                ? Mail::to($report->mentor->manager)->cc(User::admin()->get())
                : Mail::to(User::admin()->get());

            $mail ->send(new SafeguardingConcernAlert($report));
        }

        return $report;
    }

    public function updateReport($id, $keyValuePairs) {
        $reportToUpdate = Report::canSee()->find($id);
        
        if (!$reportToUpdate) throw new NotFoundException("Session report not found");
        if (!User::canSee()->withTrashed()->find($keyValuePairs['mentor_id'])) throw new NotAuthorisedException("User not authorised to change mentor's session report");

        // update session report
        $reportToUpdate->mentor_id = $keyValuePairs['mentor_id'];
        $reportToUpdate->mentee_id = $keyValuePairs['mentee_id'];
        $reportToUpdate->session_date = Carbon::createFromFormat('d-m-Y',$keyValuePairs['session_date'])->setTime(0,0,0);
        $reportToUpdate->rating_id = $keyValuePairs['rating_id'];
        $reportToUpdate->length_of_session = $keyValuePairs['length_of_session'];
        $reportToUpdate->activity_type_id = $keyValuePairs['activity_type_id'];
        $reportToUpdate->location = $keyValuePairs['location'];
        $reportToUpdate->safeguarding_concern = $keyValuePairs['safeguarding_concern'];
        $reportToUpdate->emotional_state_id = $keyValuePairs['emotional_state_id'];
        $reportToUpdate->meeting_details = $keyValuePairs['meeting_details'];
        $reportToUpdate->save();

        return $reportToUpdate;
    }

    public function deleteReport($id) {
        $report = Report::canSee()->whereId($id)->first();
        if (!$report) throw new NotFoundException("Session report not found");

        return DB::transaction(function() use ($report) {
            $report->delete();
            event(new SessionReportDeleted($report->id));
        });

        return $report;
    }
}
