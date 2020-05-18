<?php
namespace App\Domains\SessionReports\Controllers;

use function Aws\filter;

class SessionReportValidation {
    private static $rules = [
        'users' => [
            'validation' => [
                'mentee_id' => 'required|exists:mentees,id',
                'mentor_id' => 'required|exists:users,id'
            ],
            'messages' => [
            ]
        ],
        'session_report' => [
            'validation' => [
                'session_date' => 'required|date|date_format:d-m-Y|before_or_equal:today',
                'rating_id' => 'required|exists:session_ratings,id|numeric|min:2',
                'length_of_session' => 'required|numeric|min:0|max:24',
                'activity_type_id' => 'required|exists:activity_types,id',
                'location' => 'required|string|max:50',
                'safeguarding_concern' => 'required|numeric|min:0|max:2',
                'emotional_state_id' => 'required|exists:emotional_states,id',
                'meeting_details' => 'required|string|max:20000',
            ],
            'messages' => [
                'length_of_session' => 'The length of session should be in hours and at most 24 hours.',
                'session_date.before_or_equal' => 'The session date should be before or equal to today.',
                'rating_id.min' => 'The session rating field is required.',
                'safeguarding_concern' => 'Please pick a safeguarding option.'
            ]
        ],
        'planned_session' => [
            'validation' => [
                'next_session_date' => 'required|date|date_format:d-m-Y|after_or_equal:today',
                'next_session_location' => 'required|string|max:50',
            ],
            'messages' => [
                'next_session_date.after_or_equal' => 'The next session date should be in the future.',
            ]
        ],
        'leave' => [    
            'validation' => [
                'leave_type' => "required|in:mentor,mentee",
                'leave_start_date' => 'nullable|date|date_format:d-m-Y|before_or_equal:leave_end_date',
                'leave_end_date' => 'nullable|date|date_format:d-m-Y',
                'leave_description' => 'nullable|string|max:50'
            ],
            'messages' => [
                'leave_start_date.before_or_equal' => 'The leave start date should be before or equal to the end date.',
            ]
        ]
    ];

    public static function getRulesFor($ruleKeys) {

        $applicableRules = collect(self::$rules)
            ->filter(function ($value, $key) use ($ruleKeys) {
                return in_array($key, $ruleKeys);
            });

        $validationMerged = $applicableRules
            ->map(function($rule) {
                return $rule['validation'];
            })
            ->reduce(function ($merged, $validationItems) {
                return array_merge($merged, $validationItems);
            }, []);
        
        $messagesMerged = $applicableRules
            ->map(function($rule) {
                return $rule['messages'];
            })
            ->reduce(function ($merged, $messageItems) {
                return array_merge($merged, $messageItems);
            }, []);

        return [$validationMerged, $messagesMerged];
    }
}