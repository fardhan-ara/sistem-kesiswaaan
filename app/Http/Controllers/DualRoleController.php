<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DualRoleController extends Controller
{
    public function index()
    {
        $users = User::where('allow_dual_role', true)
            ->with(['dualRoleApprovedBy'])
            ->paginate(20);
            
        return view('admin.dual-roles.index', compact('users'));
    }

    public function create()
    {
        $users = User::whereIn('role', ['guru', 'kesiswaan'])
            ->where('status', 'approved')
            ->where('allow_dual_role', false)
            ->get();
            
        $availableRoles = ['kesiswaan', 'guru', 'verifikator'];
        
        return view('admin.dual-roles.create', compact('users', 'availableRoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'additional_roles' => 'required|array|min:1',
            'additional_roles.*' => 'in:kesiswaan,guru,verifikator'
        ]);

        $user = User::findOrFail($validated['user_id']);
        
        // Pastikan tidak ada duplikasi dengan primary role
        $additionalRoles = array_diff($validated['additional_roles'], [$user->role]);
        
        if (empty($additionalRoles)) {
            return back()->withErrors(['additional_roles' => 'Role tambahan tidak boleh sama dengan primary role.']);
        }

        $user->update([
            'allow_dual_role' => true,
            'additional_roles' => array_values($additionalRoles),
            'dual_role_approved_by' => Auth::id(),
            'dual_role_approved_at' => now()
        ]);

        return redirect()->route('admin.dual-roles.index')
            ->with('success', "Dual role berhasil diberikan kepada {$user->nama}!");
    }

    public function destroy(User $user)
    {
        $user->update([
            'allow_dual_role' => false,
            'additional_roles' => null,
            'dual_role_approved_by' => null,
            'dual_role_approved_at' => null
        ]);

        return redirect()->route('admin.dual-roles.index')
            ->with('success', "Dual role {$user->nama} berhasil dicabut!");
    }
}