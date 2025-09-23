@extends('layouts.app')

@section('title', 'Créer un Paiement')

@section('content')
<div class="min-h-screen">
    <!-- Floating Background Elements -->
    <div class="floating-elements">
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
        <div class="floating-circle"></div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="glass-effect rounded-2xl p-8 mb-8">
            <h1 class="text-4xl font-bold gradient-text mb-4">Créer un Paiement</h1>
            <p class="text-gray-600 text-lg">Enregistrez un nouveau paiement</p>
        </div>

        <!-- Form -->
        <div class="material-card p-8">
            <form action="{{ route('payments.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Basic Information -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations de Base</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student Selection -->
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Étudiant *
                            </label>
                            <select name="student_id" id="student_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner un étudiant</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Number -->
                        <div>
                            <label for="payment_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Numéro de Paiement *
                            </label>
                            <input type="text" name="payment_number" id="payment_number" required
                                value="{{ old('payment_number', 'PAY-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('payment_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Type -->
                        <div>
                            <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Type de Paiement *
                            </label>
                            <select name="payment_type" id="payment_type" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner le type</option>
                                <option value="lesson" {{ old('payment_type') == 'lesson' ? 'selected' : '' }}>Leçon</option>
                                <option value="exam" {{ old('payment_type') == 'exam' ? 'selected' : '' }}>Examen</option>
                                <option value="package" {{ old('payment_type') == 'package' ? 'selected' : '' }}>Forfait</option>
                                <option value="registration" {{ old('payment_type') == 'registration' ? 'selected' : '' }}>Inscription</option>
                                <option value="refund" {{ old('payment_type') == 'refund' ? 'selected' : '' }}>Remboursement</option>
                            </select>
                            @error('payment_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Montant Total (DH) *
                            </label>
                            <input type="number" name="amount" id="amount" required min="0.01" step="0.01"
                                value="{{ old('amount') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount Paid -->
                        <div>
                            <label for="amount_paid" class="block text-sm font-medium text-gray-700 mb-2">
                                Montant Payé (DH)
                            </label>
                            <input type="number" name="amount_paid" id="amount_paid" min="0" step="0.01"
                                value="{{ old('amount_paid', 0) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                oninput="validateAmountPaid()">
                            @error('amount_paid')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p id="amount_paid_error" class="mt-1 text-sm text-red-600 hidden">Le montant payé ne peut pas dépasser le montant total.</p>
                        </div>

                        <!-- Payment Method -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                Méthode de Paiement *
                            </label>
                            <select name="payment_method" id="payment_method" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner la méthode</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Carte Bancaire</option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Virement Bancaire</option>
                                <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Chèque</option>
                                <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>En Ligne</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Date -->
                        <div>
                            <label for="paid_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Date de Paiement
                            </label>
                            <input type="date" name="paid_date" id="paid_date"
                                value="{{ old('paid_date', date('Y-m-d')) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('paid_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Statut *
                            </label>
                            <select name="status" id="status" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionner le statut</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>En Attente</option>
                                <option value="partial" {{ old('status') == 'partial' ? 'selected' : '' }}>Partiel</option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Payé</option>
                                <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>En Retard</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea name="notes" id="notes" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Ajoutez des notes sur le paiement...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('payments.index') }}" 
                        class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors">
                        Annuler
                    </a>
                    <button type="submit" 
                        class="material-button px-6 py-3">
                        Créer le Paiement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function validateAmountPaid() {
    const amount = parseFloat(document.getElementById('amount').value) || 0;
    const amountPaid = parseFloat(document.getElementById('amount_paid').value) || 0;
    const errorElement = document.getElementById('amount_paid_error');
    
    if (amountPaid > amount) {
        errorElement.classList.remove('hidden');
        document.getElementById('amount_paid').classList.add('border-red-500');
        document.getElementById('amount_paid').classList.remove('border-gray-300');
    } else {
        errorElement.classList.add('hidden');
        document.getElementById('amount_paid').classList.remove('border-red-500');
        document.getElementById('amount_paid').classList.add('border-gray-300');
    }
}

// Also validate when amount changes
document.getElementById('amount').addEventListener('input', validateAmountPaid);
</script>
@endsection