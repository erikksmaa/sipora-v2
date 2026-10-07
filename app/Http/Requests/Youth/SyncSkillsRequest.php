<?php

namespace App\Http\Requests\Youth;

use App\Rules\BinaryUuidExists;
use Illuminate\Foundation\Http\FormRequest;

final class SyncSkillsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'skills' => ['nullable', 'array'],
            'skills.*' => ['string', new BinaryUuidExists('skills')],
            'other_selected' => ['sometimes', 'boolean'],
            'custom_skill' => ['required_if:other_selected,1', 'nullable', 'string', 'min:2', 'max:120'],
        ];
    }
}
