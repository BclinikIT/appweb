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

    public function create()
    {
        return inertia('User/Edit', ['user' => new User(), 'isEdit' => false]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return inertia('User/Edit', ['user' => $user, 'isEdit' => true]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
    
            return response()->json(['message' => 'Usuario eliminado exitosamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo eliminar el usuario'], 500);
        }
    }
    
    
}
