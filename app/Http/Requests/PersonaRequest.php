<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Backpack\PermissionManager\app\Http\Requests\UserStoreCrudRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class PersonaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dni' => ['required',Rule::unique('personas')->ignore($this->id)],
            'user_id' => ['required',Rule::unique('personas')->ignore($this->id)],

            // 'name' => 'required|min:5|max:255'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}