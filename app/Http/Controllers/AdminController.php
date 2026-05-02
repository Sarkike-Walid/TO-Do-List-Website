<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('updated_at', '>=', now()->subDays(7))->count();
        $totalTasks = DB::table('tasks')->count();
        $completedTasks = DB::table('tasks')->where('status', 'done')->count();
        $pendingTasks = $totalTasks - $completedTasks;

        // Optionally fetch data for charts, e.g., task creation over last 7 days
        $taskStats = []; // Simplified for now

        return view('admin.dashboard', compact('totalUsers', 'activeUsers', 'totalTasks', 'completedTasks', 'pendingTasks', 'taskStats'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $users = $query->paginate(15)->withQueryString();
        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|in:user,admin']);
        
        // Prevent modifying the super admin
        if ($user->email === 'admin@lumido.com') {
            return redirect()->back()->with('error', 'The primary administrator role cannot be changed.');
        }

        // Prevent admin from demoting themselves (if they are the only admin, etc.)
        if ($user->id === auth()->id() && $request->role === 'user') {
            return redirect()->back()->with('error', 'You cannot demote yourself.');
        }

        $user->update(['role' => $request->role]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Role Updated',
            'description' => "Updated role for user {$user->email} to {$request->role}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'User role updated successfully.');
    }

    public function deleteUser(Request $request, User $user)
    {
        // Prevent deleting the super admin
        if ($user->email === 'admin@lumido.com') {
            return redirect()->back()->with('error', 'The primary administrator account cannot be deleted.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $userEmail = $user->email;
        $user->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'User Deleted',
            'description' => "Deleted user account {$userEmail}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function tasks(Request $request)
    {
        $query = DB::table('tasks')
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->select('tasks.*', 'users.name as user_name', 'users.email as user_email');

        if ($request->filled('status')) {
            $query->where('tasks.status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tasks.title', 'like', "%{$search}%")
                  ->orWhere('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        $tasks = $query->orderBy('tasks.created_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.tasks', compact('tasks'));
    }

    public function deleteTask(Request $request, $id)
    {
        $task = DB::table('tasks')->where('id', $id)->first();
        if ($task) {
            DB::table('tasks')->where('id', $id)->delete();
            
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'Task Deleted',
                'description' => "Deleted task ID {$id} ({$task->title})",
                'ip_address' => $request->ip(),
            ]);
        }

        return redirect()->back()->with('success', 'Task deleted successfully.');
    }

    public function logs(Request $request)
    {
        $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.logs', compact('logs'));
    }

    public function settings()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except('_token');
        
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'Settings Updated',
            'description' => "Updated system settings",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
