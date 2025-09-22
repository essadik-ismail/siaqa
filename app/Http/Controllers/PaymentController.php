<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $tenantId = auth()->user()->tenant_id ?? 1; // Default to tenant 1 if not set
        $query = Payment::with(['student', 'lesson', 'exam', 'tenant'])
            ->where('tenant_id', $tenantId);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_number', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('student', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $payments = $query->paginate($perPage);

        // Get filter options
        $students = Student::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('payments.index', compact('payments', 'students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): \Illuminate\View\View
    {
        $tenantId = auth()->user()->tenant_id ?? 1; // Default to tenant 1 if not set
        $students = Student::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $lessons = Lesson::where('tenant_id', $tenantId)
            ->where('status', 'scheduled')
            ->with('student:id,name')
            ->get(['id', 'student_id', 'scheduled_at']);

        $exams = Exam::where('tenant_id', $tenantId)
            ->where('status', 'scheduled')
            ->with('student:id,name')
            ->get(['id', 'student_id', 'scheduled_at']);

        return view('payments.create', compact('students', 'lessons', 'exams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            DB::beginTransaction();

            $payment = Payment::create($request->validated());

            // Load relationships
            $payment->load(['student', 'lesson', 'exam', 'tenant']);

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', 'Paiement créé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment): \Illuminate\View\View
    {
        // Check if payment belongs to current tenant
        if ($payment->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Payment not found');
        }

        $payment->load(['student', 'lesson', 'exam', 'tenant']);

        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment): \Illuminate\View\View
    {
        // Check if payment belongs to current tenant
        if ($payment->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Payment not found');
        }

        $students = Student::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $lessons = Lesson::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'scheduled')
            ->with('student:id,name')
            ->get(['id', 'student_id', 'scheduled_at']);

        $exams = Exam::where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'scheduled')
            ->with('student:id,name')
            ->get(['id', 'student_id', 'scheduled_at']);

        return view('payments.edit', compact('payment', 'students', 'lessons', 'exams'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment): \Illuminate\Http\RedirectResponse
    {
        // Check if payment belongs to current tenant
        if ($payment->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Payment not found');
        }

        try {
            DB::beginTransaction();

            $payment->update($request->validated());
            $payment->load(['student', 'lesson', 'exam', 'tenant']);

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', 'Paiement mis à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment): \Illuminate\Http\RedirectResponse
    {
        // Check if payment belongs to current tenant
        if ($payment->tenant_id !== auth()->user()->tenant_id) {
            abort(404, 'Payment not found');
        }

        try {
            // Check if payment is already paid
            if ($payment->status === 'paid') {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer un paiement déjà effectué');
            }

            $payment->delete();

            return redirect()->route('payments.index')
                ->with('success', 'Paiement supprimé avec succès');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Mark payment as paid.
     */
    public function markAsPaid(Request $request, Payment $payment): JsonResponse
    {
        // Check if payment belongs to current tenant
        if ($payment->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($payment->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Payment is already marked as paid'
            ], Response::HTTP_CONFLICT);
        }

        $request->validate([
            'amount_paid' => 'required|numeric|min:0.01|max:999999.99',
            'paid_date' => 'nullable|date|before_or_equal:today',
            'transaction_id' => 'nullable|string|max:100',
            'payment_method' => 'nullable|in:cash,card,bank_transfer,check,online'
        ]);

        $amountPaid = $request->amount_paid;
        $totalAmount = $payment->amount;

        if ($amountPaid > $totalAmount) {
            return response()->json([
                'success' => false,
                'message' => 'Amount paid cannot exceed the total amount'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $status = $amountPaid >= $totalAmount ? 'paid' : 'partial';

        $payment->update([
            'amount_paid' => $amountPaid,
            'status' => $status,
            'paid_date' => $request->paid_date ?? now()->toDateString(),
            'transaction_id' => $request->transaction_id,
            'payment_method' => $request->payment_method ?? $payment->payment_method
        ]);

        return response()->json([
            'success' => true,
            'data' => $payment,
            'message' => 'Payment marked as paid successfully'
        ]);
    }

    /**
     * Get payment statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = Payment::where('tenant_id', auth()->user()->tenant_id);

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $stats = [
            'total_payments' => $query->count(),
            'total_amount' => $query->sum('amount'),
            'total_paid' => $query->sum('amount_paid'),
            'total_balance' => $query->sum('amount') - $query->sum('amount_paid'),
            'pending' => $query->where('status', 'pending')->count(),
            'partial' => $query->where('status', 'partial')->count(),
            'paid' => $query->where('status', 'paid')->count(),
            'overdue' => $query->where('status', 'overdue')->count(),
            'cancelled' => $query->where('status', 'cancelled')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Payment statistics retrieved successfully'
        ]);
    }

    /**
     * Get overdue payments.
     */
    public function overdue(Request $request): JsonResponse
    {
        $query = Payment::with(['student'])
            ->where('tenant_id', auth()->user()->tenant_id)
            ->where('status', '!=', 'paid')
            ->where('due_date', '<', now()->toDateString());

        if ($request->has('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $perPage = $request->get('per_page', 15);
        $overduePayments = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $overduePayments,
            'message' => 'Overdue payments retrieved successfully'
        ]);
    }

    /**
     * Get payments by student.
     */
    public function byStudent(Student $student): JsonResponse
    {
        // Check if student belongs to current tenant
        if ($student->tenant_id !== auth()->user()->tenant_id) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $payments = $student->payments()
            ->with(['lesson', 'exam'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $payments,
            'message' => 'Student payments retrieved successfully'
        ]);
    }

    /**
     * Get payment summary by type.
     */
    public function summaryByType(Request $request): JsonResponse
    {
        $query = Payment::where('tenant_id', auth()->user()->tenant_id);

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $summary = $query->selectRaw('
                payment_type,
                COUNT(*) as count,
                SUM(amount) as total_amount,
                SUM(amount_paid) as total_paid,
                SUM(amount - amount_paid) as total_balance
            ')
            ->groupBy('payment_type')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $summary,
            'message' => 'Payment summary by type retrieved successfully'
        ]);
    }
}
