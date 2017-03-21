<?php

namespace App\Http\Controllers\Connections;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\Http\Controllers\Controller;
use App\Connection;
use App\Provider;
use App\Account;

abstract class BaseConnectionController extends Controller
{
    protected $isOnboarding;

    public function __construct(Request $request)
    {
        if ($request->input('onboarding')) {
            $this->isOnboarding = true;
        } else {
            $this->isOnboarding = session('redirect_route') == 'onboardingConnect';
        }
    }

    public function getSessionConnection()
    {
        $connection_data = Session::get('connection_data');
        $connection = Connection::find($connection_data['connection_id']);

        return $connection;
    }

    public function deleteSessionConnection()
    {
        return $this->getSessionConnection()->delete();
    }

    public function getSessionConnectionMetadata()
    {
        $connectionData = Session::get('connection_data');

        return $connectionData ? $connectionData['meta_data'] : null;
    }

    protected function flashMessage($message, $type = 'success')
    {
        return [
            'flash_message' => $message,
            'flash_message_type' => $type,
            'flash_message_important' => true,
        ];
    }

    protected function redirectWithSuccess($message)
    {
        return redirect()->route($this->redirectRoute())->with([
            'flash_message' => $message,
            'flash_message_type' => 'success',
            'flash_message_important' => true,
        ]);
    }

    protected function redirectWithError($message)
    {
        return redirect()->route($this->redirectRoute())->with([
            'flash_message' => $message,
            'flash_message_type' => 'danger',
            'flash_message_important' => true,
        ]);
    }

    public function cleanSessionConnection()
    {
        $connection = $this->getSessionConnection();
        if ($connection) {
            $connection->delete();
        }
        Session::forget('connection_data');
    }

    protected function redirectRoute()
    {
        $redirectUrl = Session::get('redirect_route');
        Session::forget('redirect_route');

        return $redirectUrl ? $redirectUrl : 'connections.index';
    }

    protected function saveConnection(array $settings, $providerSlug, $activateAccount = true)
    {
        $jsonEncodedSettings = json_encode($settings);
        $connection = $this->getSessionConnection();

        if (!$connection) {
            $provider = Provider::findBySlug($providerSlug);
            $createArray = [
                'name' => $provider->name . ' Connection',
                'settings' => $jsonEncodedSettings,
                'provider_id' => $provider->id,
                'user_id' => Auth::user()->id,
            ];
            $createArray = $this->activateConnection($createArray, $activateAccount);
            $connection = Account::selectedAccount()->connections()->create($createArray);
        } else {
            $updateArray = $this->activateConnection([ 'settings' => $jsonEncodedSettings ], $activateAccount);
            $connection->update($updateArray);
        }

        return $connection;
    }

    private function activateConnection(array $connectionData, $activateAccount = true)
    {
        if ($activateAccount) {
            $connectionData = collect([ 'active' => true, 'successful' => true ])->merge($connectionData)->toArray();
        }

        return $connectionData;
    }
}
