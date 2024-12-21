<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clock-in/out') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
            $time_tracking_list = $time_trackings->get();
            $time_tracking = $time_trackings->whereNull('clocked_out')->first();
            @endphp
            <div class="mb-4">
                <p class="mb-2">Name: {{ Auth::user()->firstname." ".Auth::user()->lastname }}</p>
                <p>Date: {{ date("Y-m-d"); }}</p>
            </div>
            <div style="display:inline-flex" class="mb-4 space-x-4">
                <form action="{{ route('clockin-out.store') }}" method="POST">
                    @csrf
                    <button type="submit" class="font-semibold text-white py-2 px-4 rounded {{ $time_tracking ? 'cursor-not-allowed bg-gray-500' : 'bg-indigo-700 hover:bg-indigo-500' }}" {{ $time_tracking ? 'disabled' : '' }} >clock-in</button>
                </form>
                <form action="{{ $time_tracking ? route('clockin-out.update', ['id' => $time_tracking->id]) : '' }}" method="POST">
                    @csrf
                    <button type="submit" class="font-semibold text-white py-2 px-4 rounded {{ $time_tracking ? 'bg-indigo-700 hover:bg-indigo-500' : 'cursor-not-allowed bg-gray-500' }}" {{ $time_tracking ? '' : 'disabled' }}>clock-out</button>
                </form>
            </div>
            @if ($time_tracking_list)
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-gray-700 bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                        <th scope="col" class="px-6 py-2">Work date</th>
                        <th scope="col" class="px-6 py-2">Clocked in</th>
                        <th scope="col" class="px-6 py-2">Clocked out</th>
                        <th scope="col" class="px-6 py-2">Worked hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($time_tracking_list as $time_tracking)
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-4 py-1">{{ $time_tracking->work_date }}</td>
                            <td class="px-4 py-1">{{ $time_tracking->clocked_in }}</td>
                            <td class="px-4 py-1">{{ $time_tracking->clocked_out }}</td>
                            <td class="px-4 py-1">{{ $time_tracking->total_hours }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
