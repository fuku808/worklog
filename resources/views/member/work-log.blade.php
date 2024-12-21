<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily work report') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                @foreach ($errors->all() as $error)
                <span class="block sm:inline">{{ $error }}</span><br />
                @endforeach
            </div>
            @endif
            <form id="form_search" action="{{ route('work-log.index') }}" class="form mb-4" method="GET">
                <p class="mb-2 px-2">Name: {{ Auth::user()->firstname." ".Auth::user()->lastname }}</p>
                <div class="flex sm:items-center flex-col sm:flex-row px-2">
                    <label class="block sm:w-1/12 mb-1 pr-4" for="search_date">Work date:</label>
                    <input name="search_date" type="date" class="rounded" value="{{ isset($request->search_date) ? $request->search_date : (isset($work_log->work_date) ? $work_log->work_date : date('Y-m-d')) }}"></input>
                    <button type="submit" class="text-white mx-2 py-2 px-4 rounded bg-indigo-700 hover:bg-indigo-500">Search</button>
                </div>
            </form>
            <form action="{{ isset($work_log) ? route('work-log.update', ['work_log' => $work_log->id]) : route('work-log.store') }}" method="POST">
                @if (isset($work_log))
                @method('PUT')
                @endif
                @csrf
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-4">
                    <div class="flex sm:items-center m-6 flex-col sm:flex-row px-2">
                        <label class="block sm:w-1/12 sm:text-right mb-1 pr-4" for="work_date">Date</label>
                        <input name="work_date" type="date" class="block w-full sm:w-2/12 bg-gray-200 py-2 px-3 text-right text-gray-700 border border-gray-200 rounded focus:outline-none focus:bg-white" value="{{ isset($work_log->work_date) ? $work_log->work_date : (isset($request->search_date) ? $request->search_date : (old('work_date') ? old('work_date') : date('Y-m-d'))) }}"></input>
                        <label class="block sm:w-1/12 sm:text-right mb-1 pr-4" for="hours">Hours</label>
                        <input name="hours" step="0.01" min="0.01" class="block w-full sm:w-2/12 bg-gray-200 py-2 px-3 text-right text-gray-700 border border-gray-200 rounded focus:outline-none focus:bg-white" id="hours" type="number" value="{{ isset($work_log) ? $work_log->hours : (old('hours') ? old('hours') : '') }}" />
                    </div>
                    <div class="flex sm:items-center m-6 flex-col sm:flex-row px-2">
                        <label class="block sm:w-1/12 sm:text-right mb-1 pr-4" for="activity">Activity</label>
                        <textarea name="activity" rows="5" class="block w-full sm:w-5/12 bg-gray-200 py-2 px-3 text-gray-700 border border-gray-200 rounded focus:outline-none focus:bg-white">{{ isset($work_log) ? $work_log->activity : (old('activity') ? old('activity') : '') }}</textarea>
                        <label class="block sm:w-1/12 sm:text-right mb-1 pr-4" for="note">Note</label>
                        <textarea name="note" rows="5" class="block w-full sm:w-5/12 bg-gray-200 py-2 px-3 text-gray-700 border border-gray-200 rounded focus:outline-none focus:bg-white">{{ isset($work_log) ? $work_log->note : (old('note') ? old('note') : '') }}</textarea>
                    </div>
                    <div class="flex sm:justify-items-center m-6 flex-col sm:flex-row px-2">
                        <div class="block sm:w-3/12"></div>
                        <button type="submit" class="mx-6 block sm:w-3/12 bg-blue-700 py-2 px-4 text-white bg-indigo-700 hover:bg-indigo-500 rounded">Add/Update</button>
                        <button type="button" class="mx-6 block sm:w-3/12 bg-blue-700 py-2 px-4 text-white bg-gray-700 hover:bg-gray-500 rounded" onclick="$('#form_search').submit();">Clear fields</button>
                        <div class=" block sm:w-3/12"></div>
                    </div>
                </div>
            </form>
            <table class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-gray-700 bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th width="15%" scope="col" class="px-4 py-2">Date</th>
                        <th width="3%" scope="col" class="px-4 py-2">Hours</th>
                        <th scope="col" class="px-4 py-2">Activity</th>
                        <th scope="col" class="px-4 py-2">Note</th>
                        <th width="5%" scope="col" class=""></th>
                        <th width="5%" scope="col" class=""></th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($work_logs as $work_log)
                <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                    <td class="px-4 py-1">{{ $work_log->work_date }}</td>
                    <td class="px-4 py-1">{{ $work_log->hours }}</td>
                    <td class="px-4 py-1">{{ $work_log->activity }}</td>
                    <td class="px-4 py-1">{{ $work_log->note }}</td>
                    <td class="px-4 py-1"><a href="{{ isset($work_log) ? route('work-log.edit', ['work_log' => $work_log->id]) : '' }}" class="p-2 text-white bg-yellow-500 rounded">Edit</a></td>
                    <td class="px-4 py-1">
                        <form action="{{ isset($work_log) ? route('work-log.destroy', ['work_log' => $work_log->id]) : '' }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-white bg-red-500 rounded" onclick="return confirm('Are you sure to delete this?');">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
