@extends('layouts.app')

@section('title', 'Student Package Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Student Package Details</h3>
                    <div class="btn-group">
                        <a href="{{ route('student-packages.edit', $studentPackage) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('student-packages.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Student Information</h5>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Name:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $studentPackage->student->name }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Email:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $studentPackage->student->email }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Phone:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $studentPackage->student->phone ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-{{ $studentPackage->student->status === 'active' ? 'success' : 'warning' }}">
                                        {{ ucfirst($studentPackage->student->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Package Information</h5>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Package:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $studentPackage->package->name }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>License Category:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-info">{{ $studentPackage->package->license_category }}</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Price:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span class="fw-bold text-success">${{ number_format($studentPackage->price, 2) }}</span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Includes Exam:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-{{ $studentPackage->package->includes_exam ? 'success' : 'secondary' }}">
                                        {{ $studentPackage->package->includes_exam ? 'Yes' : 'No' }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Includes Materials:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-{{ $studentPackage->package->includes_materials ? 'success' : 'secondary' }}">
                                        {{ $studentPackage->package->includes_materials ? 'Yes' : 'No' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Purchase Details</h5>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-sm-8">
                                    <span class="badge bg-{{ $studentPackage->status === 'active' ? 'success' : ($studentPackage->status === 'completed' ? 'info' : ($studentPackage->status === 'cancelled' ? 'danger' : 'warning')) }}">
                                        {{ ucfirst($studentPackage->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Purchase Date:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $studentPackage->purchase_date ? \Carbon\Carbon::parse($studentPackage->purchase_date)->format('M d, Y') : 'N/A' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Expiry Date:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $studentPackage->expiry_date ? \Carbon\Carbon::parse($studentPackage->expiry_date)->format('M d, Y') : 'N/A' }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Created:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $studentPackage->created_at->format('M d, Y H:i') }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Last Updated:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $studentPackage->updated_at->format('M d, Y H:i') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Additional Information</h5>
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <strong>Notes:</strong>
                                </div>
                                <div class="col-sm-8">
                                    {{ $studentPackage->notes ?? 'No notes available' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($studentPackage->notes)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">Notes</h5>
                                <div class="alert alert-light">
                                    {{ $studentPackage->notes }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
