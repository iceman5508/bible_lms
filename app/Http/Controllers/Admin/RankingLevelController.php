<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Instrucotr;
use App\Http\Requests\Admin\RankingLevelRequest;
use App\Models\BlogTag;
use App\Models\RankingLevel;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\General;
use App\Traits\ImageSaveTrait;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Storage;

class RankingLevelController extends Controller
{
    use General, ImageSaveTrait;
    protected $model;

    public function __construct(RankingLevel $level)
    {
        $this->model = new Crud($level);
    }

    public function index()
    {
        if (!Auth::user()->can('ranking_level')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Manage Badge';
        $data['navRankingActiveClass'] = "mm-active";
        $data['subNavRankingActiveClass'] = "mm-active";
        $data['levels'] = $this->model->getOrderById('ASC', 25);
        return view('admin.ranking.index', $data);
    }

    public function edit($uuid)
    {
        if (!Auth::user()->can('ranking_level')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Edit Badge';
        $data['navRankingActiveClass'] = "mm-active";
        $data['subNavRankingActiveClass'] = "mm-active";
        $data['level'] = $this->model->getRecordByUuid($uuid);
        return view('admin.ranking.edit', $data);
    }

    public function store(RankingLevelRequest $request)
    {
        if (!Auth::user()->can('ranking_level')) {
            abort('403');
        } // end permission checking

        $ranking = new RankingLevel();
        $ranking->name = $request->name;
        $ranking->type = $request->type;
        $ranking->from = $request->from;
        $ranking->to = $request->to;
        $ranking->description = $request->description;
        if ($request->badge_image) {
            $ranking->badge_image = $this->saveImage('ranking_level', $request->badge_image, null, null);
        }
        $ranking->save();

        $this->showToastrMessage('success', __('Created Successful'));
        return redirect()->back();
    }

    public function update(RankingLevelRequest $request, RankingLevel $badge)
    {
        if (!Auth::user()->can('ranking_level')) {
            abort('403');
        } // end permission checking

        $ranking = $badge;
        $ranking->name = $request->name;
        $ranking->from = $request->from;
        $ranking->to = $request->to;
        $ranking->description = $request->description;
        if ($request->badge_image) {
            $ranking->badge_image = $this->updateImage('ranking_level', $request->badge_image, $ranking->badge_image, 'null', 'null');
        }
        $ranking->save();

        $this->showToastrMessage('success', __('Updated Successful'));
        return redirect()->route('ranking.index');
    }

    public function resetBadge()
    {
        if (!Auth::user()->can('ranking_level')) {
            abort('403');
        } // end permission checking

        $users = User::whereIn('users.role', [USER_ROLE_INSTRUCTOR,USER_ROLE_ORGANIZATION])
                ->leftJoin('instructors as ins', 'ins.user_id', '=', 'users.id')
                ->leftJoin('organizations as org', 'org.user_id', '=', 'users.id')
                ->whereIn('users.role', [USER_ROLE_INSTRUCTOR,USER_ROLE_ORGANIZATION])
                ->where(function($q){
                    $q->where('ins.status', STATUS_APPROVED)
                    ->orWhere('org.status', STATUS_APPROVED);
                })
                ->select('users.id')
                ->get();

        foreach($users as $user){
            setBadge($user->id);
        }

        $this->showToastrMessage('success', __('Refresh Successful'));
        return back();
    }

    public function delete($uuid)
    {
        if (!Auth::user()->can('ranking_level')) {
            abort('403');
        } // end permission checking

        $ranking = $this->model->getRecordByUuid($uuid);
        $this->deleteFile($ranking->badge_image); // delete file from server
        $this->model->deleteByUuid($uuid);

        $this->showToastrMessage('error', __('Badge has been deleted'));
        return redirect()->back();
    }
}
