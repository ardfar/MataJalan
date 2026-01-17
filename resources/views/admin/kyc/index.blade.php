<x-app-layout>
    <x-slot name="header">
        {{ __('PENDING_KYC_REQUESTS') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900 border border-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-300">
                    @if (session('status'))
                        <div class="mb-4 font-mono text-sm text-emerald-400 bg-emerald-900/20 border border-emerald-900/50 p-2 rounded">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($users->isEmpty())
                        <div class="text-center py-8 text-slate-500 font-mono border border-dashed border-slate-800 rounded">
                            NO PENDING REQUESTS IN QUEUE
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-800">
                                <thead class="bg-slate-950">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold font-mono text-slate-500 uppercase tracking-wider">AGENT_NAME</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold font-mono text-slate-500 uppercase tracking-wider">EMAIL_ADDRESS</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold font-mono text-slate-500 uppercase tracking-wider">SUBMISSION_TIME</th>
                                        <th class="px-6 py-3 text-left text-xs font-bold font-mono text-slate-500 uppercase tracking-wider">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-slate-900 divide-y divide-slate-800 font-mono text-sm">
                                    @foreach ($users as $user)
                                        <tr class="hover:bg-slate-800/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-slate-300">{{ $user->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-slate-400">{{ $user->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-cyan-500">{{ $user->kyc_submitted_at ? $user->kyc_submitted_at->diffForHumans() : 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('admin.kyc.show', $user) }}" class="inline-flex items-center text-cyan-400 hover:text-white transition-colors uppercase text-xs font-bold">
                                                    REVIEW_DATA <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
