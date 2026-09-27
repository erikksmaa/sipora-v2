<?php

namespace App\Http\Requests\Youth;

use Illuminate\Foundation\Http\FormRequest;

final class SubmitIdentityVerificationRequest extends FormRequest
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
            'document_type' => ['required', 'string', 'in:ktp,kia,student_card'],
            'document_number' => ['nullable', 'string', 'max:32'],
            'document_file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'document_type.required' => 'Pilih jenis dokumen identitas.',
            'document_type.in' => 'Jenis dokumen harus berupa KTP, KIA, atau Kartu Pelajar.',
            'document_file.required' => 'Unggah berkas dokumen identitas Anda.',
            'document_file.mimes' => 'Berkas harus dalam format JPG, PNG, atau PDF.',
            'document_file.max' => 'Ukuran berkas maksimal 4 MB.',
        ];
    }
}
