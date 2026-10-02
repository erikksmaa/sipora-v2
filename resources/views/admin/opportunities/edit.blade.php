@extends('layouts.admin')
@section('title','Edit Opportunity · Admin SIPORA')
@section('content')<div class="mx-auto max-w-5xl space-y-6"><x-ui.page-header eyebrow="Konten publik" title="Edit Opportunity" :description="$opportunity->title" /><form method="POST" action="{{ route('admin.opportunities.update',$opportunity) }}">@csrf @method('PATCH') @include('admin.opportunities._form')</form></div>@endsection
