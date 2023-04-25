<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * トップページ を表示する
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('user/register');
    }

    /**
     * 登録処理
     *
     */
    public function register(UserRegisterPost $request)
    {
        // Validation Check
        $datum = $request->validated();

        //passwordのハッシュ化

        
        //var_dump($datum); exit;
       

        //
        /*
        $name = $request->input('name');
        $email = $request->input('email');
        $pass = $request->input('password');
        var_dump($name,$email, $pass); exit;
         */

        //既にテーブルの中居にいるかどうかの確認処理
        

        // テーブルへのINSERT
        try {
            $datum['password'] = Hash::make($datum['password']);
            $r = UserModel::create($datum);
            // var_dump($r); exit;
        } catch(\Throwable $e) {
            // XXX 本当はログに書く等の処理をする。今回は一端「出力する」だけ
            //echo $e->getMessage();
            return back()
                   ->withErrors(['auth' => 'ユーザー登録に失敗しました',]) // エラーメッセージの出力
                   ;
            exit;}

        // ユーザー登録成功
        $request->session()->flash('front.user_register_success', true);

        // リダイレクト
        return redirect('/');
        

    }

}