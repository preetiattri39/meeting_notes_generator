<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeetingRequest extends FormRequest
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
            'media' => ['required', 'file', 'mimes:mp3,mp4,mpeg,mpga,m4a,wav,webm', 'max:25600'],
            'tags' => ['nullable', 'string', 'max:500'],
            'collaborator_emails' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
