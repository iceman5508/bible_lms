<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RankingLevelRequest;
use App\Models\RankingLevel;
use App\Models\User;
use App\Tools\Repositories\Crud;
use App\Traits\ApiStatusTrait;
use App\Traits\ImageSaveTrait;
use Auth;

class RankingLevelController extends Controller
{
    use ApiStatusTrait, ImageSaveTrait;
    protected $model;

    public function __construct(RankingLevel $level)
    {
        $this->model = new Crud($level);
    }

    public function index()
    {
        if (!Auth::user()->hasPermissionTo('ranking_level', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['levels'] = $this->model->getOrderById('ASC', 25);
        return $this->success($data);
    }


    public function update(RankingLevelRequest $request, RankingLevel $badge)
    {
        if (!Auth::user()->hasPermissionTo('ranking_level', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
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

        return $this->success([], __('Updated Successful'));
    }
}
