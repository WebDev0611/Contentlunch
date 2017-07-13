<?php

namespace App\Http\Controllers;

use App\Account;
use App\Connection;
use App\Provider;
use Connections\API\GoogleAnalyticsAPI;
use App\Http\Controllers\Connections\GoogleController;
use Google_Client;
use Google_Service_Books;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use oAuth\API\GoogleAnalyticsAuth;
use oAuth\API\GoogleDriveAuth;
use View;

class ContentScoreController extends BaseController{

    private $gaApi;

    function __construct()
    {
        $this->connectToGaApi();
    }

    public function accounts(){
        // Get the list of accounts for the authorized user.
        $accounts = $this->gaApi->analyticsService->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            //return response()->json([]);
            //return response()->json([$accounts->getItems()[0]]);
            return response()->json($accounts->getItems());
        }else{
            return response()->json(array("error"=>"No Analytics accounts are associated with this Google account."));
        }
    }

    public function properties($accountId){
        $properties = $this->gaApi->analyticsService->management_webproperties->listManagementWebproperties($accountId);
        if (count($properties->getItems()) > 0){
            //return response()->json([]);
            //return response()->json([$properties->getItems()[0]]);
            return response()->json($properties->getItems());
        }else{
            return response()->json(array("error"=>"No Analytics properties found for this Google account."));
        }

    }

    public function profiles($accountId, $propertyId){
        $profiles = $this->gaApi->analyticsService->management_profiles->listManagementProfiles($accountId, $propertyId);

        if (count($profiles->getItems()) > 0) {
            //return response()->json([]);
            //return response()->json([$profiles->getItems()[0]]);
            return response()->json($profiles->getItems());
        }else{
            return response()->json(array("error"=>"No Analytics profiles found for this Google account."));
        }
    }

    public function score($profile_id){

        // Validate Input
        $input = request()->input("all");
        $start_date = !isset($input["start_date"]) ? date('Y-m-d', strtotime('-30 days')) : $input["start_date"];
        $end_date = !isset($input["end_date"]) ? date("Y-m-d") : $input["end_date"];
        $path = !isset($input["path"]) ? "/" : $input["path"];


        $data = $this->gaApi->getProfileData($profile_id, $start_date,  $end_date, $path);
        $totals = $data['totalsForAllResults'];


        $sessions = $totals['ga:sessions'];
        $entrances = $totals['ga:entrances'];
        $exits = $totals['ga:exits'];
        $pageviews = $totals['ga:pageviews'];
        $users = $totals['ga:users'];
        $avgSessionDuration = $totals['ga:avgSessionDuration'];
        $bounceRate = $totals['ga:bounceRate'];
        $organicSearches = $totals['ga:organicSearches'];
        $pageLoadTime = $totals['ga:pageLoadTime'];
        $socialInteractions = $totals['ga:socialInteractions'];

        /*

        From API
                p = pageviews
                u = users
                d = averagerageSessionDuration
                g = organicSearches
                e = entrances
                s = sessions
                x = exits
                b = bounceRate

        Variable
                a = average session Duration from google industry report in seconds. Set at 110.
                r = page view reducer. Set at 100 gives 1000 pageviews within 30 days a score of 10. Can be adjusted if needed.
                n = organic search average percentage from industry report. set at 60.

        ContentLaunch Formula for www.hostmath.com:
        score = \frac{\left(\begin{array}{c}\frac{p+u}{r}\end{array}\right)+\left(\begin{array}{c}\frac{d}{a}.1\end{array}\right)+\left(\begin{array}{c}\frac{g}{\left(\begin{array}{c}\frac{e}{10}\frac{n}{10}\end{array}\right)}\end{array}\right)+\left(\begin{array}{c}\frac{s}{x}-\frac{b}{10}\end{array}\right)}{4}

        */

        // This is base on pageviews mostly. If the view were made by more users, than the score goes up. The pageview
        // number has a lower value when one person was responsible for all the views. 1000 views in 30 days give score of 10.
        $reach = $this->cleanScore(($pageviews + $users) / 100);

        // Here we take the pages' average session duration and put it over the industry standard average session
        // duration from google of 1 minute and 50 seconds. If session duration meets or exceeds the industry standard,
        // then the score is 10. Anything less than than will be docked accordingly.
        $interest = $this->cleanScore($avgSessionDuration / 11); // From google industry standard of 1:50 (110)

        // This calculation is based on how many of the entrances were from organic searches. A perfect score of 10
        // wpi;d occur when 50% of the site's entrances are from organic searches. Anything less will cause a drop
        // in the score.
        $search = $entrances == 0 ? 0 : $this->cleanScore($organicSearches / ($entrances / 10 * 6) * 10);

        // The impact of a single piece of content will be determined by how engaged the user was with the site after
        // coming in contact with this content. Did they leave the site?
        // Sessions over exits minus bounce percentage divided by ten.
        $impact = $exits == 0 ? 0 : $this->cleanScore($sessions / $exits - ($bounceRate / 10));


        return response()->json([
            "reach"=>$reach,
            "interest"=>$interest,
            "search"=>$search,
            "impact"=>$impact,
            "score"=> $this->cleanScore(($impact + $search + $interest + $reach) / 4)
        ]);

    }

    private function cleanScore($score){
        $score = $score > 10 ? 10 : $score;
        $score = $score < 0 ? 0 : $score;
        return round($score, 1, PHP_ROUND_HALF_EVEN);
    }
    



    // Private Members

    private function connectToGaApi(){
        $connectionId = $_COOKIE['gaConnectionId'];

        if($connectionId != null){

            $connection = Account::selectedAccount()
                ->connections()
                ->where("id", "=", $connectionId)
                ->active()
                ->first();

            if (!$connection) {
                return false;
            }

            $this->gaApi = new GoogleAnalyticsAPI(null, $connection);

        } else{

            $connection = Account::selectedAccount()
                ->connections()
                ->where('provider_id', Provider::whereSlug('google-analytics')->first()->id)
                ->active()
                ->first();

            if (!$connection) {
                return false;
            }

            $this->gaApi = new GoogleAnalyticsAPI(null, $connection);
        }
    }
}
