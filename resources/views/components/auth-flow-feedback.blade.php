@php($feedback = session('error') ?? session('status'))
@if(is_string($feedback))
    <p role="{{ session('error') ? 'alert' : 'status' }}" class="mb-5 rounded-lg border border-border bg-surface-soft p-4 text-sm leading-6 text-text-primary">{{ $feedback }}</p>
@endif
@if($errors->any())
    <div role="alert" class="mb-5 rounded-lg border border-danger/30 bg-surface-soft p-4 text-sm leading-6 text-danger"><p class="font-bold">Periksa kembali permintaan Anda.</p><ul class="mt-2 list-inside list-disc">@foreach($errors->all() as $message)<li>{{ $message }}</li>@endforeach</ul></div>
@endif
