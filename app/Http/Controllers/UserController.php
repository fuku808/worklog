<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('master.user-list', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.user-edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'max:255',
            'role' => 'required',
            'password' => 'required|string|max:255',
        ]);

        try {
            User::create([
                'username' => $request->username,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
                'updated_user_id' => Auth::user()->id,
            ]);

            $notification = array(
                'message' => "Added successfully.",
                'alert-type' => 'success'
            );
        } catch (Exception $e) {
            $notification = array(
                'message' => "Addition failed. ".$e->getMessage(),
                'alert-type' => 'error'
            );
        }

        return redirect()->route('user.index')->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);

        return view('master.user-edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,'.$id,
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'max:255',
            'role' => 'required',
        ]);

        try {
            $user = User::find($id);

            if ($user->id == Auth::user()->id && $request->role < Auth::user()->role) {
                throw new Exception("Cannot downgrade your role by yourself. Please request to other system admins.");
            }

            $user->update([
                'username' => $request->username,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'role' => $request->role,
                'updated_user_id' => Auth::user()->id,
            ]);

            $notification = array(
                'message' => "Updated successfully.",
                'alert-type' => 'success'
            );
        } catch (Exception $e) {
            $notification = array(
                'message' => "Update failed. ".$e->getMessage(),
                'alert-type' => 'error'
            );
        }

        return redirect()->route('user.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);

            // cannot delete own account
            if ($user->id == Auth::user()->id) {
                throw new Exception("Cannot delete your account by yourself.");
            }

            $user->delete();

            $notification = array(
                'message' => "Deleted successfully.",
                'alert-type' => 'success'
            );

        } catch (Exception $e) {
           $notification = array(
                'message' => "Deletion failed. ".$e->getMessage(),
                'alert-type' => 'error'
            );
        }

        return redirect()->route('user.index')->with($notification);
    }

    public function reset_password(Request $request)
    {
        $validated = $request->validate([
            'new_password' => 'required|string|max:255',
        ]);

        try {
            $user = User::find($request->id);
            $user->update([
                'password' => Hash::make($request->new_password),
                'updated_user_id' => Auth::user()->id,
            ]);

            $notification = array(
                'message' => "Password updated successfully.",
                'alert-type' => 'success'
            );
        } catch (Exception $e) {
            $notification = array(
                'message' => "Password update failed. ".$e->getMessage(),
                'alert-type' => 'error'
            );
        }

        return redirect()->route('user.edit', ['user' => $request->id])->with($notification);
    }
}
