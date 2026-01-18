@extends('layouts.admin')

@section('header', 'ADD_NEW_OPERATIVE')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-slate-900 border border-slate-800 rounded p-6">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-xs font-mono font-bold text-cyan-700 uppercase mb-2">Operative Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required 
                        class="w-full bg-slate-950 border border-slate-700 rounded p-2.5 text-slate-200 focus:outline-none focus:border-cyan-500 font-mono text-sm">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-mono font-bold text-cyan-700 uppercase mb-2">Email Frequency</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                        class="w-full bg-slate-950 border border-slate-700 rounded p-2.5 text-slate-200 focus:outline-none focus:border-cyan-500 font-mono text-sm">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Role -->
            <div>
                <label class="block text-xs font-mono font-bold text-cyan-700 uppercase mb-2">Clearance Level (Role)</label>
                <select name="role" required class="w-full bg-slate-950 border border-slate-700 rounded p-2.5 text-slate-200 focus:outline-none focus:border-cyan-500 font-mono text-sm uppercase">
                    <option value="user">USER (TIER 2)</option>
                    <option value="tier_1">TIER 1 (VERIFIED)</option>
                    <option value="admin">ADMIN</option>
                    <option value="superadmin">SUPERADMIN</option>
                </select>
                @error('role') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Password -->
                <div>
                    <label class="block text-xs font-mono font-bold text-cyan-700 uppercase mb-2">Access Key (Password)</label>
                    <input type="password" name="password" required 
                        class="w-full bg-slate-950 border border-slate-700 rounded p-2.5 text-slate-200 focus:outline-none focus:border-cyan-500 font-mono text-sm">
                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs font-mono font-bold text-cyan-700 uppercase mb-2">Confirm Key</label>
                    <input type="password" name="password_confirmation" required 
                        class="w-full bg-slate-950 border border-slate-700 rounded p-2.5 text-slate-200 focus:outline-none focus:border-cyan-500 font-mono text-sm">
                </div>
            </div>

            <div class="pt-6 border-t border-slate-800 flex items-center justify-end gap-4">
                <a href="{{ route('admin.users.index') }}" class="text-slate-500 hover:text-slate-300 text-sm font-mono uppercase">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-bold font-mono rounded transition-colors uppercase">
                    Initialize Operative
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
