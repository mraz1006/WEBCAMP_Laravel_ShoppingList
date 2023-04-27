<?php
declare(strict_types=1);
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\shopping_lists as ShoppingListsModel;
use App\Http\Requests\ShoppingListsRegisterPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Completed_Shopping_Lists as CompletedShoppingListsModel;


class ShoppingListController extends Controller
{
    public function list()
    {
        // 1Page辺りの表示アイテム数を設定
        $per_page = 2;


        $list = ShoppingListsModel::where('user_id', Auth::id())
        ->orderBy('name')
        ->paginate($per_page);
        //->get();
//$sql = ShoppingListsModel::toSql();
//echo "<pre>\n"; var_dump($sql, $list); exit;
        return view('shopping_list.list', ['list' => $list]);
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
            //var_dump($r); exit;
        } catch(\Throwable $e) {
            // XXX 本当はログに書く等の処理をする。今回は一端「出力する」だけ
            echo $e->getMessage();
                   
            exit;}


        // ユーザー登録成功
        $request->session()->flash('front.shoppinglists_register_success', true);

        // リダイレクト
        return redirect('/shopping_list/list');
        

    }
    /**
     * 「単一のタスク」Modelの取得
     */
    protected function getShoppingListsModel($shopping_list_id)
    {
        // task_idのレコードを取得する
        $item = ShoppingListsModel::find($shopping_list_id);
        if ($item === null) {
            return null;
        }
        // 本人以外のタスクならNGとする
        if ($item->user_id !== Auth::id()) {
            return null;
        }
        //
        return $item;
    }

    /**
     * 削除処理
     */
    public function delete(Request $request, $shopping_list_id)
    {
        // dのレコードを取得する
        $item = $this->getShoppingListsModel($shopping_list_id);
        //var_dump($item); exit;
        
        // タスクを削除する
        if ($item !== null) {
            $item->delete();
            $request->session()->flash('front.shopping_item_delete_success', true);
        }

        // 一覧に遷移する
        return redirect('/shopping_list/list');
    }
    /**
     * タスクの完了
     */
    public function complete(Request $request,$shopping_list_id)
    {
        /* タスクを完了テーブルに移動させる */
        try {
            // トランザクション開始
            DB::beginTransaction();

            // idのレコードを取得する
            $item = $this->getShoppingListsModel($shopping_list_id);
            if ($item === null) {
                // idが不正なのでトランザクション終了
                throw new \Exception('');
            }

            // item側を削除する
            $item->delete();

            // completed_tasks側にinsertする
            $dask_datum = $item->toArray();
            unset($dask_datum['created_at']);
            unset($dask_datum['updated_at']);
            $r = CompletedShoppingListsModel::create($dask_datum);

            if ($r === null) {
                // insertで失敗したのでトランザクション終了
                throw new \Exception('');
            }
//echo '処理成功'; exit;

            // トランザクション終了
            DB::commit();
            // 完了メッセージ出力
            $request->session()->flash('front.shopping_item_completed_success', true);
        } catch(\Throwable $e) {
//var_dump($e->getMessage()); exit;
            // トランザクション異常終了
            DB::rollBack();
            // 完了失敗メッセージ出力
            $request->session()->flash('front.shopping_item_completed_failure', true);
        }

        // 一覧に遷移する
        return redirect('/shopping_list/list');
    }
    public function completed_list()
    {
        // 1Page辺りの表示アイテム数を設定
        $per_page = 2;


        $list = CompletedShoppingListsModel::where('user_id', Auth::id())
        ->orderBy('name')
        ->paginate($per_page);
        //->get();
//$sql = ShoppingListsModel::toSql();
//echo "<pre>\n"; var_dump($sql, $list); exit;
        return view('shopping_list.completed_list', ['list' => $list]);
    }
}
