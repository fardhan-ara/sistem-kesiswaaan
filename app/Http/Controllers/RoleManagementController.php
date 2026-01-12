<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleManagementController extends Controller
{
    public function index()
    {
        $dualRoleUsers = User::where('allow_dual_role', true)
            ->with(['dualRoleApprovedBy'])
            ->get();
        
        // Get users for dual role: exclude those who already have dual role
        $users = User::whereIn('role', ['guru', 'kesiswaan', 'bk'])
            ->where('status', 'approved')
            ->where('allow_dual_role', false)
            ->get();
        
        $availableRoles = ['kesiswaan', 'guru', 'bk'];
            
        return view('admin.role-management.index', compact('dualRoleUsers', 'users', 'availableRoles'));
    }

    // Dual Role Methods
    public function assignDualRole(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'additional_roles' => 'required|array|min:1',
            'additional_roles.*' => 'in:kesiswaan,guru,bk'
        ]);

        $user = User::findOrFail($validated['user_id']);
        
        // Filter out primary role from additional roles
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

        return back()->with('success', "Dual role berhasil diberikan kepada {$user->nama}!");
    }

    public function removeDualRole($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'allow_dual_role' => false,
            'additional_roles' => null,
            'dual_role_approved_by' => null,
            'dual_role_approved_at' => null
        ]);

        return back()->with('success', "Dual role {$user->nama} berhasil dicabut!");
    }
    
    public function editDualRole($id)
    {
        $user = User::with(['dualRoleApprovedBy'])->findOrFail($id);
        $availableRoles = ['kesiswaan', 'guru', 'bk'];
        
        return view('admin.role-management.edit-dual-role', compact('user', 'availableRoles'));
    }
    
    public function updateDualRole(Request $request, $id)
    {
        $validated = $request->validate([
            'additional_roles' => 'required|array|min:1',
            'additional_roles.*' => 'in:kesiswaan,guru,bk'
        ]);

        $user = User::findOrFail($id);
        $additionalRoles = array_diff($validated['additional_roles'], [$user->role]);
        
        if (empty($additionalRoles)) {
            return back()->withErrors(['additional_roles' => 'Role tambahan tidak boleh sama dengan primary role.']);
        }

        $user->update([
            'additional_roles' => array_values($additionalRoles),
            'dual_role_approved_by' => Auth::id(),
            'dual_role_approved_at' => now()
        ]);

        return redirect()->route('admin.role-management')
            ->with('success', "Dual role {$user->nama} berhasil diupdate!");
    }
}