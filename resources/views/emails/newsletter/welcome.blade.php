@extends('emails.layouts.base')

@section('content')
    <h2 style="margin:0 0 10px;font-size:20px;">Welcome to the {{ config('flocksense.brand_name') }} Newsletter</h2>

    <p style="margin:0 0 14px;line-height:1.7;color:#334155;">
        You’re now subscribed with <strong>{{ $subscriber->email }}</strong>.
        We’ll send you product updates, new features, and release notes—no spam.
    </p>

    <div style="margin:18px 0;padding:14px 16px;background:#ecfeff;border:1px solid #cffafe;border-radius:12px;color:#155e75;">
        <strong>What to expect:</strong>
        <ul style="margin:10px 0 0;padding-left:18px;">
            <li>Feature releases & improvements</li>
            <li>Farm productivity tips</li>
            <li>Security and compliance updates</li>
        </ul>
    </div>

    <p style="margin:0;line-height:1.7;color:#334155;">
        Thanks for joining,<br>
        <strong>{{ config('flocksense.brand_name') }} Team</strong>
    </p>
@endsection
