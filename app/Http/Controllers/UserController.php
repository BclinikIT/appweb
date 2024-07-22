<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $datos = User::all();
        return inertia('User/List', ['datos' => $datos]);
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return inertia('User/Edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ]);

        $user->update($request->only('name', 'email'));

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }
}
