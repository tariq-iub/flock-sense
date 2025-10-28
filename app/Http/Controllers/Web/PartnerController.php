<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Partner::ordered();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Filter by keyword
        if ($request->has('keyword') && $request->keyword) {
            $query->byKeyword($request->keyword);
        }

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        $partners = $request->has('per_page')
            ? $query->paginate($request->per_page)
            : $query->get();

        return view(
            'admin.partners.index',
            compact('partners')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'url' => 'nullable|url',
            'introduction' => 'nullable|string',
            'partnership_detail' => 'nullable|string',
            'support_keywords' => 'nullable|array',
            'support_keywords.*' => 'string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'logo' => 'nullable|mimes:jpeg,jpg,png|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->only([
            'company_name', 'url', 'introduction', 'partnership_detail', 'support_keywords', 'is_active', 'sort_order',
        ]);

        $partner = Partner::create($data);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $partner->addMedia($file);
        }

        return redirect()
            ->route('partners.index')
            ->with('success', 'Partner is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Partner $partner)
    {
        return $partner;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partner $partner)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partner $partner)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'sometimes|required|string|max:255',
            'url' => 'nullable|url',
            'introduction' => 'nullable|string',
            'partnership_detail' => 'nullable|string',
            'support_keywords' => 'nullable|array',
            'support_keywords.*' => 'string|max:50',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2000',
        ]);

        $data = $request->only([
            'company_name', 'url', 'introduction', 'partnership_detail', 'support_keywords', 'is_active', 'sort_order',
        ]);

        $partner->update($data);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('file');
            if ($partner->media != null && $partner->media->first()) {
                $partner->deleteMedia($partner->media->first()->id);
            }
            $partner->addMedia($file);
        }

        return redirect()
            ->route('partners.index')
            ->with('success', 'Partner is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partner $partner)
    {
        // Delete logo file if exists
        $media = $partner->media()->first();

        if ($media) {
            $partner->deleteMedia($media->id);
        }

        $partner->delete();

        return redirect()
            ->route('partners.index')
            ->with('success', 'Partner is deleted successfully.');
    }

    /**
     * Add keyword to partner
     */
    public function addKeyword(Partner $partner, Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:50',
        ]);

        $partner->addKeyword($request->keyword);
        $partner->save();

        return redirect()
            ->route('partners.index')
            ->with('success', 'Keyword added successfully.');
    }

    /**
     * Remove keyword from partner
     */
    public function removeKeyword(Partner $partner, Request $request)
    {
        $request->validate([
            'keyword' => 'required|string',
        ]);

        $partner->removeKeyword($request->keyword);
        $partner->save();

        return redirect()
            ->route('partners.index')
            ->with('success', 'Keyword removed successfully.');
    }

    /**
     * Get all unique keywords across partners
     */
    public function getAllKeywords()
    {
        $keywords = Partner::active()
            ->pluck('support_keywords')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        return response()->json([
            'success' => true,
            'data' => $keywords,
        ]);
    }
}
