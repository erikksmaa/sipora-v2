<?php

namespace App\Http\Requests\Manager;

use App\Models\ProgramLogbook;
use App\Rules\BinaryUuidExists;
use Illuminate\Foundation\Http\FormRequest;

final class SaveProgramLogbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        $logbook = $this->route('logbook');
        $program = $this->route('program');
        $organization = $this->route('organization');

        return $program && $organization && $program->organization_id === $organization->getKey()
            && ($logbook ? $logbook->program_id === $program->getKey() && $this->user()?->can('update', $logbook) : $this->user()?->can('create', [ProgramLogbook::class, $program]));
    }

    public function rules(): array
    {
        return ['activity_id' => ['nullable', 'uuid', new BinaryUuidExists('activities')], 'log_date' => ['required', 'date'], 'summary' => ['required', 'string', 'max:10000'],
            'obstacles' => ['nullable', 'string', 'max:10000'], 'solutions' => ['nullable', 'string', 'max:10000'], 'progress_percent' => ['required', 'integer', 'between:0,100']];
    }
}
