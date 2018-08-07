<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Auth; //The auth facade

//Form requests are for abstracting away authorization and validaten away from our controllers. This way our controller code is not cluttered up.
class ChannelUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //We user auth middleware to authenticate users trying to edit or update their channel
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //We want to ignore having a unique slug when comparing to our own current slug, we exclude on the channel id

        //A class that cannot be resolved throug the service container, might just mean that there is a syntax error somewhere in the denpendency. OMFG!

        $channelId = Auth::user()->channel()->first()->id; //Need to get better at eloquent

        return [
            'name' => 'required|max:255|unique:channels,name,' . $channelId,
            'slug' => 'required|max:255|alpha_num|unique:channels,slug,' . $channelId,
            'description' => 'max:1000'
        ];
    }

    public function messages()
    {
        return [
            'slug.unique' => 'That unique URL has already been taken.', //Error message for a validation error
            'name.unique' => 'That channel name has already been taken.'
        ];
    }
}
