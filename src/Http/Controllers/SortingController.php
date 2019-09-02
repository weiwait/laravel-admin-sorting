<?php

namespace Weiwait\Sorting\Http\Controllers;

use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SortingController extends Controller
{
    public function sorting(Request $request)
    {
        $model = $request->model::query()->findOrFail($request->id);

        try {
            switch ($request->action) {
                case 'to':
                    $model->moveOrderTo($request->order);
                    break;
                case 'ascend':
                    $model->moveOrderAscend($request->order);
                    break;
                case 'descend':
                    $model->moveOrderDescend($request->order);
                    break;
                default:
                    throw new NotFoundHttpException('该操作不存在');
            }
        } catch (\Exception $exception) {
            return response($exception, 500);
        }

        return response('排序成功', 200);
    }
}
