<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Mail\Newsletter\SubscriberWelcomeMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = NewsletterSubscriber::query();

        if ($status == 'subscribed') {
            $query->where('status', 'subscribed');
        } elseif ($status == 'unsubscribed') {
            $query->where('status', 'unsubscribed');
        }

        $subscribers = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return view(
            'admin.newsletters.subscribers',
            compact('subscribers', 'status')
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'source' => ['nullable', 'string', 'max:50'],
        ]);

        // Check if already subscribed
        $existingSubscriber = NewsletterSubscriber::where('email', strtolower($data['email']))
            ->where('status', 'subscribed')
            ->first();

        if ($existingSubscriber) {
            return response()->json([
                'message' => 'This email is already subscribed.',
                'type' => 'warning',
            ], 409);
        }

        // Create or update subscriber
        $subscriber = NewsletterSubscriber::firstOrNew(['email' => strtolower($data['email'])]);

        // If previously unsubscribed, re-subscribe
        $subscriber->status = 'subscribed';
        $subscriber->confirmed_at = now();

        $subscriber->source = $data['source'] ?? $subscriber->source ?? 'website';
        $subscriber->ip_address = $request->ip();
        $subscriber->user_agent = substr((string) $request->userAgent(), 0, 1000);

        $subscriber->save();

        // Queue welcome email
        Mail::to($subscriber->email)->queue(new SubscriberWelcomeMail($subscriber));

        return response()->json([
            'message' => 'Subscribed successfully. Please check your email.',
            'type' => 'success',
        ]);
    }

    public function toggleStatus(Request $request, NewsletterSubscriber $subscriber)
    {
        $request->validate([
            'status' => 'required|in:subscribed,unsubscribed',
        ]);

        $subscriber->update([
            'status' => $request->status,
            'confirmed_at' => $request->status === 'subscribed' ? now() : null,
        ]);

        return back()->with('success', 'Subscriber status updated successfully.');
    }

    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)
            ->firstOrFail();

        $subscriber->update(['status' => 'unsubscribed']);

        return view('newsletter.unsubscribed'); // simple confirmation page
    }
}
