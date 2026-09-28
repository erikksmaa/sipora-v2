<?php

namespace App\Http\Requests\Manager;

use App\Models\ActivityAttendance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class RecordActivityAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $activity = $this->route('activity');
        $organization = $this->route('organization');

        return $activity && $organization && $activity->organization_id === $organization->getKey()
            && $this->user()?->can('view', $activity);
    }

    public function rules(): array
    {
        return [
            'attendance_status' => ['required', Rule::in([
                ActivityAttendance::STATUS_PRESENT,
                ActivityAttendance::STATUS_ABSENT,
                ActivityAttendance::STATUS_EXCUSED,
            ])],
            'checked_in_at' => ['nullable', 'date'],
            'checked_out_at' => ['nullable', 'date', 'after_or_equal:checked_in_at'],
            'notes' => ['nullable', 'string', 'max:3000'],
        ];
    }
}
