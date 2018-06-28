<?php

namespace App\Http\Controllers;

use App\Http\AppContext;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /** @var string  */
    protected $title = "";
    
    /**
     *
     * @param \App\Http\AppContext $context
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function view(AppContext $context)
    {
        /**
         * т.к. фронт на vue и рендерится на клиенте,
         * то мы имеем только один базовый шаблон, в который передаем данные и заголовок
         */
        
        return view('app.templates.index',[
            'title' => $this->title,
            'context' => $context->toBase64()
        ]);
    }
}
