<?php

namespace App\Http\Controllers;

use App\Http\AppContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function entry(AppContext $context)
    {
    
        if(Auth::check()){
            return redirect('/accounts');
        }
        
        $context->csrf_token = csrf_token();
        $context->authUser = null;
        $this->title = "Необходима авторизация во Вконтакте";
        $context->viewName = "LoginView";
        return $this->view($context);
    }
    
}
