<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User list') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex sm:items-center flex-col sm:flex-row px-2 mb-4">
                <form action="{{ route('user.create') }}" method="GET">
                    <button type="submit" class="text-white py-2 px-4 rounded bg-green-700 hover:bg-green-500">Add user</button>
                </form>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-gray-700 bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                        <th scope="col" class="px-6 py-2">Username</th>
                        <th scope="col" class="px-6 py-2">First name</th>
                        <th scope="col" class="px-6 py-2">Last name</th>
                        <th scope="col" class="px-6 py-2">Email</th>
                        <th scope="col" class="px-6 py-2">Role</th>
                        <th width="5%" scope="col" class="px-2 py-2"></th>
                        <th width="5%" scope="col" class="px-2 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td class="px-4 py-1">{{ $user->username }}</td>
                            <td class="px-4 py-1">{{ $user->firstname }}</td>
                            <td class="px-4 py-1">{{ $user->lastname }}</td>
                            <td class="px-4 py-1">{{ $user->email }}</td>
                            <td class="px-4 py-1">{{ ($user->role == 9 ? 'System admin' : ($user->role == 5 ? 'Manager' : 'Member')) }}</td>
                            <td class="px-2 py-1">
                                <form action="{{ route('user.edit', ['user' => $user->id]) }}" method="GET">
                                    <button type="submit" class="px-4 py-1 text-white bg-yellow-500 hover:bg-yellow-400 rounded">Edit</button>
                                </form>
                            </td>
                            <td class="px-4 py-1">
                                @if (config('app.env') == 'demo')
                                    <button type="button" class="px-2 py-1 text-white bg-red-400 rounded cursor-not-allowed" disabled>Delete</button>
                                @else
                                <form action="{{ isset($user) ? route('user.destroy', ['user' => $user->id]) : '' }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 text-white bg-red-500 hover:bg-red-400 rounded" onclick="return confirm('Are you sure to delete this?');">Delete</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
