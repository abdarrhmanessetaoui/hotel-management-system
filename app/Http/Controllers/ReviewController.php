<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    // ─── AUTHENTICATED: Soumettre un avis (client) ────────────────────────────

    /**
     * POST /reviews
     * Seuls les utilisateurs connectés peuvent soumettre.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content'     => 'required|string|min:10|max:1000',
            'rating'      => 'required|integer|min:1|max:5',
        ], [
            'content.required'     => 'Votre avis ne peut pas être vide.',
            'content.min'          => 'Votre avis doit contenir au moins 10 caractères.',
            'content.max'          => 'Votre avis ne peut pas dépasser 1000 caractères.',
            'rating.required'      => 'Veuillez sélectionner une note.',
            'rating.min'           => 'La note minimale est 1 étoile.',
            'rating.max'           => 'La note maximale est 5 étoiles.',
        ]);

        Review::create([
            'user_id'     => Auth::id(),
            'author_name' => Auth::user()->name,
            'content'     => $validated['content'],
            'rating'      => $validated['rating'],
            'status'      => Review::STATUS_PENDING, // toujours en attente
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Merci pour votre témoignage ! Il sera visible après validation.'
            ]);
        }

        return back()->with('message', 'Merci pour votre témoignage ! Il sera visible après validation.');
    }
}

