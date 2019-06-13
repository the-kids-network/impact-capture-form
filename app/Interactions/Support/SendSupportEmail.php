<?php

namespace App\Interactions\Support;

use RuntimeException;
use App\Configuration\Spark;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Contracts\Interactions\Support\SendSupportEmail as Contract;

class SendSupportEmail implements Contract
{
    /**
     * Get a validator instance for the given data.
     *
     * @param  array  $data
     * @return \Illuminate\Validation\Validator
     */
    public function validator($user, array $data)
    {
        return Validator::make($data, [
            'from' => 'required',
            'subject' => 'required|max:2048',
            'message' => 'required',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function handle($user, array $data)
    {
        Mail::raw($data['message'], function ($m) use ($data) {
            $m->to(Spark::supportAddress())->subject('Support Request: '.$data['subject']);

            $m->replyTo($data['from']);
        });
    }
}
