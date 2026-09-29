<?php

namespace App\Http\Requests\Verifier;

use App\Models\ProgramProposal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class ReviewProgramProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        $proposal = $this->route('proposal');

        return $proposal && $this->user()?->can('review', $proposal);
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', Rule::in([ProgramProposal::STATUS_APPROVED, ProgramProposal::STATUS_REVISION, ProgramProposal::STATUS_REJECTED])],
            'notes' => ['nullable', 'required_if:decision,'.ProgramProposal::STATUS_REVISION.','.ProgramProposal::STATUS_REJECTED, 'string', 'max:5000'],
        ];
    }
}
