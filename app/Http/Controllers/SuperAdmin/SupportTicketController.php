<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Support\StoreSupportTicketReplyRequest;
use App\Models\Support\SupportTicket;
use App\Models\Tenancy\Tenant;
use App\Notifications\SupportTicketReplyNotification;
use App\Notifications\SupportTicketStatusChangedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(Request $request): View
    {
        $query = SupportTicket::withoutGlobalScopes()
            ->with(['user', 'tenant'])
            ->withCount('replies');

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

        if ($tenantId = $request->input('tenant_id')) {
            $query->where('tenant_id', $tenantId);
        }

        $tickets    = $query->latest()->paginate(20)->withQueryString();
        $totalCount = SupportTicket::withoutGlobalScopes()->count();
        $openCount  = SupportTicket::withoutGlobalScopes()->where('status', 'open')->count();
        $tenants    = Tenant::orderBy('name')->get(['id', 'name']);

        return view('backoffice.superadmin.support-tickets.index', compact('tickets', 'totalCount', 'openCount', 'tenants'));
    }

    public function show(string $id): View
    {
        $ticket = SupportTicket::withoutGlobalScopes()
            ->with(['user', 'tenant', 'replies.user', 'media'])
            ->findOrFail($id);

        return view('backoffice.superadmin.support-tickets.show', compact('ticket'));
    }

    public function reply(StoreSupportTicketReplyRequest $request, string $id): RedirectResponse
    {
        $ticket = SupportTicket::withoutGlobalScopes()->findOrFail($id);

        $ticket->replies()->create([
            'user_id'        => auth()->id(),
            'message'        => $request->validated('message'),
            'is_admin_reply' => true,
        ]);

        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        // Notify the ticket owner about admin reply
        $ticket->user->notify(new SupportTicketReplyNotification($ticket, isAdminReply: true));

        return back()->with('success', __('Réponse envoyée avec succès.'));
    }

    public function updateStatus(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:open,in_progress,on_hold,resolved,closed'],
        ]);

        $ticket = SupportTicket::withoutGlobalScopes()->findOrFail($id);

        $data = ['status' => $request->status];

        if ($request->status === 'resolved') {
            $data['resolved_at'] = now();
        } elseif ($request->status === 'closed') {
            $data['closed_at'] = now();
        }

        $ticket->update($data);

        // Notify the ticket owner about status change
        $ticket->user->notify(new SupportTicketStatusChangedNotification($ticket, $request->status));

        return back()->with('success', __('Statut mis à jour avec succès.'));
    }
}
