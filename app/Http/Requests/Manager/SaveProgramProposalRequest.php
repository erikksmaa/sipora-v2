<?php

namespace App\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

final class SaveProgramProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        $proposal = $this->route('proposal');
        $program = $this->route('program');
        $organization = $this->route('organization');

        return $program && $organization && $program->organization_id === $organization->getKey()
            && ($proposal ? $proposal->program_id === $program->getKey() && $this->user()?->can('update', $proposal) : $this->user()?->can('update', $program));
    }

    public function rules(): array
    {
        return [
            'requested_budget' => ['nullable', 'numeric', 'min:0', 'max:9999999999999999.99'],
            'proposal_document' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ];
    }
}
