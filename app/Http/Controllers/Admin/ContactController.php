<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $query = Contact::query()->latest();

        if (in_array($request->get('status'), ['unread', 'read'], true)) {
            $query->where('status', $request->get('status'));
        }

        $contacts = $query->paginate(20)->withQueryString();

        return view('admin.contacts.index', compact('contacts'));
    }

    public function show(Contact $contact)
    {
        if ($contact->status === 'unread') {
            $contact->update(['status' => 'read']);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function markRead(Contact $contact)
    {
        $contact->update(['status' => 'read']);

        return redirect()->route('admin.contacts.index')->with('success', 'Message marked as read.');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Message deleted.');
    }
}
