@extends('emails.layouts.base')

@section('content')
    <h2 style="margin:0 0 10px;font-size:20px;">{{ $newsletter->subject }}</h2>

    @if($newsletter->preview_text)
        <p style="margin:0 0 16px;color:#475569;line-height:1.7;">
            {{ $newsletter->preview_text }}
        </p>
    @endif

    <div style="border:1px solid #e2e8f0;border-radius:12px;padding:16px;">
        {!! $newsletter->content_html !!}
    </div>

    <p style="margin:16px 0 0;color:#334155;line-height:1.7;">
        — {{ config('flocksense.brand_name') }} Team
    </p>
@endsection
