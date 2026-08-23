<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Email;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailMonitorController extends Controller
{
    public function index(Request $request): View
    {
        $emails = Email::with('temporaryEmail.domain')
            ->when($request->search, function ($query, $search) {
                $query->where('from_address', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhereHas('temporaryEmail', fn ($q) => $q->where('email_address', 'like', "%{$search}%"));
            })
            ->latest('received_at')
            ->paginate(30)
            ->withQueryString();

        return view('admin.emails.index', compact('emails'));
    }

    public function show(Email $email): View
    {
        $email->load('temporaryEmail.domain', 'attachments');

        return view('admin.emails.show', compact('email'));
    }

    public function destroy(Email $email): RedirectResponse
    {
        $email->attachments()->delete();
        $email->delete();

        return redirect()->route('admin.emails.index')->with('success', 'Email deleted.');
    }
}
