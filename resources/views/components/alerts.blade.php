@php
    $messages = [];
    if (($errors ?? null)?->any()) {
        $messages[] = ['icon' => 'error', 'text' => implode("\n", $errors->all())];
    } else {
        foreach (['error' => 'error', 'warning' => 'warning', 'info' => 'info', 'success' => 'success', 'status' => 'success'] as $key => $icon) {
            if (session()->has($key) && is_string(session($key))) {
                $messages[] = ['icon' => $icon, 'text' => session($key)];
            }
        }
    }
@endphp
<script type="application/json" id="sipora-alert-data">{!! json_encode($messages, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) !!}</script>
