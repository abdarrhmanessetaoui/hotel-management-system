<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => 'Veuillez entrer votre adresse e-mail.',
            'email.email' => 'Veuillez entrer une adresse e-mail valide.',
        ]);

        // TODO: save to DB / send to mailing service (Mailchimp etc.)

        return response()->json([
            'message' => 'Merci ! Vous êtes maintenant inscrit à notre newsletter.',
        ]);
    }
}
