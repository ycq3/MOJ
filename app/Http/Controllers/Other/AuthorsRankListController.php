<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/30 0030
 * Time: 13:59
 */

namespace App\Http\Controllers\Other;


use App\Http\Controllers\Controller;
use App\Model\UserInformation;

class AuthorsRankListController extends Controller
{
    public function index()
    {
        $data=UserInformation::with('user')->orderByDesc('accepted')->paginate(30);
        $data->setCollection($data->getCollection()->map(function ($item) {
            return [
                'quote'=>$item->quote,
                'submitted' => $item->submitted,
                'accepted'=>$item->accepted,
                'user_id'=>$item->user_id,
                'user_name'=>$item->user->name,
            ];
        }));
        return $data;
    }
}