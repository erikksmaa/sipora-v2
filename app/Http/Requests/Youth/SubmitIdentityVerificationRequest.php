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
            'acknowledgement' => ['accepted'],
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
            'document_file.extensions' => 'Berkas harus dalam format JPG/JPEG, PNG, atau PDF.',
            'document_file.mimetypes' => 'Berkas harus dalam format JPG/JPEG, PNG, atau PDF.',
            'document_file.max' => 'Ukuran berkas maksimal 4 MB.',
            'acknowledgement.accepted' => 'Konfirmasikan bahwa bukti identitas sudah diperiksa.',
        ];
    }
}
