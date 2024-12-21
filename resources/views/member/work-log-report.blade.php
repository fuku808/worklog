<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Work log report') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('work-log.report') }}" class="form" method="GET">
                <div class="mb-4">
                    <p class="mb-2">Name: {{ Auth::user()->firstname." ".Auth::user()->lastname }}</p>
                    <div>
                        From: <input name="date_from" type="date" class="search-input mx-2 rounded" value="{{ isset($request->date_from) ? $request->date_from : date("Y-m-01") }}"></input>
                        To: <input name="date_to" type="date" class="search-input mx-2 rounded" value="{{ isset($request->date_to) ? $request->date_to : date("Y-m-t") }}"></input>
                    </div>
                </div>
                @if ($work_logs)
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-gray-700 bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                            <th width="15%" scope="col" class="px-6 py-2">Work date</th>
                            <th width="3%"  scope="col" class="px-6 py-2">Hours</th>
                            <th scope="col" class="px-6 py-2">Activity</th>
                            <th scope="col" class="px-6 py-2">Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($work_logs as $work_log)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-4 py-1">{{ $work_log->work_date }}</td>
                                <td class="px-4 py-1">{{ $work_log->hours }}</td>
                                <td class="px-4 py-1">{{ $work_log->activity }}</td>
                                <td class="px-4 py-1">{{ $work_log->note }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </form>
        </div>
    </div>
</x-app-layout>
