@extends('layouts.admin')

@section('title', 'Contact Message')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Contact Message</h1>
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back to list</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-2 fw-bold">Name</div>
            <div class="col-md-10">{{ $contact->name }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-2 fw-bold">Email</div>
            <div class="col-md-10"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-2 fw-bold">Phone</div>
            <div class="col-md-10">{{ $contact->phone ?? '—' }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-2 fw-bold">Subject</div>
            <div class="col-md-10">{{ \App\Models\Contact::subjectOptions()[$contact->subject] ?? $contact->subject }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-2 fw-bold">Message</div>
            <div class="col-md-10">{{ $contact->message }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-2 fw-bold">Newsletter</div>
            <div class="col-md-10">{{ $contact->newsletter ? 'Yes' : 'No' }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-2 fw-bold">Status</div>
            <div class="col-md-10"><span class="badge bg-{{ $contact->status === 'read' ? 'secondary' : 'primary' }}">{{ $contact->status }}</span></div>
        </div>
        <div class="row mb-3">
            <div class="col-md-2 fw-bold">Submitted</div>
            <div class="col-md-10">{{ $contact->created_at->format('M d, Y H:i') }} @if($contact->ip_address)(IP: {{ $contact->ip_address }})@endif</div>
        </div>
        <hr>
        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this message?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">Delete message</button>
        </form>
    </div>
</div>
@endsection
