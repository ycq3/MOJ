<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/5 0005
 * Time: 17:38
 */

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Predis\Client;

class ClientAPIController extends Controller
{
    public function socket(Request $request)
    {
        $client=new Client();
        $response = $client->request('POST', 'http://localhost:2121', [
            'form_params' =>$request->all()
        ]);
        if ($response->getStatusCode() != 200) {
            return 'error';
        }
        return $response;
    }
}