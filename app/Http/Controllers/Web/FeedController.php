<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Feed;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $feeds = Feed::with('feedProfiles')
            ->orderBy('start_day')
            ->get();
        $categories = ['broiler', 'layer'];

        return view(
            'admin.feeds.index',
            compact('feeds', 'categories')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // No implementationis required
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'start_day'     => 'required|integer|min:0',
            'end_day'       => 'nullable|integer|min:0',
            'feed_form'     => 'required|string|max:150',
            'particle_size' => 'nullable|string',
            'category'      => 'required|in:broiler,layer',
        ]);

        // Create the Feed
        $feed = Feed::create($validated);

        return redirect()->route('feeds.index')
            ->with('success', 'Feed has been created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Feed $feed)
    {
        return response()->json($feed);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feed $feed)
    {
        // No implementaion is required
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feed $feed)
    {
         $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'start_day'     => 'required|integer|min:0',
            'end_day'       => 'nullable|integer|min:0',
            'feed_form'     => 'required|string|max:150',
            'particle_size' => 'nullable|string',
            'category'      => 'required|in:broiler,layer',
        ]);

        // Update the Feed
        $f = $feed->update($validated);

        return redirect()->route('feeds.index')
            ->with('success', 'Feed has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feed $feed)
    {
        $title = $feed->title;
        $feed->delete();
        return redirect()->route('feeds.index')
            ->with('success', "Feed: {$title} has been deleted successfully.");
    }
}
