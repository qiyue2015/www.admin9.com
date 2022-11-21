<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  Request  $request
     * @param  Account  $account
     * @return JsonResponse
     */
    public function index(Request $request, Account $account): JsonResponse
    {
        $accounts = $account->paginate(20);
        return $this->success($accounts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate(['url' => 'required|url']);

        $url = 'https://www.jzl.com/fbmain/monitor/v3/article_detail';
        $response = Http::withoutVerifying()
            ->timeout(30)
            ->get($url, [
                'url' => $request->get('url'),
                'key' => env('JZL_API_KEY'),
            ])
            ->object();

        if (!Account::where('biz', $response->data->biz)->exists()) {
            Account::create([
                'name' => $response->data->name,
                'account' => $response->data->wxid,
                'original' => $response->data->gh_id,
                'signature' => $response->data->signature,
                'biz' => $response->data->biz,
                'avatar' => $response->data->mp_head_img,
            ]);

            return $this->success();
        }

        return $this->fail('该公众号已存在.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
