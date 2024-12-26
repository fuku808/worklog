<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Work log report') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex sm:items-center flex-col sm:flex-row px-2 mb-4">
                <form action="{{ route('management.work-log.report') }}" class="form" method="GET">
                    <label class="sm:w-1/12 sm:text-right mb-1 pr-4" for="date_from">From</label>
                    <input name="date_from" type="date" class="search-input rounded" value="{{ isset($request->date_from) ? $request->date_from : date('Y-m-01') }}"></input>
                    <label class="sm:w-1/12 sm:text-right mb-1 px-4" for="date_to">To</label>
                    <input name="date_to" type="date" class="search-input rounded" value="{{ isset($request->date_to) ? $request->date_to : date('Y-m-t') }}"></input>
                    <label class="sm:w-1/12 sm:text-right mb-1 px-4" for="user_id">User</label>
                    <select name="user_id" class="search-input rounded">
                        <option value="">--- Select ---</option>
                        @foreach ($users as $user)
                        <option value={{ $user->id }} {{ isset($request->user_id) && $user->id == $request->user_id ? 'selected' : '' }}>{{ $user->firstname." ".$user->lastname }}</option>
                        @endforeach
                    </select>
                </form>
                <form action="{{ route('management.work-log.download') }}" method="GET">
                    <button type="submit" class="px-12 text-center text-white bg-green-700 hover:bg-green-500 mb-1 ml-10 p-2 rounded">Download</button>
                    <input type="hidden" name="date_from"  value="{{ isset($request->date_from) ? $request->date_from : date("Y-m-01") }}" />
                    <input type="hidden" name="date_to"  value="{{ isset($request->date_to) ? $request->date_to : date("Y-m-t") }}" />
                    <input type="hidden" name="user_id"  value="{{ isset($request->user_id) ? $request->user_id : '' }}" />
                </form>
            </div>
            @if ($work_logs)
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-gray-700 bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                        <th scope="col" class="px-6 py-2">Name</th>
                        <th width="15%" scope="col" class="px-6 py-2">Work date</th>
                        <th width="3%"  scope="col" class="px-6 py-2">Hours</th>
                        <th scope="col" class="px-6 py-2">Activity</th>
                        <th scope="col" class="px-6 py-2">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($work_logs->get() as $work_log)
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-4 py-1">{{ $work_log->user->firstname." ".$work_log->user->lastname }}</td>
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
        </div>
    </div>
</x-app-layout>
