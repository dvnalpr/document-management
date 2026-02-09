<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $divisions = Division::all();

        $query = User::with(['division', 'roles']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        $perPage = $request->input('per_page', 10);
        $users = $query->latest()->paginate($perPage)->withQueryString();

        // Stats
        $stats = [
            ['label' => 'Total Users', 'value' => User::count()],
            ['label' => 'Active Users', 'value' => User::where('is_active', true)->count()],
            ['label' => 'Deactivated', 'value' => User::where('is_active', false)->count()],
        ];

        return view('users.index', compact('users', 'stats', 'divisions', 'perPage'));
    }

    public function store(Request $request)
    {
        $divisionId = ($request->role === 'Admin') ? null : $request->division_id;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'division_id' => 'required|exists:divisions,id',
            'role' => 'required',
        ]);

        $finalDivisionId = ($request->role === 'Admin') ? null : $request->division_id;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'division_id' => $finalDivisionId,
            'is_active' => true,
        ]);

        $user->assignRole($request->role);

        AuditLog::create([
            'user_id' => Auth::id(),
            'activity' => 'Create User',
            'target' => $user->name,
            'target_type' => 'User',
        ]);

        return redirect()->back()->with('success', 'User created successfully');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'division_id' => 'required|exists:divisions,id',
            'role' => 'required',
        ]);

        $finalDivisionId = ($request->role === 'Admin') ? null : $request->division_id;

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'is_active' => $request->status === 'Active',
            'division_id' => $finalDivisionId,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'User deleted');
    }
}
