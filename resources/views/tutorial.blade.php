<x-app-layout>
    @section('title', 'Tutorial | MATAJALAN_OS')
    <x-slot name="header">
        {{ __('SYSTEM_MANUAL') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <!-- Sidebar Navigation -->
                <div class="lg:col-span-1">
                    <div class="bg-slate-900 border border-slate-800 rounded-lg p-6 sticky top-24">
                        <h3 class="font-mono font-bold text-cyan-500 mb-4 uppercase text-sm tracking-wider">
                            <i data-lucide="book-open" class="inline-block w-4 h-4 mr-2"></i>Table of Contents
                        </h3>
                        <nav class="space-y-2 font-mono text-xs">
                            <a href="#introduction" class="block text-slate-400 hover:text-cyan-400 hover:translate-x-1 transition-all">01. INTRODUCTION</a>
                            <a href="#search" class="block text-slate-400 hover:text-cyan-400 hover:translate-x-1 transition-all">02. SEARCHING VEHICLES</a>
                            <a href="#reporting" class="block text-slate-400 hover:text-cyan-400 hover:translate-x-1 transition-all">03. SUBMITTING REPORTS</a>
                            <a href="#threat-levels" class="block text-slate-400 hover:text-cyan-400 hover:translate-x-1 transition-all">04. THREAT LEVELS</a>
                            <a href="#kyc-system" class="block text-slate-400 hover:text-cyan-400 hover:translate-x-1 transition-all">05. KYC & ROLES</a>
                            <a href="#registration" class="block text-slate-400 hover:text-cyan-400 hover:translate-x-1 transition-all">06. VEHICLE REGISTRATION</a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-12">
                    
                    <!-- Section 01: Introduction -->
                    <section id="introduction" class="scroll-mt-24">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-3xl font-mono font-bold text-slate-700">01</span>
                            <h2 class="text-2xl font-bold text-slate-100 font-mono">INTRODUCTION TO MATAJALAN_OS</h2>
                        </div>
                        <div class="bg-slate-900 border border-slate-800 rounded-lg p-6 shadow-lg">
                            <p class="text-slate-400 mb-4 leading-relaxed">
                                <strong class="text-cyan-400">MATAJALAN_OS</strong> is a decentralized vehicle surveillance and reputation system. 
                                It allows citizens to track, report, and verify vehicle behavior on public roads. 
                                By aggregating user reports, we build a comprehensive safety profile for every vehicle in the network.
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                                <div class="bg-slate-950 p-4 border border-slate-800 rounded">
                                    <i data-lucide="eye" class="w-8 h-8 text-cyan-500 mb-2"></i>
                                    <h4 class="font-mono font-bold text-slate-200 text-sm mb-1">OBSERVE</h4>
                                    <p class="text-xs text-slate-500">Monitor vehicle behavior in real-time.</p>
                                </div>
                                <div class="bg-slate-950 p-4 border border-slate-800 rounded">
                                    <i data-lucide="file-warning" class="w-8 h-8 text-amber-500 mb-2"></i>
                                    <h4 class="font-mono font-bold text-slate-200 text-sm mb-1">REPORT</h4>
                                    <p class="text-xs text-slate-500">Submit evidence of violations or safety risks.</p>
                                </div>
                                <div class="bg-slate-950 p-4 border border-slate-800 rounded">
                                    <i data-lucide="shield-check" class="w-8 h-8 text-emerald-500 mb-2"></i>
                                    <h4 class="font-mono font-bold text-slate-200 text-sm mb-1">VERIFY</h4>
                                    <p class="text-xs text-slate-500">Build trust through verified data.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Section 02: Searching -->
                    <section id="search" class="scroll-mt-24">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-3xl font-mono font-bold text-slate-700">02</span>
                            <h2 class="text-2xl font-bold text-slate-100 font-mono">SEARCHING VEHICLES</h2>
                        </div>
                        <div class="bg-slate-900 border border-slate-800 rounded-lg p-6 shadow-lg">
                            <p class="text-slate-400 mb-4">
                                You can search for any vehicle using its license plate number. The system supports fuzzy matching, so you don't need to worry about exact spacing.
                            </p>
                            <div class="bg-slate-950 p-4 rounded border border-slate-800 font-mono text-sm text-slate-300 mb-4">
                                <span class="text-cyan-500">Example Inputs:</span><br>
                                "B 1234 XYZ" <span class="text-slate-600">-> Valid</span><br>
                                "B1234XYZ" <span class="text-slate-600">-> Valid (Auto-normalized)</span><br>
                                "b 1234 xyz" <span class="text-slate-600">-> Valid (Case insensitive)</span>
                            </div>
                            <p class="text-slate-400 text-sm">
                                If a vehicle exists in our database, you will be taken to its <strong>Profile Page</strong>. 
                                If not, you will see a basic profile where you can be the first to submit a report.
                            </p>
                        </div>
                    </section>

                    <!-- Section 03: Reporting -->
                    <section id="reporting" class="scroll-mt-24">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-3xl font-mono font-bold text-slate-700">03</span>
                            <h2 class="text-2xl font-bold text-slate-100 font-mono">SUBMITTING REPORTS</h2>
                        </div>
                        <div class="bg-slate-900 border border-slate-800 rounded-lg p-6 shadow-lg">
                            <div class="flex items-start gap-4">
                                <div class="flex-1">
                                    <p class="text-slate-400 mb-4">
                                        Found a vehicle behaving dangerously? Or maybe a very safe driver? Submit a report to update their score.
                                    </p>
                                    <ol class="list-decimal list-inside space-y-2 text-slate-300 font-mono text-sm mb-4">
                                        <li>Go to the Vehicle's Profile Page.</li>
                                        <li>Click the <span class="text-cyan-400">FILE REPORT</span> button.</li>
                                        <li>Select a <strong>Threat Level</strong> (1 = Dangerous, 5 = Safe).</li>
                                        <li>Add <strong>Behavioral Tags</strong> (e.g., SPEEDING, AGGRESSIVE).</li>
                                        <li>Upload <strong>Evidence</strong> (Photos/Videos) - <em>Recommended</em>.</li>
                                        <li>Submit the report.</li>
                                    </ol>
                                    <div class="bg-amber-900/20 border border-amber-500/30 p-3 rounded text-amber-400 text-xs font-mono">
                                        <i data-lucide="alert-triangle" class="inline-block w-4 h-4 mr-1 align-text-bottom"></i>
                                        Note: All reports are subject to verification by Admins before affecting the public score.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Section 04: Threat Levels -->
                    <section id="threat-levels" class="scroll-mt-24">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-3xl font-mono font-bold text-slate-700">04</span>
                            <h2 class="text-2xl font-bold text-slate-100 font-mono">THREAT LEVELS</h2>
                        </div>
                        <div class="bg-slate-900 border border-slate-800 rounded-lg p-6 shadow-lg">
                            <p class="text-slate-400 mb-6">
                                The system categorizes vehicles into three threat levels based on their aggregate rating score (0-5).
                            </p>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4 p-3 bg-red-900/10 border border-red-500/30 rounded">
                                    <div class="w-12 h-12 rounded bg-red-500/20 flex items-center justify-center text-red-500 font-bold font-mono">
                                        HIGH
                                    </div>
                                    <div>
                                        <h4 class="text-red-400 font-bold font-mono text-sm">CRITICAL THREAT (Score < 2.5)</h4>
                                        <p class="text-slate-500 text-xs">Vehicles with frequent dangerous reports. Exercise extreme caution.</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 p-3 bg-amber-900/10 border border-amber-500/30 rounded">
                                    <div class="w-12 h-12 rounded bg-amber-500/20 flex items-center justify-center text-amber-500 font-bold font-mono">
                                        MED
                                    </div>
                                    <div>
                                        <h4 class="text-amber-400 font-bold font-mono text-sm">MODERATE THREAT (Score 2.5 - 4.0)</h4>
                                        <p class="text-slate-500 text-xs">Vehicles with mixed reviews or minor infractions.</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 p-3 bg-emerald-900/10 border border-emerald-500/30 rounded">
                                    <div class="w-12 h-12 rounded bg-emerald-500/20 flex items-center justify-center text-emerald-500 font-bold font-mono">
                                        LOW
                                    </div>
                                    <div>
                                        <h4 class="text-emerald-400 font-bold font-mono text-sm">SAFE / COMPLIANT (Score > 4.0)</h4>
                                        <p class="text-slate-500 text-xs">Vehicles with positive history or no negative reports.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Section 05: KYC -->
                    <section id="kyc-system" class="scroll-mt-24">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-3xl font-mono font-bold text-slate-700">05</span>
                            <h2 class="text-2xl font-bold text-slate-100 font-mono">KYC & USER ROLES</h2>
                        </div>
                        <div class="bg-slate-900 border border-slate-800 rounded-lg p-6 shadow-lg">
                            <p class="text-slate-400 mb-4">
                                To ensure data integrity, MATAJALAN_OS uses a tiered access system.
                            </p>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse font-mono text-xs">
                                    <thead>
                                        <tr class="border-b border-slate-800 text-slate-500">
                                            <th class="py-2">ROLE</th>
                                            <th class="py-2">REQUIREMENTS</th>
                                            <th class="py-2">PRIVILEGES</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-slate-300">
                                        <tr class="border-b border-slate-800/50">
                                            <td class="py-3 text-slate-400">GUEST</td>
                                            <td class="py-3">None</td>
                                            <td class="py-3">View public vehicle profiles (limited)</td>
                                        </tr>
                                        <tr class="border-b border-slate-800/50">
                                            <td class="py-3 text-cyan-400">REGISTERED</td>
                                            <td class="py-3">Email Verification</td>
                                            <td class="py-3">Submit reports, Register own vehicles</td>
                                        </tr>
                                        <tr class="border-b border-slate-800/50">
                                            <td class="py-3 text-emerald-400">TIER 1 (VERIFIED)</td>
                                            <td class="py-3">KYC (ID Card Upload)</td>
                                            <td class="py-3">View detailed Driver Info & Specs, Higher trust score</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('kyc.index') }}" class="inline-flex items-center text-cyan-500 hover:text-cyan-400 font-mono text-xs font-bold uppercase tracking-wider">
                                    Start Verification Process <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                                </a>
                            </div>
                        </div>
                    </section>

                    <!-- Section 06: Registration -->
                    <section id="registration" class="scroll-mt-24">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-3xl font-mono font-bold text-slate-700">06</span>
                            <h2 class="text-2xl font-bold text-slate-100 font-mono">REGISTERING YOUR VEHICLE</h2>
                        </div>
                        <div class="bg-slate-900 border border-slate-800 rounded-lg p-6 shadow-lg">
                            <p class="text-slate-400 mb-4">
                                Own a vehicle? Register it to manage its reputation and add authorized drivers.
                            </p>
                            <ul class="list-disc list-inside space-y-2 text-slate-300 font-mono text-sm">
                                <li>Navigate to <strong>My Vehicles</strong>.</li>
                                <li>Click <strong>Add Vehicle</strong>.</li>
                                <li>Enter the Plate Number and VIN.</li>
                                <li>Once registered, you can view all reports made against your vehicle.</li>
                            </ul>
                        </div>
                    </section>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
