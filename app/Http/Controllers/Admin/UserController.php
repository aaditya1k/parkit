<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AdminTrait;
use App\User;
use Hash;
use Lang;

class UserController extends Controller
{
    use AdminTrait;

    private $rules = [
        'is_admin' => 'required',
        'new_password' => 'sometimes|nullable|min:6'
    ];

    public function index()
    {
        $users = User::paginate(20);
        return view('admin.user.index', [
            'users' => $users
        ]);
    }

    public function view($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.view', ['user' => $user]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->valid($request);

        $user->is_admin = $request->is_admin == "1" ? "1" : "0";
        if ($request->new_password != null) {
            $user->password = Hash::make($request->new_password);
        }
        $user->save();

        return redirect()->route('admin:user:edit', $user->id)->with('success', Lang::get('admin.updated'));
    }
}
