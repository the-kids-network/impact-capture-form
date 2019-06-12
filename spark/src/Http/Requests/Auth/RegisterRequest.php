<?php

namespace Laravel\Spark\Http\Requests\Auth;

use Laravel\Spark\Spark;
use Laravel\Spark\Invitation;
use Illuminate\Foundation\Http\FormRequest;
use Laravel\Spark\Contracts\Interactions\Auth\CreateUser;
use Laravel\Spark\Contracts\Repositories\CouponRepository;
use Laravel\Spark\Contracts\Http\Requests\Auth\RegisterRequest as Contract;


class RegisterRequest extends FormRequest implements Contract
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validator for a registration request.
     *
     * @param  array  $paymentAttributes
     * @return \Illuminate\Validation\Validator
     */
    protected function registerValidator(array $paymentAttributes)
    {
        return $this->baseValidator();
    }

    /**
     * Get the base validator instance for a register request.
     *
     * @return \Illuminate\Validation\Validator
     */
    public function baseValidator()
    {
        $validator = Spark::interact(
            CreateUser::class.'@validator', [$this]
        );

        return $validator;
    }

    /**
     * Get the validator for the request.
     *
     * @return \Illuminate\Validation\Validator
     */
    public function validator()
    {
        $validator = $this->registerValidator(['stripe_token']);

        return $validator;
    }

}
