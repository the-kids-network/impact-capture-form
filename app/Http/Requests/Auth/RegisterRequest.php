<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\Contracts\Http\Requests\Auth\RegisterRequest as Contract;
use App\Interactions\Auth\Register;


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
    protected function registerValidator()
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
        $validator = (new Register())->validator($this->user(), $this->all());

        return $validator;
    }

    /**
     * Get the validator for the request.
     *
     * @return \Illuminate\Validation\Validator
     */
    public function validator()
    {
        $validator = $this->registerValidator();

        return $validator;
    }

}
