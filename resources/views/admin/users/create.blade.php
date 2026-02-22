@extends('layouts.admin')

@section('title', 'Add User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="bi bi-person-plus me-2"></i>Add User
    </h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4 p-lg-5">
        <div class="mb-4 pb-3 border-bottom">
            <p class="text-muted mb-0 small">
                <i class="bi bi-envelope-check me-1"></i>
                A welcome email with login credentials will be sent to the user automatically.
            </p>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="john@example.com" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mobile</label>
                    <input type="text" name="mobile" class="form-control form-control-lg" value="{{ old('mobile') }}" placeholder="+1 234 567 8900">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select form-select-lg">
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" placeholder="••••••••" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="••••••••" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Roles <span class="text-danger">*</span></label>
                    <div class="row g-3">
                        @foreach($roles as $r)
                        <div class="col-md-4 col-lg-3">
                            <div class="form-check p-3 border rounded-3" style="transition: all 0.2s ease;">
                                <input type="checkbox" name="roles[]" value="{{ $r->id }}" class="form-check-input" id="role{{ $r->id }}" {{ in_array($r->id, old('roles', [])) ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="role{{ $r->id }}">{{ $r->name }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @error('roles')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mt-4 pt-4 border-top d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 py-2">
                    <i class="bi bi-check-lg me-2"></i>Create User & Send Welcome Email
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4 py-2">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
