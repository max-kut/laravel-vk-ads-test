<?php

namespace App\Http\Controllers;

use App\Http\AppContext;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use VK\Client\VKApiClient;

class AccountsController extends Controller
{
    use AccountsTrait;
    
    protected $title = "Список рекламных кабинетов";
    
    /**
     * @param \App\Http\AppContext $context
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list(AppContext $context)
    {
        $context->viewName = "AccountsListView";
        $context->head     = "Список рекламных кабинетов";
        /** @var User $user */
        $user              = Auth::user();
        $context->authUser = $user;
        $context->data     = [
            'accounts' => $this->getAccounts($user),
        ];
        $context->csrf_token = csrf_token();
        
        return $this->view($context);
    }
}
