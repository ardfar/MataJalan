<x-app-layout>
    <x-slot name="header">
        {{ __('KYC VERIFICATION') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-900 border border-slate-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <!-- Decorative Corner -->
                <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-cyan-500/10 to-transparent"></div>
                <div class="absolute top-0 right-0 w-4 h-4 border-t border-r border-cyan-500/50"></div>

                <div class="p-6 text-slate-300 font-mono">
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-emerald-400 border border-emerald-900/50 bg-emerald-900/20 p-3 rounded">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($user->kyc_status === 'approved')
                        <div class="flex items-center gap-3 text-emerald-400 font-bold text-lg mb-2">
                            <i data-lucide="check-circle" class="w-6 h-6"></i>
                            IDENTITY_VERIFIED
                        </div>
                        <p class="text-slate-500 text-sm">Clearance Level: UNRESTRICTED. You have full access to the surveillance grid.</p>
                    @elseif ($user->kyc_status === 'pending')
                        <div class="flex items-center gap-3 text-amber-400 font-bold text-lg mb-2">
                            <i data-lucide="clock" class="w-6 h-6"></i>
                            VERIFICATION_PENDING
                        </div>
                        <p class="text-slate-500 text-sm">Documents under review by central command. Estimated wait time: 24-48 hours.</p>
                    @else
                        @if ($user->kyc_status === 'rejected')
                            <div class="mb-6 border border-red-900/50 bg-red-900/20 p-4 rounded text-red-400">
                                <div class="font-bold flex items-center gap-2 mb-1">
                                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                                    SUBMISSION_REJECTED
                                </div>
                                <p class="text-xs">Your previous documents were invalid. Please resubmit.</p>
                            </div>
                        @endif

                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-slate-100 flex items-center gap-2">
                                <i data-lucide="file-up" class="w-5 h-5 text-cyan-500"></i>
                                UPLOAD_CREDENTIALS
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">Submit valid government ID for clearance.</p>
                        </div>
                        
                        <form method="POST" action="{{ route('kyc.store') }}" enctype="multipart/form-data" class="space-y-6 max-w-xl">
                            @csrf

                            <div>
                                <x-input-label for="document_type" :value="__('DOCUMENT_TYPE')" />
                                <select id="document_type" name="document_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-slate-700 focus:outline-none focus:ring-cyan-500 focus:border-cyan-500 sm:text-sm rounded-none bg-slate-950 text-slate-300 font-mono">
                                    <option value="passport">PASSPORT</option>
                                    <option value="id_card">NATIONAL_ID_CARD</option>
                                    <option value="driving_license">DRIVING_LICENSE</option>
                                </select>
                                <x-input-error :messages="$errors->get('document_type')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="document_file" :value="__('DOCUMENT_FILE (JPG, PNG, PDF)')" />
                                <input id="document_file" name="document_file" type="file" class="mt-1 block w-full text-sm text-slate-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-none file:border-0
                                file:text-xs file:font-bold file:font-mono
                                file:bg-slate-800 file:text-cyan-400
                                hover:file:bg-slate-700
                                border border-slate-700 bg-slate-950 cursor-pointer focus:outline-none" required />
                                <x-input-error :messages="$errors->get('document_file')" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-4 pt-4 border-t border-slate-800">
                                <x-primary-button>
                                    <i data-lucide="upload" class="w-4 h-4 mr-2"></i>
                                    {{ __('TRANSMIT_DATA') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
