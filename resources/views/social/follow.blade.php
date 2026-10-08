@auth @if(auth()->user()->hasRole('youth'))
<form method="post" action="{{ route('social.follow') }}">@csrf
<input type="hidden" name="target_type" value="{{ $targetType }}"><input type="hidden" name="target_id" value="{{ $targetId }}"><input type="hidden" name="following" value="{{ $followed ? 0 : 1 }}">
<button class="btn-outline !min-h-10 !px-3 text-sm" type="submit">{{ $followed ? 'Berhenti mengikuti' : 'Ikuti '.$targetType }}</button></form>
@endif @endauth
