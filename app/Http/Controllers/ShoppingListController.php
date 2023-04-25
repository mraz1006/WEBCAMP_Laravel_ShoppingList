<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\shopping_lists as ShoppingListsModel;
use App\Http\Requests\ShoppingListsRegisterPost;
use Illuminate\Support\Facades\Auth;


class ShoppingListController extends Controller
{
    public function list()
    {
        return view('shopping_list.list');
    }

    /**
     * 登録処理
     *
     */
    public function register(ShoppingListsRegisterPost $request)
    {
        // Validation Check
        $datum = $request->validated(); 
       
       
        //入力確認用
        //$name = $request->input('name');
        //var_dump($name); exit;
        

        // テーブルへのINSERT
                
        try {
             // user_id の追加
            $datum['user_id'] = Auth::id();
            //var_dump($datum); exit;
            $r = ShoppingListsModel::create($datum);
            var_dump($r); exit;
        } catch(\Throwable $e) {
            // XXX 本当はログに書く等の処理をする。今回は一端「出力する」だけ
            echo $e->getMessage();
                   
            exit;}


        // ユーザー登録成功
        $request->session()->flash('front.shoppinglists_register_success', true);

        // リダイレクト
        return redirect('/shopping_list/list');
        

    }
}
