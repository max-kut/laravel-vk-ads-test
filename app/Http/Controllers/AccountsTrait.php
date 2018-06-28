<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.06.18
 * Time: 1:38
 */

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use VK\Client\VKApiClient;

trait AccountsTrait
{
    /**
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    protected function getAccounts(User $user)
    {
        $vk = new VKApiClient();
        
        $accounts = $vk->ads()->getAccounts($user->access_token);
        
        // сохраним в сессию
        session()->put('vk_ads_accounts', $accounts);
        session()->save();
        
        return $accounts;
    }
    
    /**
     * @param $accountId
     *
     * @return array|null
     */
    protected function getAccountById($accountId)
    {
        if (session()->has('vk_ads_accounts')) {
            $accounts = session('vk_ads_accounts');
        } else {
            $accounts = $this->getAccounts(Auth::user());
        }
        
        foreach ($accounts as $account) {
            if ($accountId == $account['account_id']) {
                return $account;
            }
        }
        
        return null;
    }
}