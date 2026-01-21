@extends('layouts.admin')

@section('title', 'User Management | MATAJALAN_OS')

@section('header', 'USER_MANAGEMENT')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <!-- Search & Filter -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex-1 w-full flex gap-2">
        <div class="relative flex-1 max-w-md">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." 
                class="w-full pl-9 pr-4 py-2 bg-slate-900 border border-slate-700 rounded text-sm text-slate-300 focus:outline-none focus:border-cyan-500 font-mono">
        </div>
        <select name="role" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700 rounded text-sm text-slate-300 px-3 font-mono focus:outline-none focus:border-cyan-500">
            <option value="all">ALL_ROLES</option>
            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>USER</option>
            <option value="tier_1" {{ request('role') == 'tier_1' ? 'selected' : '' }}>TIER_1</option>
            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>ADMIN</option>
            <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>SUPERADMIN</option>
        </select>
    </form>

    <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2 px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-bold font-mono rounded transition-colors uppercase">
        <i data-lucide="user-plus" class="w-4 h-4"></i> Add Operative
    </a>
</div>

<div class="bg-slate-900 border border-slate-800 rounded overflow-hidden">
    <table class="w-full text-left text-sm text-slate-400">
        <thead class="bg-slate-950 text-xs uppercase font-mono text-slate-500">
            <tr>
                <th class="px-6 py-3 border-b border-slate-800">Identity</th>
                <th class="px-6 py-3 border-b border-slate-800">Role</th>
                <th class="px-6 py-3 border-b border-slate-800">KYC Status</th>
                <th class="px-6 py-3 border-b border-slate-800">Joined</th>
                <th class="px-6 py-3 border-b border-slate-800 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-800">
            @forelse($users as $user)
            <tr class="hover:bg-slate-800/50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-xs text-slate-300">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                        <div>
                            <div class="font-bold text-slate-200">{{ $user->name }}</div>
                            <div class="text-xs text-slate-500 font-mono">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 font-mono text-xs uppercase">
                    <span class="px-2 py-1 rounded border 
                        {{ $user->role === 'superadmin' ? 'border-purple-500/50 text-purple-400 bg-purple-950/20' : 
                          ($user->role === 'admin' ? 'border-cyan-500/50 text-cyan-400 bg-cyan-950/20' : 
                          ($user->role === 'tier_1' ? 'border-emerald-500/50 text-emerald-400 bg-emerald-950/20' : 'border-slate-700 text-slate-400')) }}">
                        {{ $user->role }}
                    </span>
                </td>
                <td class="px-6 py-4 font-mono text-xs uppercase">
                    @if($user->kyc_status === 'approved')
                        <span class="text-emerald-500 flex items-center gap-1"><i data-lucide="check" class="w-3 h-3"></i> VERIFIED</span>
                    @elseif($user->kyc_status === 'pending')
                        <span class="text-amber-500 flex items-center gap-1"><i data-lucide="clock" class="w-3 h-3"></i> PENDING</span>
                    @elseif($user->kyc_status === 'rejected')
                        <span class="text-red-500 flex items-center gap-1"><i data-lucide="x" class="w-3 h-3"></i> REJECTED</span>
                    @else
                        <span class="text-slate-600">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 font-mono text-xs">
                    {{ $user->created_at->format('Y-m-d') }}
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="p-1.5 text-slate-400 hover:text-cyan-400 transition-colors" title="Edit">
                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                        </a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Terminate this operative account? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-red-400 transition-colors" title="Delete">
                                <i data-lucide="trash" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-mono italic">
                    NO OPERATIVES FOUND MATCHING CRITERIA
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t border-slate-800">
        {{ $users->links() }}
    </div>
</div>
@endsection
