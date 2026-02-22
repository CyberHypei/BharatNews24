<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['required', 'string', 'in:' . implode(',', array_keys(Contact::subjectOptions()))],
            'message' => ['required', 'string', 'max:5000'],
            'newsletter' => ['nullable', 'boolean'],
            'privacy' => ['required', 'accepted'],
        ]);

        $validated['phone'] = $request->filled('phone') ? $validated['phone'] : null;
        $validated['newsletter'] = $request->boolean('newsletter');
        $validated['status'] = 'unread';
        $validated['ip_address'] = $request->ip();
        unset($validated['privacy']);

        try {
            Contact::create($validated);
        } catch (\Throwable $e) {
            Log::error('Contact form store failed: ' . $e->getMessage(), [
                'exception' => $e,
                'validated_keys' => array_keys($validated),
            ]);
            return redirect()->back()
                ->withInput($request->only('name', 'email', 'phone', 'subject', 'message', 'newsletter'))
                ->with('error', 'Unable to save your message. Please try again or contact support.');
        }

        return redirect()->route('contact.index')->with('success', 'Your message has been sent. We will get back to you soon.');
    }
}
