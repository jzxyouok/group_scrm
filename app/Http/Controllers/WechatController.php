<?php
/**
 * Controller generated using LaraAdmin
 * Help: http://laraadmin.com
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Wechat;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class WechatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    public function valid(){
        $wechat = Wechat::find(1);
        $options = array(
            'app_id' => $wechat->app_id,
            'secret' => $wechat->secret,
            'token'  => $wechat->token,
            'aes_key' => $wechat->aes_key // 可选
        );

        $app = new Application($options);
        
        $response = $app->server->serve();
        return $response;
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $roleCount = \App\Role::count();
        if($roleCount != 0) {
            if($roleCount != 0) {
                return view('home');
            }
        } else {
            return view('errors.error', [
                'title' => 'Migration not completed',
                'message' => 'Please run command <code>php artisan db:seed</code> to generate required table data.',
            ]);
        }
    }
}