@extends('layouts.admin')

@section('title', 'Specification Library | MATAJALAN_OS')

@section('header', 'SPECIFICATION_LIBRARY')

@section('content')
<div x-data="{ 
    selected: [], 
    allSelected: false,
    toggleAll() {
        this.allSelected = !this.allSelected;
        if (this.allSelected) {
            this.selected = {{ json_encode($specs->pluck('id')) }};
        } else {
            this.selected = [];
        }
    }
}">
    <!-- Top Actions -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <!-- Search & Filter -->
        <form action="{{ route('admin.vehicle-specs.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="pl-9 pr-4 py-2 bg-slate-900 border border-slate-700 rounded text-xs font-mono text-slate-200 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 w-full sm:w-64"
                    placeholder="Search Brand, Model, Variant...">
            </div>
            
            <select name="category" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700 rounded text-xs font-mono text-slate-200 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 px-3 py-2">
                <option value="">ALL CATEGORIES</option>
                @foreach(['MPV', 'SUV', 'LCGC', 'Sedan', 'Hatchback', 'EV', 'Commercial'] as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <select name="fuel_type" onchange="this.form.submit()" class="bg-slate-900 border border-slate-700 rounded text-xs font-mono text-slate-200 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 px-3 py-2">
                <option value="">ALL FUEL TYPES</option>
                @foreach(['Bensin', 'Diesel', 'Hybrid', 'Mild Hybrid', 'Listrik'] as $fuel)
                    <option value="{{ $fuel }}" {{ request('fuel_type') == $fuel ? 'selected' : '' }}>{{ $fuel }}</option>
                @endforeach
            </select>
        </form>

        <!-- Right Actions -->
        <div class="flex items-center gap-2">
            <!-- Bulk Delete -->
            <form action="{{ route('admin.vehicle-specs.bulk-delete') }}" method="POST" 
                x-show="selected.length > 0" 
                class="inline-block"
                onsubmit="return confirm('Are you sure you want to delete selected items?')">
                @csrf
                @method('DELETE')
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="flex items-center gap-2 px-3 py-2 bg-red-900/50 border border-red-500/50 text-red-400 font-mono font-bold text-xs uppercase hover:bg-red-900/80 transition-all rounded">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                    DELETE (<span x-text="selected.length"></span>)
                </button>
            </form>

            <a href="{{ route('admin.vehicle-specs.create') }}" class="flex items-center gap-2 px-4 py-2 bg-cyan-600 border border-cyan-500 text-white font-mono font-bold text-xs uppercase tracking-widest hover:bg-cyan-500 transition-all shadow-[0_0_15px_rgba(6,182,212,0.3)] rounded">
                <i data-lucide="plus" class="w-4 h-4"></i>
                ADD_NEW_SPEC
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-slate-900 border border-slate-800 rounded overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/50 text-xs font-mono text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-4 w-10">
                            <input type="checkbox" @click="toggleAll()" :checked="allSelected" class="rounded border-slate-700 bg-slate-900 text-cyan-500 focus:ring-offset-slate-900 focus:ring-cyan-500">
                        </th>
                        <th class="py-3 px-4">Brand</th>
                        <th class="py-3 px-4">Model</th>
                        <th class="py-3 px-4">Variant</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">Engine / Power</th>
                        <th class="py-3 px-4">Created At</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-mono text-slate-300 divide-y divide-slate-800/50">
                    @forelse($specs as $spec)
                        <tr class="hover:bg-slate-800/30 transition-colors group">
                            <td class="py-4 px-4">
                                <input type="checkbox" value="{{ $spec->id }}" x-model="selected" class="rounded border-slate-700 bg-slate-900 text-cyan-500 focus:ring-offset-slate-900 focus:ring-cyan-500">
                            </td>
                            <td class="py-4 px-4 font-bold text-white">{{ $spec->brand }}</td>
                            <td class="py-4 px-4">{{ $spec->model }}</td>
                            <td class="py-4 px-4">{{ $spec->variant }}</td>
                            <td class="py-4 px-4">
                                <span class="px-2 py-1 bg-slate-800 text-slate-400 text-[10px] rounded border border-slate-700 uppercase">
                                    {{ $spec->category }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                @if($spec->category === 'EV')
                                    <span class="text-emerald-400">{{ $spec->battery_kwh }} kWh</span> / {{ $spec->horsepower }} PS
                                @else
                                    {{ $spec->engine_cc }} cc / {{ $spec->horsepower }} PS
                                @endif
                            </td>
                            <td class="py-4 px-4 text-xs text-slate-500">
                                {{ $spec->created_at->format('Y-m-d') }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.vehicle-specs.edit', $spec) }}" class="p-1 text-cyan-400 hover:text-cyan-300 hover:bg-cyan-900/20 rounded transition-colors" title="Edit">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.vehicle-specs.destroy', $spec) }}" method="POST" onsubmit="return confirm('Delete this spec permanently?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-red-400 hover:text-red-300 hover:bg-red-900/20 rounded transition-colors" title="Delete">
                                            <i data-lucide="trash" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500 font-mono text-sm">
                                <i data-lucide="database" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                NO_DATA_FOUND
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-slate-950/50 border-t border-slate-800 px-4 py-3 flex items-center justify-between">
            <div class="text-xs text-slate-500 font-mono">
                Showing {{ $specs->firstItem() ?? 0 }} to {{ $specs->lastItem() ?? 0 }} of {{ $specs->total() }} results
            </div>
            <div>
                {{ $specs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
