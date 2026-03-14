<?php

namespace App\Http\Controllers\Backoffice\Support;

use App\Http\Controllers\Controller;
use App\Http\Requests\Support\StoreSupportTicketReplyRequest;
use App\Http\Requests\Support\StoreSupportTicketRequest;
use App\Models\Support\SupportTicket;
use App\Models\User;
use App\Notifications\SupportTicketCreatedNotification;
use App\Notifications\SupportTicketReplyNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(Request $request): View
    {
        $query = SupportTicket::query()->with('user')->withCount('replies');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();

        // Stat counts
        $totalCount      = SupportTicket::count();
        $openCount       = SupportTicket::where('status', 'open')->count();
        $inProgressCount = SupportTicket::where('status', 'in_progress')->count();
        $resolvedCount   = SupportTicket::where('status', 'resolved')->count();
        $closedCount     = SupportTicket::where('status', 'closed')->count();

        return view('backoffice.support.tickets.index', compact(
            'tickets', 'totalCount', 'openCount', 'inProgressCount', 'resolvedCount', 'closedCount'
        ));
    }

    public function create(): View
    {
        return view('backoffice.support.tickets.create');
    }

    public function store(StoreSupportTicketRequest $request): RedirectResponse
    {
        $ticket = SupportTicket::create($request->safe()->except('attachments'));

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $ticket->addMedia($file)->toMediaCollection('attachments');
            }
        }

        // Notify all super admins
        $superAdmins = User::whereNull('tenant_id')->get();
        Notification::send($superAdmins, new SupportTicketCreatedNotification($ticket));

        return redirect()->route('bo.support.tickets.index')
            ->with('success', __('Ticket créé avec succès. Notre équipe vous répondra dans les plus brefs délais.'));
    }

    public function show(SupportTicket $ticket): View
    {
        $ticket->load(['user', 'replies.user', 'media']);

        return view('backoffice.support.tickets.show', compact('ticket'));
    }

    public function reply(StoreSupportTicketReplyRequest $request, SupportTicket $ticket): RedirectResponse
    {
        $ticket->replies()->create([
            'user_id'        => auth()->id(),
            'message'        => $request->validated('message'),
            'is_admin_reply' => false,
        ]);

        if ($ticket->status === 'resolved' || $ticket->status === 'closed') {
            $ticket->update(['status' => 'open', 'resolved_at' => null, 'closed_at' => null]);
        }

        // Notify all super admins about client reply
        $superAdmins = User::whereNull('tenant_id')->get();
        Notification::send($superAdmins, new SupportTicketReplyNotification($ticket, isAdminReply: false));

        return back()->with('success', __('Réponse envoyée avec succès.'));
    }
}
