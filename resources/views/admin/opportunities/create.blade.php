@extends('layouts.admin')
@section('title','Tambah Opportunity · Admin SIPORA')
@section('content')<div class="mx-auto max-w-5xl space-y-6"><x-ui.page-header eyebrow="Konten publik" title="Tambah Opportunity" description="Buat informasi peluang eksternal sebagai draft sebelum dipublikasikan." /><form method="POST" action="{{ route('admin.opportunities.store') }}">@csrf @include('admin.opportunities._form')</form></div>@endsection
