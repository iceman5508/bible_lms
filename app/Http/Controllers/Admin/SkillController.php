<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    use General;

    protected $model;
    public function __construct(Skill $skill)
    {
        $this->model = new Crud($skill);
    }

    public function index()
    {
        if (!Auth::user()->can('skill')) {
            abort('403');
        }

        $data['title'] = 'Manage Skill';
        $data['subNavSkillActiveClass'] = "mm-active";
        $data['skills'] = $this->model->getOrderById('DESC', 25);
        return view('admin.skill.index', $data);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->can('skill')) {
            abort('403');
        }

        $request->validate([
            'title' => 'required|max:255',
            'status' => 'required',
            'description' => 'max:255',
        ]);

        $data = [
            'title' => $request->title,
            'status' => $request->status,
            'description' => $request->description,
        ];

        $this->model->create($data);

        $this->showToastrMessage('success', __('Created Successful'));
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('skill')) {
            abort('403');
        }

        $request->validate([
            'title' => 'required|max:255',
            'status' => 'required',
            'description' => 'max:255',
        ]);

        $data = [
            'title' => $request->title,
            'status' => $request->status,
            'description' => $request->description,
        ];

        $this->model->update($data, $id);
        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->back();
    }

    public function delete($id)
    {
        if (!Auth::user()->can('skill')) {
            abort('403');
        }

        $this->model->delete($id);
        $this->showToastrMessage('error', __('Deleted Successful'));
        return redirect()->back();
    }
}
