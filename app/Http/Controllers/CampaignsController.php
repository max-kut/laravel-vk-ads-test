<?php

namespace App\Http\Controllers;

use App\Http\AppContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use VK\Client\VKApiClient;

class CampaignsController extends Controller
{
    use AccountsTrait;
    
    protected $title = "Список рекламных кабинетов";
    
    public function list(AppContext $context, $accountId)
    {
        $context->viewName = "AccountView";
        
        $account       = $this->getAccountById($accountId);
        $context->head = "Рекламный кабинет: '{$account['account_name']}'";
        
        $context->data = [
            'campaigns' => $this->getCampaigns($accountId),
        ];
        
        $context->authUser = Auth::user();
        $context->csrf_token = csrf_token();
        
        return $this->view($context);
    }
    
    /**
     * @param $accountId
     *
     * @return mixed
     */
    private function getCampaigns($accountId)
    {
        $vk = new VKApiClient();
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $vk->ads()->getCampaigns($user->access_token, [
            'account_id' => $accountId,
            'include_deleted' => 1,
        ]);
    }
}
