<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AdminTrait;
use App\Group;
use Lang;

class GroupController extends Controller
{
    use AdminTrait;

    private $rules = [
        'name' => 'required'
    ];

    public function index()
    {
        $groups = Group::paginate(20);
        return view('admin.group.index', ['groups' => $groups]);
    }

    public function new()
    {
        return view('admin.group.new');
    }

    public function create(Request $request)
    {
        $this->valid($request);

        $group = Group::create([
            'name' => $request->name,
            'balance' => 0
        ]);

        return redirect()->route('admin:group:edit', $group->id)->with('success', Lang::get('admin.created'));
    }

    public function view($id)
    {
        $group = Group::findOrFail($id);
        return view('admin.group.view', ['group' => $group]);
    }

    public function edit($id)
    {
        $group = Group::findOrFail($id);
        return view('admin.group.edit', ['group' => $group]);
    }

    public function update(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $this->valid($request);

        $group->name = $request->name;
        $group->save();

        return redirect()->route('admin:group:edit', $group->id)->with('success', Lang::get('admin.updated'));
    }
}
