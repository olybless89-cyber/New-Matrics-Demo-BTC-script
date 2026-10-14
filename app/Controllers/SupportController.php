<?php

namespace App\Controllers;

class SupportController
{
    public function index()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $tickets = \DB::fetchAll("SELECT * FROM support_tickets WHERE user_id = ? ORDER BY last_message_at DESC, id DESC", [$user['id']]);
        \View::render('support.index', [
            'active' => 'support',
            'title' => 'Help & Support',
            'tickets' => $tickets,
        ], 'dashboard');
    }

    public function create()
    {
        \Auth::requireAuth();
        \View::render('support.create', [
            'active' => 'support',
            'title' => 'New Support Ticket',
        ], 'dashboard');
    }

    public function store()
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $subject = trim($_POST['subject'] ?? '');
        $category = trim($_POST['category'] ?? 'general');
        $priority = trim($_POST['priority'] ?? 'medium');
        $message = trim($_POST['message'] ?? '');

        if (empty($subject) || empty($message)) {
            flash('error', 'Subject and message are required.');
            redirect('dashboard/support/create');
        }

        $now = date('Y-m-d H:i:s');
        $ticketId = \DB::insert('support_tickets', [
            'user_id' => $user['id'],
            'subject' => $subject,
            'category' => $category,
            'priority' => $priority,
            'status' => 'open',
            'last_message_at' => $now,
        ]);

        \DB::insert('support_messages', [
            'ticket_id' => $ticketId,
            'user_id' => $user['id'],
            'is_admin' => 0,
            'message' => $message,
        ]);

        flash('success', 'Support ticket created. We will respond shortly.');
        redirect('dashboard/support/' . $ticketId);
    }

    public function show($id)
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $ticket = \DB::fetch("SELECT * FROM support_tickets WHERE id = ? AND user_id = ?", [$id, $user['id']]);
        if (!$ticket) {
            http_response_code(404);
            \View::render('errors/404', [], 'plain');
            exit;
        }
        $messages = \DB::fetchAll("SELECT sm.*, u.fullname, u.is_admin FROM support_messages sm LEFT JOIN users u ON u.id = sm.user_id WHERE sm.ticket_id = ? ORDER BY sm.id ASC", [$id]);
        \View::render('support.show', [
            'active' => 'support',
            'title' => $ticket['subject'],
            'ticket' => $ticket,
            'messages' => $messages,
        ], 'dashboard');
    }

    public function reply($id)
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        $ticket = \DB::fetch("SELECT * FROM support_tickets WHERE id = ? AND user_id = ?", [$id, $user['id']]);
        if (!$ticket) {
            http_response_code(404);
            exit;
        }
        $message = trim($_POST['message'] ?? '');
        if (empty($message)) {
            flash('error', 'Message cannot be empty.');
            redirect('dashboard/support/' . $id);
        }

        \DB::insert('support_messages', [
            'ticket_id' => $id,
            'user_id' => $user['id'],
            'is_admin' => 0,
            'message' => $message,
        ]);
        \DB::update('support_tickets', [
            'status' => 'open',
            'last_message_at' => date('Y-m-d H:i:s'),
        ], 'id = :id', ['id' => $id]);

        redirect('dashboard/support/' . $id);
    }

    public function close($id)
    {
        \Auth::requireAuth();
        $user = \Auth::user();
        \DB::update('support_tickets', ['status' => 'closed'], 'id = :id AND user_id = :uid', ['id' => $id, 'uid' => $user['id']]);
        flash('success', 'Ticket closed.');
        redirect('dashboard/support');
    }
}
