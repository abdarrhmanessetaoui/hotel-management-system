<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotSuggestion;
use Illuminate\Http\Request;

class ChatbotSuggestionController extends Controller
{
    public function index()
    {
        $suggestions = ChatbotSuggestion::orderBy('id', 'desc')->get();
        return view('superadmin.chatbot-suggestions.index', compact('suggestions'));
    }

    public function create()
    {
        return view('superadmin.chatbot-suggestions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'role' => 'required|in:client,admin,all',
            'is_active' => 'required|boolean',
        ]);

        ChatbotSuggestion::create($request->only(['text', 'role', 'is_active']));


        return redirect()->route('superadmin.chatbot-suggestions.index')
            ->with('success', 'La suggestion a été créée avec succès.');
    }

    public function edit(ChatbotSuggestion $chatbotSuggestion)
    {
        return view('superadmin.chatbot-suggestions.edit', compact('chatbotSuggestion'));
    }

    public function update(Request $request, ChatbotSuggestion $chatbotSuggestion)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'role' => 'required|in:client,admin,all',
            'is_active' => 'required|boolean',
        ]);

        $chatbotSuggestion->update($request->only(['text', 'role', 'is_active']));


        return redirect()->route('superadmin.chatbot-suggestions.index')
            ->with('success', 'La suggestion a été mise à jour avec succès.');
    }

    public function destroy(ChatbotSuggestion $chatbotSuggestion)
    {
        $chatbotSuggestion->delete();

        return redirect()->route('superadmin.chatbot-suggestions.index')
            ->with('success', 'La suggestion a été supprimée avec succès.');
    }
}
