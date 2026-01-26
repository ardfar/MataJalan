@extends('layouts.app')

@section('title', 'My Vehicles | MATAJALAN_OS')

@section('header', 'MY_VEHICLES')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-mono font-bold text-slate-100 uppercase tracking-tight">
                <span class="text-cyan-500">>></span> REGISTERED_FLEET
            </h2>
            <a href="{{ route('my-vehicles.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-cyan-600 border border-cyan-500 text-white font-mono font-bold text-xs uppercase tracking-widest hover:bg-cyan-500 transition-all shadow-[0_0_15px_rgba(6,182,212,0.3)]">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                ADD_MY_VEHICLE
            </a>
        </div>

        <div class="bg-slate-900 overflow-hidden shadow-sm sm:rounded-lg border border-slate-800">
            <div class="p-6">
                @if($myVehicles->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-xs font-mono text-slate-500 uppercase">
                                    <th class="py-3 px-4">Plate Number</th>
                                    <th class="py-3 px-4">Vehicle Model</th>
                                    <th class="py-3 px-4">Driver Name</th>
                                    <th class="py-3 px-4">Role / Usage</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm font-mono text-slate-300">
                                @foreach($myVehicles as $vehicle)
                                    @php
                                        // Get the primary driver info (either the owner themselves or the first approved/latest one)
                                        $primaryDriver = $vehicle->vehicleUsers->first(); 
                                        $driverName = $primaryDriver ? $primaryDriver->driver_name : 'N/A';
                                        $roleType = $primaryDriver ? $primaryDriver->role_type : 'OWNER';
                                        $status = $primaryDriver ? $primaryDriver->status : 'approved'; // If owned but no driver record (shouldn't happen with new logic), assume approved/active
                                    @endphp
                                    <tr class="border-b border-slate-800/50 hover:bg-slate-800/30 transition-colors">
                                        <td class="py-4 px-4 font-bold text-white">{{ $vehicle->plate_number }}</td>
                                        <td class="py-4 px-4">{{ $vehicle->make }} {{ $vehicle->model }}</td>
                                        <td class="py-4 px-4">{{ $driverName }}</td>
                                        <td class="py-4 px-4">
                                            <span class="px-2 py-1 bg-slate-800 text-slate-400 text-[10px] rounded border border-slate-700 uppercase">
                                                {{ str_replace('_', ' ', $roleType) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            @if($status === 'approved')
                                                <span class="text-emerald-400 flex items-center gap-1.5">
                                                    <i data-lucide="check-circle" class="w-3 h-3"></i> APPROVED
                                                </span>
                                            @elseif($status === 'pending')
                                                <span class="text-amber-400 flex items-center gap-1.5">
                                                    <i data-lucide="clock" class="w-3 h-3"></i> PENDING
                                                </span>
                                            @else
                                                <span class="text-red-400 flex items-center gap-1.5">
                                                    <i data-lucide="x-circle" class="w-3 h-3"></i> REJECTED
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            <a href="{{ route('vehicle.show', $vehicle->uuid) }}" class="text-cyan-400 hover:text-cyan-300 text-xs uppercase hover:underline">
                                                VIEW_PROFILE
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $myVehicles->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-700">
                            <i data-lucide="car" class="w-8 h-8 text-slate-600"></i>
                        </div>
                        <h3 class="text-lg font-mono font-bold text-slate-300 mb-2">NO_VEHICLES_FOUND</h3>
                        <p class="text-sm text-slate-500 font-mono mb-6 max-w-md mx-auto">
                            You haven't registered any driver information for vehicles yet. Start by adding your first vehicle.
                        </p>
                        <a href="{{ route('my-vehicles.create') }}" class="inline-flex items-center justify-center px-6 py-2 bg-slate-800 border border-slate-700 text-cyan-400 font-mono font-bold text-xs uppercase tracking-widest hover:bg-slate-700 hover:border-cyan-500 transition-all">
                            REGISTER_FIRST_VEHICLE
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
