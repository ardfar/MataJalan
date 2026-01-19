<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Rating Moderation') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.ratings.index', ['status' => 'pending']) }}" class="px-4 py-2 text-sm rounded {{ $status === 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">Pending</a>
                <a href="{{ route('admin.ratings.index', ['status' => 'approved']) }}" class="px-4 py-2 text-sm rounded {{ $status === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }}">Approved</a>
                <a href="{{ route('admin.ratings.index', ['status' => 'rejected']) }}" class="px-4 py-2 text-sm rounded {{ $status === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700' }}">Rejected</a>
                <a href="{{ route('admin.ratings.index', ['status' => 'all']) }}" class="px-4 py-2 text-sm rounded {{ $status === 'all' ? 'bg-gray-600 text-white' : 'bg-gray-200 text-gray-700' }}">All</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Content</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($ratings as $rating)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $rating->created_at->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $rating->user->name ?? 'Unknown' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <a href="{{ route('vehicle.show', $rating->vehicle->uuid) }}" class="text-blue-600 hover:underline" target="_blank">
                                                {{ $rating->vehicle->plate_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex items-center">
                                                <span class="font-bold mr-1">{{ $rating->rating }}</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs">
                                            <p class="truncate mb-1">"{{ $rating->comment }}"</p>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($rating->tags ?? [] as $tag)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ $tag }}
                                                    </span>
                                                @endforeach
                                            </div>
                                            @if($rating->media->count() > 0)
                                                <div class="mt-2 text-xs text-blue-600">
                                                    {{ $rating->media->count() }} media attachment(s)
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $rating->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $rating->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $rating->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ ucfirst($rating->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($rating->status === 'pending')
                                                <div class="flex space-x-2">
                                                    <form action="{{ route('admin.ratings.approve', $rating) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                                    </form>
                                                    
                                                    <button onclick="openRejectModal({{ $rating->id }})" class="text-red-600 hover:text-red-900">Reject</button>
                                                </div>
                                            @else
                                                <span class="text-gray-400">Processed</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No ratings found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $ratings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full" x-data="{ open: false }">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Reject Rating</h3>
                <form id="rejectForm" method="POST" class="mt-2 text-left">
                    @csrf
                    @method('PATCH')
                    <div class="mt-2">
                        <label class="block text-sm font-medium text-gray-700">Reason</label>
                        <textarea name="rejection_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required></textarea>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Confirm Rejection
                        </button>
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openRejectModal(ratingId) {
            const modal = document.getElementById('rejectModal');
            const form = document.getElementById('rejectForm');
            form.action = `/admin/ratings/${ratingId}/reject`;
            modal.classList.remove('hidden');
        }
    </script>
</x-app-layout>
