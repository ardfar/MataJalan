<x-app-layout>
    <x-slot name="header">
        {{ __('REVIEW_KYC_DATA') }} // {{ $user->name }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900 border border-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-300 font-mono">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-bold text-slate-100 mb-4 border-b border-slate-800 pb-2">AGENT_DETAILS</h3>
                            <div class="space-y-3 text-sm">
                                <div>
                                    <span class="text-slate-500 block text-xs uppercase">FULL NAME</span>
                                    <span class="text-slate-200">{{ $user->name }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-xs uppercase">EMAIL ADDRESS</span>
                                    <span class="text-cyan-400">{{ $user->email }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-xs uppercase">SUBMISSION TIMESTAMP</span>
                                    <span class="text-slate-200">{{ $user->kyc_submitted_at }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 block text-xs uppercase">DOCUMENT TYPE</span>
                                    <span class="inline-flex px-2 py-0.5 rounded bg-slate-800 text-slate-300 text-xs border border-slate-700 mt-1 uppercase">
                                        {{ $user->kyc_data['document_type'] ?? 'UNKNOWN' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-100 mb-4 border-b border-slate-800 pb-2">EVIDENCE_FILE</h3>
                            @if(isset($user->kyc_data['document_path']))
                                <div class="bg-slate-950 border border-slate-800 p-6 rounded flex flex-col items-center justify-center gap-4">
                                    <i data-lucide="file-check" class="w-12 h-12 text-slate-600"></i>
                                    <div class="text-center">
                                        <p class="text-xs text-slate-500 mb-4">ENCRYPTED_FILE_DETECTED</p>
                                        <a href="{{ route('admin.kyc.download', $user) }}" class="inline-flex items-center px-4 py-2 bg-slate-800 border border-slate-700 rounded-sm font-bold text-xs text-white uppercase tracking-widest hover:bg-slate-700 hover:border-cyan-500 hover:text-cyan-400 transition ease-in-out duration-150">
                                            <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                                            DECRYPT_&_DOWNLOAD
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="bg-red-900/10 border border-red-900/30 p-4 rounded text-center">
                                    <p class="text-red-400 text-xs font-bold">ERROR: NO_DOCUMENT_FOUND</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-8 border-t border-slate-800 pt-6 flex gap-4 justify-end">
                        <form action="{{ route('admin.kyc.approve', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <x-primary-button class="bg-emerald-600 hover:bg-emerald-500 border-emerald-500">
                                <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                                AUTHORIZE_CLEARANCE
                            </x-primary-button>
                        </form>

                        <form action="{{ route('admin.kyc.reject', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <x-danger-button>
                                <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                                REJECT_SUBMISSION
                            </x-danger-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
