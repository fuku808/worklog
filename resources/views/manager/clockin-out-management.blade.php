<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Clock-in/out management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form id="form_search" action="{{ route('management.clockin-out') }}" class="form" method="GET">
                <div class="flex sm:items-center flex-col sm:flex-row px-2 mb-4">
                    <label class="block sm:w-1/12 sm:text-right mb-1 pr-4" for="user_id">User</label>
                    <select name="user_id" class="rounded search-input">
                        <option value="">--- Select ---</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ isset($request->user_id) && $user->id == $request->user_id || isset($time_tracking) && $user->id == $time_tracking->user_id ? 'selected' : '' }}>{{ $user->firstname." ".$user->lastname }}</option>
                        @endforeach
                    </select>
                    <label class="block sm:w-1/12 sm:text-right mb-1 ml-4 pr-4" for="work_date">Work date</label>
                    <input name="work_date" type="date" class="rounded search-input" value="{{ isset($request->work_date) ? $request->work_date : (isset($time_tracking) ? $time_tracking->work_date : date('Y-m-d')) }}"></input>
                </div>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-gray-700 bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                            <th scope="col" class="px-6 py-2">Clocked in</th>
                            <th scope="col" class="px-6 py-2">Clocked out</th>
                            <th scope="col" class="px-6 py-2">Worked hours</th>
                            <th scope="col" class="px-6 py-2"></th>
                            <th scope="col" class="px-6 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($time_trackings as $time_tracking_data)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-4 py-1">{{ $time_tracking_data->clocked_in }}</td>
                                <td class="px-4 py-1">{{ $time_tracking_data->clocked_out }}</td>
                                <td class="px-4 py-1">{{ $time_tracking_data->total_hours }}</td>
                                <td class="px-4 py-1"><a href="{{ isset($time_tracking_data) ? route('management.clockin-out.edit', ['id' => $time_tracking_data->id]) : '' }}" class="p-2 text-white bg-yellow-500 rounded">Edit</a></td>
                                <td class="px-4 py-1">
                                    <form action="{{ isset($time_tracking_data) ? route('management.clockin-out.destroy', ['id' => $time_tracking_data->id]) : '' }}" method="POST">
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
            </form>
            <hr />
            <form action="{{ isset($time_tracking) ? route('management.clockin-out.update', ['id' => $time_tracking->id]) : route('management.clockin-out.store') }}" method="POST">
                @if (isset($time_tracking))
                @method('PUT')
                @endif
                @csrf
                @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    @foreach ($errors->all() as $error)
                    <span class="block sm:inline">{{ $error }}</span><br />
                    @endforeach
                </div>
                @endif
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-4">
                    <div class="flex sm:items-center m-6 flex-col sm:flex-row px-2">
                        <label class="block sm:w-2/12 sm:text-right mb-1 pr-4" for="clocked_in">Clocked-in</label>
                        <input name="clocked_in" type="datetime-local" step="1" class="date_range block w-full sm:w-3/12 bg-gray-200 py-2 px-3 text-right text-gray-700 border border-gray-200 rounded focus:outline-none focus:bg-white" value="{{ isset($time_tracking->clocked_in) ? $time_tracking->clocked_in : (old('clocked_in') ? old('clocked_in') : '') }}"></input>
                        <label class="block sm:w-2/12 sm:text-right mb-1 pr-4" for="clocked_out">Clocked-out</label>
                        <input name="clocked_out" type="datetime-local" step="1" class="date_range block w-full sm:w-3/12 bg-gray-200 py-2 px-3 text-right text-gray-700 border border-gray-200 rounded focus:outline-none focus:bg-white" value="{{ isset($time_tracking->clocked_out) ? $time_tracking->clocked_out : (old('clocked_out') ? old('clocked_out') : '') }}"></input>
                        <input type="hidden" id="work_date_hidden" name="work_date" value="{{ isset($time_tracking->work_date) ? $time_tracking->work_date : (isset($request->work_date) ? $request->work_date : '') }}" />
                        <input type="hidden" name="user_id" value="{{ isset($time_tracking->user_id) ? $time_tracking->user_id : (isset($request->user_id) ? $request->user_id : '') }}" />
                    </div>
                    <div class="flex sm:justify-items-center m-6 flex-col sm:flex-row px-2">
                        <div class="block sm:w-3/12"></div>
                        <button type="submit" class="mx-6 block sm:w-3/12 bg-blue-700 py-2 px-4 text-white bg-indigo-700 hover:bg-indigo-500 rounded">Add/Update</button>
                        <button type="button" class="mx-6 block sm:w-3/12 bg-blue-700 py-2 px-4 text-white bg-gray-700 hover:bg-gray-500 rounded" onclick="$('#form_search').submit();">Clear fields</button>
                        <div class=" block sm:w-3/12"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
<script>
    $('.date_range').click(function() {
        var work_date = $('input[name="work_date"]').val();
        $('#work_date_hidden').val(work_date);
        $(this).prop('min', work_date+"T00:00");
        $(this).prop('max', work_date+"T23:59");
    });
</script>
