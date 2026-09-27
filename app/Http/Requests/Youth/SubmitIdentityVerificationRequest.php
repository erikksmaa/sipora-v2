<?php

namespace App\Http\Requests\Youth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'document_number' => [
                'required', 'string', 'max:32',
                Rule::when(in_array($this->input('document_type'), ['ktp', 'kia'], true), ['regex:/^[0-9]{16}$/']),
            ],
            'document_file' => [
                'required', 'file', 'extensions:jpg,jpeg,png,pdf',
                'mimetypes:image/jpeg,image/png,application/pdf', 'max:4096',
            ],
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
            'document_number.required' => 'Nomor dokumen wajib diisi.',
            'document_number.regex' => 'Nomor KTP/KIA harus terdiri dari 16 digit.',
        ];
    }
}
