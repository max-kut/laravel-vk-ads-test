<?php

namespace App\Http\Controllers;

use App\Http\AppContext;
use App\Models\Ad;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use VK\Client\VKApiClient;

class AdsController extends Controller
{
    use AccountsTrait;
    protected $title = "Список объявлений";
    
    /**
     * @param \App\Http\AppContext $context
     * @param $accountId
     * @param $campaignId
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list(AppContext $context, $accountId, $campaignId)
    {
        $context->viewName = "AdsView";
        $account       = $this->getAccountById($accountId);
        $campaign = $this->getCampaignById($accountId, $campaignId);
        $context->head = "Рекламный кабинет: '{$account['account_name']}';<br>Кампания: '{$campaign['name']}'";
        
        $context->authUser = Auth::user();
        
        $context->data = [
            'ads' => $this->getAds($accountId, $campaignId),
            'backLink' => "/accounts/$accountId",
            'accountId' => $accountId
        ];
        $context->csrf_token = csrf_token();
        
        return $this->view($context);
    }
    
    /**
     * @param $accountId
     * @param $campaignId
     *
     * @return mixed
     */
    private function getCampaignById($accountId, $campaignId)
    {
        $vk = new VKApiClient();
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $vk->ads()->getCampaigns($user->access_token, [
            'account_id' => $accountId,
            'include_deleted' => 1,
            'campaign_ids' => json_encode([$campaignId], JSON_NUMERIC_CHECK)
        ])[0];
    }
    
    /**
     * @param $accountId
     * @param $campaignId
     *
     * @return array|mixed
     */
    private function getAds($accountId, $campaignId)
    {
        $vk = new VKApiClient();
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $ads = $vk->ads()->getAds($user->access_token, [
            'account_id' => $accountId,
            'include_deleted' => 1,
            'campaign_ids' => json_encode([$campaignId], JSON_NUMERIC_CHECK)
        ]);
    
        return !empty($ads) ? $this->syncAdsWithDatabase($ads) : [];
    }
    
    /**
     * @param array $ads
     *
     * @return mixed
     */
    private function syncAdsWithDatabase(array $ads)
    {
        $ids = [];
        foreach ($ads as $ad){
            $ids[] = (int)$ad['id'];
        }
        /** @var \Illuminate\Database\Eloquent\Collection $adsInDB */
        $adsInDB = Ad::whereIn('vk_ad_id',$ids)->get();
        
        $sync = function($adsInDB, $ads){
            foreach ($ads as $k => $ad){
                $ads[$k]['description'] = '';
                foreach ($adsInDB as $adDb){
                    if($ad['id'] == $adDb['vk_ad_id']){
                        $ads[$k]['description'] = $adDb['description'] ?: '';
                        break;
                    }
                }
            }
            return $ads;
        };
        
        // Если в базе есть все объявления
        if($adsInDB->count() === count($ads)){
            return $sync($adsInDB,$ads);
        }
        
        $idsInDb = [];
        foreach ($adsInDB as $adDb){
            $idsInDb[] = (int)$adDb['vk_ad_id'];
        }
        $createAds = [];
        foreach ($ads as $ad){
            if(!in_array((int)$ad['id'], $idsInDb)){
                $createAds[] = [
                    'vk_ad_id' => (int)$ad['id']
                ];
            }
        }
        Ad::insert($createAds);
        return $sync($adsInDB,$ads);
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     */
    public function delete(Request $request, $id)
    {
        $vk = new VKApiClient();
        /** @var \App\Models\User $user */
        $user = Auth::user();
    
        $vk->ads()->updateAds($user->access_token, [
            'account_id' => $request->accountId,
            'data' => json_encode([
                [
                    'ad_id' => $id,
                    'status' => 2
                ]
            ])
        ]);
        
        return response()->json($vk);
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @param $id
     */
    public function save(Request $request, $id)
    {
        $ad = Ad::where('vk_ad_id', $id)->first();
        $ad->description = $request->description;
        $ad->save();
    }
}
