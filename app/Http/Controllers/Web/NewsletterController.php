<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Jobs\Newsletter\PrepareNewsletterDeliveriesJob;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsletterController extends Controller
{
    public function index()
    {
        $newsletters = Newsletter::with('deliveries')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.newsletters.index', compact('newsletters'));
    }

    public function create()
    {
        return view('admin.newsletters.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'preview_text' => 'nullable|string|max:500',
            'content_html' => 'required|string',
            'content_text' => 'nullable|string',
            'status' => 'required|in:draft,pending,sending,sent,failed',
            'send_at' => 'nullable|date|after:now',
        ]);

        $validated['created_by'] = Auth::id();

        Newsletter::create($validated);

        return redirect()
            ->route('newsletters.index')
            ->with('success', 'Newsletter created successfully.');
    }

    public function show(Newsletter $newsletter)
    {
        $newsletter->load('deliveries');

        return view('admin.newsletters.show', compact('newsletter'));
    }

    public function edit(Newsletter $newsletter)
    {
        return view('admin.newsletters.edit', compact('newsletter'));
    }

    public function update(Request $request, Newsletter $newsletter)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'preview_text' => 'nullable|string|max:500',
            'content_html' => 'required|string',
            'content_text' => 'nullable|string',
            'status' => 'required|in:draft,pending,sending,sent,failed',
            'send_at' => 'nullable|date|after:now',
        ]);

        $newsletter->update($validated);

        return redirect()
            ->route('newsletters.index')
            ->with('success', 'Newsletter updated successfully.');
    }

    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();

        return redirect()
            ->route('newsletters.index')
            ->with('success', 'Newsletter deleted successfully.');
    }

    public function queueSend(Newsletter $newsletter)
    {
        // Mark as pending if it’s draft
        if ($newsletter->status === 'draft') {
            $newsletter->update(['status' => 'pending']);
        }

        PrepareNewsletterDeliveriesJob::dispatch($newsletter->id);

        return back()->with('success', 'Queued for sending.');
    }
}
