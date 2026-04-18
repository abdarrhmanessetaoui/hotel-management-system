<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // ─── GET /superadmin/reviews ───────────────────────────────────────────────
    public function index(Request $request)
    {
        $reviews = Review::with('user')->latest()->paginate(15);
        return view('superadmin.reviews.index', compact('reviews'));
    }

    // ─── PUT /superadmin/reviews/{review}/approve ──────────────────────────────
    public function approve(Review $review, Request $request)
    {
        $review->update(['status' => Review::STATUS_ACCEPTED]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => 'accepted']);
        }

        return back()->with('message', 'Avis accepté avec succès.');
    }

    // ─── PUT /superadmin/reviews/{review}/reject ───────────────────────────────
    public function reject(Review $review, Request $request)
    {
        $review->update(['status' => Review::STATUS_REJECTED]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'status' => 'rejected']);
        }

        return back()->with('message', 'Avis refusé.');
    }

    // ─── PATCH /superadmin/reviews/{review} ───────────────────────────────────
    public function update(Review $review, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,rejected'
        ]);

        $review->update(['status' => $request->status]);

        return back()->with('message', 'Le statut de l\'avis a été mis à jour.');
    }

    // ─── DELETE /superadmin/reviews/{review} ───────────────────────────────────
    public function destroy(Review $review, Request $request)
    {
        $review->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('message', 'Avis supprimé définitivement.');
    }
}
