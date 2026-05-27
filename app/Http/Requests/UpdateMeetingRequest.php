<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'language' => ['nullable', 'string', 'max:30'],
            'scheduled_for' => ['nullable', 'date'],
            'team_id' => ['nullable', 'exists:teams,id'],
            'tags' => ['nullable', 'string', 'max:500'],
            'collaborator_emails' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
