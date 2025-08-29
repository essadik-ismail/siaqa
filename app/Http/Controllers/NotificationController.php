<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $notifications = Notification::paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,error,success',
            'destinataire_id' => 'nullable|exists:users,id',
            'statut' => 'required|in:non_lu,lu',
        ]);

        Notification::create($validated);

        return redirect()->route('notifications.index')
            ->with('success', 'Notification créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification): View
    {
        return view('notifications.show', compact('notification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification): View
    {
        return view('notifications.edit', compact('notification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification): RedirectResponse
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,error,success',
            'destinataire_id' => 'nullable|exists:users,id',
            'statut' => 'required|in:non_lu,lu',
        ]);

        $notification->update($validated);

        return redirect()->route('notifications.index')
            ->with('success', 'Notification mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification): RedirectResponse
    {
        $notification->delete();

        return redirect()->route('notifications.index')
            ->with('success', 'Notification supprimée avec succès.');
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification): RedirectResponse
    {
        $notification->update(['statut' => 'lu']);

        return redirect()->back()
            ->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(): RedirectResponse
    {
        Notification::where('statut', 'non_lu')->update(['statut' => 'lu']);

        return redirect()->back()
            ->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}
