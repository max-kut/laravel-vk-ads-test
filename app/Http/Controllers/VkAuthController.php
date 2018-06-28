<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use VK\Client\VKApiClient;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class VkAuthController extends Controller
{
    /** uri на который VK перенаправит */
    const REDIRECT_URI = "/vk-redirect";
    
    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function vkGetAuthCode(VKOAuth $oauth)
    {
        $redirect_uri = env('APP_URL') . self::REDIRECT_URI;
        $display      = VKOAuthDisplay::PAGE;
        $scope        = [
            VKOAuthUserScope::ADS,
        ];
        
        $browser_url = $oauth->getAuthorizeUrl(
            VKOAuthResponseType::CODE,
            env('VK_CLIENT_ID'),
            $redirect_uri,
            $display,
            $scope,
            env('VK_APP_SECRET')
        );
        
        return redirect($browser_url);
    }
    
    public function vkRedirect(Request $request)
    {
        if ($request->code) {
            $oauth         = new VKOAuth();
            $redirect_uri  = env('APP_URL') . self::REDIRECT_URI;
            
            $response     = $oauth->getAccessToken(
                env('VK_CLIENT_ID'),
                env('VK_APP_SECRET'),
                $redirect_uri,
                $request->code
            );
            
            $userData = $this->getVkUserData($response['user_id'], $response['access_token']);
            
            $this->saveUserInDb(array_merge($response, $userData));
            
            return redirect('/');
        } else if($request->error){
            dump([
                'error' => $request->error,
                'error_description' => $request->error_description,
            ]);
        }
        
    }
    
    /**
     * @param int $vkUserId
     * @param string $accessToken
     *
     * @return mixed
     */
    private function getVkUserData($vkUserId, $accessToken)
    {
        $vk = new VKApiClient();
        $response = $vk->users()->get($accessToken, [
            'user_ids' => [$vkUserId],
            'fields' => 'photo_100'
        ]);
        return $response[0];
    }
    
    /**
     * @param array $userData
     *
     * @return mixed
     */
    private function saveUserInDb(array $userData)
    {
        $user = User::where('vk_user_id', $userData['user_id'])->first();
        if(is_null($user)){
            $user = new User();
            $user->vk_user_id = $userData['user_id'];
        }
    
        $user->first_name = $userData['first_name'];
        $user->last_name = $userData['last_name'];
        $user->photo_url = $userData['photo_100'];
        $user->access_token = $userData['access_token'];
        $user->expires_in = $userData['expires_in'];
        
        $user->save();
        
        // запомним
        Auth::login($user, true);
        
        return $user;
    }
}
