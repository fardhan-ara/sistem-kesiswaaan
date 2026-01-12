<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    /**
     * Tampilkan halaman form kirim notifikasi (Admin only)
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $users = User::where('status', 'approved')->orderBy('nama')->get();
        $history = \App\Models\SentNotification::with('sender')->latest()->take(10)->get();
        return view('notifications.create', compact('users', 'history'));
    }

    /**
     * Kirim notifikasi ke user berdasarkan role
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'type' => 'required|in:info,success,warning,danger',
                'target_type' => 'required|in:role,individual',
                'target_role' => 'required_if:target_type,role|nullable|in:all,admin,kesiswaan,kepala_sekolah,bk,guru,wali_kelas,siswa,ortu',
                'target_users' => 'required_if:target_type,individual|nullable|array',
                'target_users.*' => 'exists:users,id',
                'action_url' => 'nullable|url'
            ]);

            if ($validated['target_type'] === 'role') {
                $query = User::where('status', 'approved');
                if ($validated['target_role'] !== 'all') {
                    $query->where('role', $validated['target_role']);
                }
                $users = $query->get();
                $targetValue = $validated['target_role'];
            } else {
                $users = User::whereIn('id', $validated['target_users'])
                    ->where('status', 'approved')
                    ->get();
                $targetValue = implode(',', $validated['target_users']);
            }

            if ($users->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Tidak ada user yang memenuhi kriteria')
                    ->withInput();
            }

            Notification::send($users, new SystemNotification(
                $validated['title'],
                $validated['message'],
                $validated['type'],
                $validated['action_url'] ?? null,
                null
            ));

            \App\Models\SentNotification::create([
                'title' => $validated['title'],
                'message' => $validated['message'],
                'type' => $validated['type'],
                'target_type' => $validated['target_type'],
                'target_value' => $targetValue,
                'recipients_count' => $users->count(),
                'action_url' => $validated['action_url'],
                'sent_by' => Auth::id()
            ]);

            return redirect()->back()->with('success', 
                'Notifikasi berhasil dikirim ke ' . $users->count() . ' user');
                
        } catch (\Exception $e) {
            \Log::error('Error sending notification: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengirim notifikasi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan daftar notifikasi user yang login
     */
    public function index()
    {
        $user = Auth::user();
        
        // Ambil notifikasi dengan pagination
        $notifications = $user->notifications()->paginate(15);
        
        // Hitung notifikasi belum dibaca
        $unreadCount = $user->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        $notification->markAsRead();

        // Redirect ke action URL jika ada, jika tidak ke halaman notifikasi
        $data = $notification->data;
        if (isset($data['action_url']) && $data['action_url']) {
            return redirect($data['action_url']);
        }

        return redirect()->route('notifications.index');
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }

    /**
     * Hapus notifikasi
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus');
    }

    /**
     * API: Get unread notifications count (untuk badge)
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * API: Get latest notifications (untuk dropdown)
     */
    public function destroySent($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $sent = \App\Models\SentNotification::findOrFail($id);
        $sent->delete();

        return redirect()->back()->with('success', 'Riwayat notifikasi berhasil dihapus');
    }
}
