<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add/Edit user') }}
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
            <form action="{{ isset($user) ? route('user.update', ['user' => $user->id]) : route('user.store') }}" method="POST">
                @if (isset($user))
                @method('PUT')
                @endif
                @csrf
                <div class="bg-gray-300 overflow-hidden shadow-xl sm:rounded-lg mb-4 mx-20">
                    <div class="flex sm:items-center m-6 flex-col sm:flex-row px-2">
                        <label class="block sm:w-2/12 sm:text-right mb-1 pr-4" for="username">Username</label>
                        <input name="username" type="text" class="block w-full sm:w-9/12 py-2 px-3 border border-gray-200 rounded" value="{{ isset($user->username) ? $user->username : (old('username') ? old('username') : '') }}"></input>
                    </div>
                    <div class="flex sm:items-center m-6 flex-col sm:flex-row px-2">
                        <label class="block sm:w-2/12 sm:text-right mb-1 pr-4" for="firstname">First name</label>
                        <input name="firstname" type="text" class="block w-full sm:w-9/12 py-2 px-3 border border-gray-200 rounded" value="{{ isset($user) ? $user->firstname : (old('firstname') ? old('firstname') : '') }}" />
                    </div>
                    <div class="flex sm:items-center m-6 flex-col sm:flex-row px-2">
                        <label class="block sm:w-2/12 sm:text-right mb-1 pr-4" for="lastname">Last name</label>
                        <input name="lastname" type="text" class="block w-full sm:w-9/12 py-2 px-3 border border-gray-200 rounded" value="{{ isset($user) ? $user->lastname : (old('lastname') ? old('lastname') : '') }}" />
                    </div>
                    <div class="flex sm:items-center m-6 flex-col sm:flex-row px-2">
                        <label class="block sm:w-2/12 sm:text-right mb-1 pr-4" for="email">Email</label>
                        <input name="email" type="email" class="block w-full sm:w-9/12 py-2 px-3 border border-gray-200 rounded" value="{{ isset($user) ? $user->email : (old('email') ? old('email') : '') }}" />
                    </div>
                    @if (!isset($user))
                    <div class="flex sm:items-center m-6 flex-col sm:flex-row px-2">
                        <label class="block sm:w-2/12 sm:text-right mb-1 pr-4" for="password">Password</label>
                        <input name="password" type="password" class="block w-full sm:w-9/12 py-2 px-3 border border-gray-200 rounded" value="{{ isset($user) ? $user->password : (old('password') ? old('password') : '') }}" />
                    </div>
                    @else
                    <div class="flex sm:items-center m-6 flex-col sm:flex-row px-2">
                        <div class="block sm:w-2/12"></div>
                        <button type="button" data-modal-target="default-modal" data-modal-toggle="default-modal" class="block sm:w-3/12 bg-blue-700 py-2 px-4 text-white bg-green-700 hover:bg-green-500 rounded">Reset password</button>
                    </div>
                    @endif
                    <div class="flex sm:items-center m-6 flex-col sm:flex-row px-2">
                        <label class="block sm:w-2/12 sm:text-right mb-1 pr-4" for="role">Role</label>
                        <select name="role" class="rounded border-gray-200">
                            <option value="1" {{ isset($user) && $user->role == 1 ? 'selected' : '' }}>Member</option>
                            <option value="5" {{ isset($user) && $user->role == 5 ? 'selected' : '' }}>Manager</option>
                            <option value="9" {{ isset($user) && $user->role == 9 ? 'selected' : '' }}>System admin</option>
                        </select>
                    </div>
                    <div class="flex justify-center m-6f lex-col sm:flex-row px-2">
                        <a href="{{ route('user.index') }}" class="block sm:w-2/12 bg-blue-700 py-2 px-4 mr-3 text-center text-white bg-gray-700 hover:bg-gray-500 rounded">Cancel</a>
                        @if (config('app.env') == 'demo')
                        <button type="button" class="block sm:w-2/12 bg-blue-700 py-2 px-4 text-white bg-indigo-500 rounded cursor-not-allowed" disabled>Add/Update</button>
                        @else
                        <button type="submit" class="block sm:w-2/12 bg-blue-700 py-2 px-4 text-white bg-indigo-700 hover:bg-indigo-500 rounded">Add/Update</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


<!-- Modal -->
<div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Reset password
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form class="space-y-4" action="{{ route('user.reset_password') }}" method="POST">
                @csrf
                <div class="p-4 md:p-5 space-y-4">
                    <input type="hidden" name="id" value="{{ isset($user) ? $user->id : '' }}" />
                    <div>
                        <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New password</label>
                        <input type="password" name="new_password" max="255" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                    </div>
                    <div class="flex flex-row-reverse items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        @if (config('app.env') == 'demo')
                        <button type="button" class="text-white bg-blue-300 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 ms-3 text-center cursor-not-allowed" disabled>Update</button>
                        @else
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 ms-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update</button>
                        @endif
                        <button data-modal-hide="default-modal" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
