<?php

namespace App\Http\Requests\Manager;

use App\Models\ActivityAttendance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class BulkRecordActivityAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $organization = $this->route('organization');
        $activity = $this->route('activity');
        $session = $this->route('session');

        return $organization && $activity && $session
            && $activity->organization_id === $organization->getKey()
            && $session->activity_id === $activity->getKey()
            && $this->user()?->can('view', $activity);
    }

    public function rules(): array
    {
        return [
            'attendance' => ['required', 'array'],
            'attendance.*' => ['required', 'array:status,notes'],
            'attendance.*.status' => ['required', Rule::in([
                ActivityAttendance::STATUS_PRESENT,
                ActivityAttendance::STATUS_ABSENT,
                ActivityAttendance::STATUS_EXCUSED,
            ])],
            'attendance.*.notes' => ['nullable', 'string', 'max:3000'],
        ];
    }
}
