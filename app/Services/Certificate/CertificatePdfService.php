<?php

namespace App\Services\Certificate;

use App\Models\UserCertificate;
use Illuminate\Support\Str;

final class CertificatePdfService
{
    public function render(UserCertificate $certificate): string
    {
        $certificate->loadMissing(['user.profile', 'participation.activity.organization']);
        $name = $certificate->user->profile?->full_name ?: $certificate->user->name;
        $activity = $certificate->participation?->activity;
        $verificationUrl = route('certificates.verify', $certificate->verification_code);

        $content = implode("\n", [
            '0.953 0.961 0.988 rg 0 0 842 595 re f',
            '0.141 0.200 0.471 rg 24 24 794 547 re S',
            '0.141 0.200 0.471 rg BT /F2 18 Tf 56 520 Td (SIPORA v2 - DINDIKPORA KABUPATEN PEMALANG) Tj ET',
            '0.980 0.365 0.086 rg BT /F2 13 Tf 56 486 Td (SERTIFIKAT PARTISIPASI ACTIVITY) Tj ET',
            '0.10 0.12 0.22 rg BT /F1 12 Tf 56 440 Td (Diberikan kepada:) Tj ET',
            '0.141 0.200 0.471 rg BT /F2 28 Tf 56 398 Td ('.$this->text($name, 46).') Tj ET',
            '0.10 0.12 0.22 rg BT /F1 12 Tf 56 354 Td (atas penyelesaian terverifikasi pada Activity:) Tj ET',
            '0.141 0.200 0.471 rg BT /F2 21 Tf 56 316 Td ('.$this->text($certificate->name, 62).') Tj ET',
            '0.10 0.12 0.22 rg BT /F1 11 Tf 56 278 Td (Penyelenggara: '.$this->text($certificate->issuer_name, 82).') Tj ET',
            'BT /F1 10 Tf 56 226 Td (Tanggal terbit: '.$certificate->issued_at->format('d-m-Y').') Tj ET',
            'BT /F1 10 Tf 56 204 Td (Nomor: '.$this->text($certificate->certificate_number).') Tj ET',
            'BT /F1 9 Tf 56 166 Td (Kode verifikasi: '.$this->text($certificate->verification_code).') Tj ET',
            'BT /F1 8 Tf 56 142 Td (Verifikasi: '.$this->text($verificationUrl, 112).') Tj ET',
            '0.980 0.365 0.086 rg 56 104 210 5 re f',
            '0.141 0.200 0.471 rg BT /F2 10 Tf 56 76 Td (Data terverifikasi melalui sistem SIPORA.) Tj ET',
            $activity ? 'BT /F1 7 Tf 56 52 Td (Activity ID: '.$this->text($activity->uuid()).') Tj ET' : '',
        ]);

        return $this->document($content);
    }

    private function document(string $content): string
    {
        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 842 595] /Resources << /Font << /F1 4 0 R /F2 5 0 R >> >> /Contents 6 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>',
            '<< /Length '.strlen($content)." >>\nstream\n{$content}\nendstream",
        ];
        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $number => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($number + 1)." 0 obj\n{$object}\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= sprintf('%010d 00000 n ', $offset)."\n";
        }

        return $pdf."trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";
    }

    private function text(?string $value, ?int $limit = null): string
    {
        $text = Str::ascii((string) $value);
        if ($limit !== null) {
            $text = Str::limit($text, $limit, '...');
        }

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
    }
}
