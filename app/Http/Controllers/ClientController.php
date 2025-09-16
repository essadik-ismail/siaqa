<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Client::where('tenant_id', auth()->user()->tenant_id);

        // Search functionality
        if ($request->has('search') && !empty($request->get('search'))) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%")
                  ->orWhere('numero_permis', 'like', "%{$search}%")
                  ->orWhere('numero_piece_identite', 'like', "%{$search}%");
            });
        }

        // Filter by blacklist status
        if ($request->has('is_blacklisted') && $request->get('is_blacklisted') !== '') {
            $query->where('is_blacklisted', $request->boolean('is_blacklisted'));
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort fields to prevent SQL injection
        $allowedSortFields = ['nom', 'prenom', 'email', 'created_at', 'is_blacklisted'];
        if (!in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }
        
        $query->orderBy($sortBy, $sortOrder);

        // Add reservations count for better performance
        $query->withCount('reservations');

        $clients = $query->get();

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['tenant_id'] = auth()->user()->tenant_id;

        // Handle main image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('clients/images', 'public');
            $data['image'] = $imagePath;
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('clients/images', 'public');
                $imagePaths[] = $imagePath;
            }
            $data['images'] = $imagePaths;
        }

        $client = Client::create($data);

        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): View
    {
        // Ensure the client belongs to the current tenant
        if ($client->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Client non trouvé');
        }

        $client->load(['reservations']);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client): View
    {
        // Ensure the client belongs to the current tenant
        if ($client->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Client non trouvé');
        }

        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRequest $request, Client $client): RedirectResponse
    {
        // Ensure the client belongs to the current tenant
        if ($client->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Client non trouvé');
        }

        $data = $request->validated();

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($client->image && Storage::disk('public')->exists($client->image)) {
                Storage::disk('public')->delete($client->image);
            }
            $imagePath = $request->file('image')->store('clients/images', 'public');
            $data['image'] = $imagePath;
        }

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            // Delete old images if exist
            if ($client->images) {
                foreach ($client->images as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('clients/images', 'public');
                $imagePaths[] = $imagePath;
            }
            $data['images'] = $imagePaths;
        }

        $client->update($data);

        return redirect()->route('clients.index')
            ->with('success', 'Client mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): RedirectResponse
    {
        // Ensure the client belongs to the current tenant
        if ($client->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Client non trouvé');
        }

        // Check if client has active reservations
        if ($client->reservations()->whereIn('statut', ['confirmee', 'en_cours'])->exists()) {
            return redirect()->route('clients.index')
                ->with('error', 'Impossible de supprimer ce client car il a des réservations actives');
        }

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès');
    }

    /**
     * Toggle blacklist status of a client.
     */
    public function toggleBlacklist(Client $client): RedirectResponse
    {
        // Ensure the client belongs to the current tenant
        if ($client->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Client non trouvé');
        }

        $client->update(['is_blacklisted' => !$client->is_blacklisted]);

        $status = $client->is_blacklisted ? 'blacklisté' : 'déblacklisté';
        return redirect()->route('clients.index')
            ->with('success', "Client {$status} avec succès");
    }

    /**
     * Display client statistics.
     */
    public function statistics(): View
    {
        $tenantId = auth()->user()->tenant_id;
        
        $totalClients = Client::where('tenant_id', $tenantId)->count();
        $activeClients = Client::where('tenant_id', $tenantId)->where('is_blacklisted', false)->count();
        $blockedClients = Client::where('tenant_id', $tenantId)->where('is_blacklisted', true)->count();
        $newClientsThisMonth = Client::where('tenant_id', $tenantId)->whereMonth('created_at', now()->month)->count();

        $clientsByType = Client::where('tenant_id', $tenantId)
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $recentClients = Client::where('tenant_id', $tenantId)
            ->withCount('reservations')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('clients.statistics', compact(
            'totalClients',
            'activeClients',
            'blockedClients',
            'newClientsThisMonth',
            'clientsByType',
            'recentClients'
        ));
    }

    /**
     * Search clients.
     */
    public function search(Request $request): View
    {
        $query = $request->get('q');
        $tenantId = auth()->user()->tenant_id;
        
        $clients = Client::where('tenant_id', $tenantId)
            ->where(function ($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                  ->orWhere('prenom', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('telephone', 'like', "%{$query}%")
                  ->orWhere('numero_permis', 'like', "%{$query}%");
            })->paginate(15);

        return view('clients.search', compact('clients', 'query'));
    }
} 