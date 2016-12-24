<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\WriterAccessPartialOrder;
use Validator;
use Config;

/**
 * Class WriterAccessController.
 */
class WriterAccessController extends Controller
{
    /**
     * @var string
     */
    private $apiUsername = 'jon@contentlaunch.com';
    /**
     * @var string
     */
    private $apiPassword = '4002d7683bdb5fadde467314bdd477f6';
    /**
     * @var string
     */
    private $apiProject = '';
    /**
     * @var string
     */
    private $apiProjectId = '';

    private function initStripe()
    {
        $stripe = [
            'secret_key' => Config::get('services.stripe.secret'),
            'publishable_key' => Config::get('services.stripe.key'),
        ];

        Stripe::setApiKey($stripe['secret_key']);
    }

    /**
     * WriterAccessController constructor.
     */
    public function __construct(Request $request)
    {
        // Set the project name for writer access calls
        $this->apiProject = $this->getProjectName($request->root());

        // Create the users project if it doesn't already exist.
        if (empty(Auth::user()->writer_access_Project_id)) {
            echo 'Creating project';
            $this->createProject();
        }

        // Set the projectid for writer access calls
        $this->apiProjectId = Auth::user()->writer_access_Project_id;
    }

    private function getProjectName($requestRoot)
    {
        return preg_replace('#https?://#', '', $requestRoot) . '-user-' . Auth::user()->id;
    }

    /**
     * @return Response
     */
    public function categories()
    {
        return $this->get('/categories');
    }

    /**
     * @return Response
     */
    public function account()
    {
        return $this->get('/account');
    }

    /**
     * @return Response
     */
    public function assetTypes()
    {
        return $this->get('/assets');
    }

    /**
     * @param null $id
     *
     * @return Response
     */
    public function orders($id = null)
    {
        $parameters = [];
        $url = null;
        $queryString = '';
        $parameters['project'] = $this->apiProjectId;

        if (isset($_GET['status'])) {
            if (isset($_GET['status'])) {
                $parameters['status'] = $_GET['status'];
            }
        }

        if (count($parameters > 0)) {
            $queryString = '?'.http_build_query($parameters);
        }

        if (isset($id)) {
            $url = '/orders/'.$id.$queryString;
        } else {
            $url = '/orders'.$queryString;
        }

        return $this->get($url);
    }

    public function orderSubmit(Request $request, WriterAccessPartialOrder $order)
    {
        $validation = $this->validateCard($request->all());

        if ($validation->fails()) {
            return redirect()
                ->route('orderReview', $order)
                ->with('errors', $validation->errors());
        }

        $params = array_merge($this->projectInfo(), $order->writerAccessFormat());

        $response = $this->post('/orders', $params);
        $responseContent = json_decode($response->getContent());

        if (isset($responseContent->fault)) {
            $writerAccessErrors = [ $responseContent->fault ];

            return redirect()
                ->route('orderReview', $order)
                ->with([
                    'flash_message' => $responseContent->fault,
                    'flash_message_type' => 'danger',
                ]);
        }

        $this->initStripe();
        $stripeToken = $request->input('stripeToken');
        $orderPrice = $order->price * 100;

        $customer = \Stripe\Customer::create([
            'email' => Auth::user()->email,
            'source' => $stripeToken
        ]);

        try {
            $charge = \Stripe\Charge::create([
                'customer' => $customer->id,
                'amount' => $orderPrice,
                'currency' => 'usd'
            ]);
        } catch (\Exception $e) {
            return redirect()
                ->route('orderReview', $order)
                ->with([
                    'flash_message' => $e->getMessage(),
                    'flash_message_type' => 'danger'
                ]);
        }

        return redirect()
            ->route('contentIndex')
            ->with([
                'flash_message' => 'Payment successful. Your order is being processed.',
                'flash_message_type' => 'success'
            ]);
    }

    private function validateCard(array $data)
    {
        return Validator::make($data, [
            'stripeToken' => 'required'
        ]);
    }

    public function projectInfo()
    {
        return [
            'apiProject' => $this->apiProject,
            'projectId' => $this->apiProjectId
        ];
    }

    public function createOrder()
    {
        $params = $this->projectInfo();

        $errors = [];

        // Validate Token
        if (isset($_POST['stripeToken'])) {
            $token = $_POST['stripeToken'];
        } else {
            $errors['token'] = 'The order cannot be processed. You have not been charged.
                        Please confirm that you have JavaScript enabled and try again.';
        }

        // Validate Post Data
        if (!isset($_POST['assetType'])) {
            $errors['assetType'] = "Missing required parameter 'assetType'.";
        } else {
            $params['assetType'] = $_POST['assetType'];
        }

        if (!isset($_POST['wordcount'])) {
            $errors['wordcount'] = "Missing required parameter 'wordcount'.";
        } else {
            $wordcount = intval($_POST['wordcount']);
            $params['minwords'] = $wordcount - ($wordcount * .1);
            $params['maxwords'] = $wordcount + ($wordcount * .1);
        }

        if (!isset($_POST['writer_level'])) {
            $errors['writer_level'] = "Missing required parameter 'writer_level'.";
        } else {
            $params['writer'] = $_POST['writer_level'];
        }

        if (!isset($_POST['duedate'])) {
            $errors['duedate'] = "Missing required parameter 'duedate'.";
        } else {
            $params['hourstocomplete'] = $this->createDueDate($_POST['duedate']);
        }

        if (!isset($_POST['title'])) {
            $errors['title'] = "Missing required parameter 'title'.";
        } else {
            $params['title'] = $_POST['title'];
        }

        if (!isset($_POST['instructions'])) {
            $errors['instructions'] = "Missing required parameter 'instructions'.";
        } else {
            $params['instructions'] = $_POST['instructions'];
        }

        if (!isset($_POST['target'])) {
            $errors['target'] = "Missing required parameter 'target'.";
        } else {
            $params['instructions'] .= "\nTarget Audience: \n".$_POST['target'];
        }

        if (!isset($_POST['tone'])) {
            $errors['tone'] = "Missing required parameter 'tone'.";
        } else {
            $params['instructions'] .= "\nTone of Writing: \n".$_POST['tone'];
        }

        if (!isset($_POST['voice'])) {
            $errors['voice'] = "Missing required parameter 'voice'.";
        } else {
            $params['instructions'] .= "\nNarrative Voice: \n".$_POST['voice'];
        }

        // Get the price of the order (never trust this part coming from the front end)
        $price = null;
        if ($WriterAccessPrice = WriterAccessPrice::where('asset_type_id', $params['assetType'])
            ->where('writer_level', $params['writer'])
            ->where('wordcount', $_POST['wordcount'])
            ->first()) {
            $price = $WriterAccessPrice->fee;
        } else {
            $errors['generic'] = 'Error processing form. Please try again later.';
        }

        /*
        Writer Access API Requried Param Checklist:
            [√] projectid
            [√] hourstocomplete
            [√] writer
            [√] minwords
            [√] maxwords
            [√] title
            [√] instructions

        Sample Data:
            {
                "assetType": 0,
                "wordcount": "500",
                "writer_level": "5",
                "duedate": "10/18/2016",
                "title": "Test - Order",
                "target": "Everyone",
                "instructions": "Please ignore this order",
                "tone": "Writing tone goes here",
                "Voice": "Professional on the topic"
            }

        */

        // Stop here if we find errors
        if (count($errors) > 0) {
            $errors['debug'] = $params;

            return array(['errors' => $errors]);
        }

        // NOW THAT ALL THE DATA LOOKS GOOD, LET'S TRY TO CREATE THE ORDER
        $response = $this->post('/orders', array_merge($params));
        $responseContent = json_decode($response->getContent());

        if (isset($responseContent->fault)) {
            $errors['writeraccess_fault'] = $responseContent->fault;
        }

        // Stop here if we find errors
        if (count($errors) > 0) {
            $errors['debug'] = $params;

            return array(['errors' => $errors]);
        }

        // Get/create stripe customer
        $customer = \Stripe\Customer::create(array(
            'email' => Auth::user()->email,
            'source' => $token,
        ));

        // Try to charge the card
        try {
            $charge = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount' => $price * 100, // Stripe processes cents for the ammount
                'currency' => 'usd',
            ));
        } catch (Stripe_CardError $e) {
            $errors['Payment Declined'] = 'Your card was declined, please try another card to complete the order.';
        }

        // Stop here if we find errors
        if (count($errors) > 0) {
            $errors['debug'] = $params;

            return array(['errors' => $errors]);
        }

        return $this->post('/orders', array_merge($params));
    }

    public function createDueDate($date)
    {
        $today = new DateTime(date('Y-m-d H:i:s'));
        $duedate = new DateTime(date($date));

        $diff = $duedate->diff($today);

        $hours = $diff->h;
        $hours = $hours + ($diff->days * 24);

        // NOTE: WriterAccess expects to see 4, 12, or increments of 24 hours.
        // We are only going to worry about full days or a half day if submitted
        // for next day duedates.

        //round down to the nearest 24 hours
        $hours = $hours - $hours % 24;

        //Set $hours to 12 if rounding down == 0
        $hours = $hours == 0 ? 12 : $hours;

        return $hours;
    }

    /**
     * Creates a project in WriterAccess for the current user and stores the project's id as writer_access_Project_id.
     */
    private function createProject()
    {
        $user = User::find(Auth::user()->id);

        $response = $this->post('/projects', array('projectname' => $this->apiProject));
        $responseContent = json_decode($response->getContent());

        if (!isset($responseContent->fault)) {
            $user->writer_access_Project_id = $responseContent->projects[0]->id;
            $user->save();
        } else {
            header('Content-type: application/json');
            echo '['.$response->getContent().',{projectname:'.$this->apiProject.',message: "Project did not exist, but failed to create it."}]';
            die();
        }
    }

    /**
     * @return Response
     */
    public function projects()
    {
        return $this->get('/projects/'.$this->apiProjectId);
    }

    /**
     * @return Response
     */
    public function expertises()
    {
        return $this->get('/expertises');
    }

    /**
     * @param $apiPath
     *
     * @return Response
     */
    private function get($apiPath)
    {
        $curl = $this->init_curl();
        curl_setopt($curl, CURLOPT_URL, 'https://writeraccess.com/api'.$apiPath);
        $output = curl_exec($curl);
        curl_close($curl);

        return (new Response($output))->header('Content-Type', 'application/json');
    }

    /**
     * @param $apiPath
     * @param null $postFields
     *
     * @return Response
     */
    private function post($apiPath, $postFields = null)
    {
        $curl = $this->init_curl();
        curl_setopt($curl, CURLOPT_URL, 'https://writeraccess.com/api'.$apiPath);

        if (isset($postFields)) {
            $fields_string = '';
            foreach ($postFields as $key => $value) {
                $fields_string .= $key.'='.$value.'&';
            }
            rtrim($fields_string, '&');
            curl_setopt($curl, CURLOPT_POST, count($postFields));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
        }

        $output = curl_exec($curl);
        curl_close($curl);

        return (new Response($output))->header('Content-Type', 'application/json');
    }

    /**
     * @param $apiPath
     *
     * @return Response
     */
    private function delete($apiPath)
    {
        $curl = $this->init_curl();
        curl_setopt($curl, CURLOPT_URL, 'https://writeraccess.com/api'.$apiPath);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $output = curl_exec($curl);
        curl_close($curl);

        return (new Response($output))->header('Content-Type', 'application/json');
    }

    /**
     * @return resource
     */
    private function init_curl()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_USERPWD,  $this->apiUsername.':'.$this->apiPassword);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json'));

        return $curl;
    }

    /**
     * @return string
     */
    public function getApiProjectId()
    {
        return $this->apiProjectId;
    }

    /**
     * @param string $apiProjectId
     */
    public function setApiProjectId($apiProjectId)
    {
        $this->apiProjectId = $apiProjectId;
    }

    /**
     * @return string
     */
    public function getApiProject()
    {
        return $this->apiProject;
    }

    /**
     * @param string $apiProject
     */
    public function setApiProject($apiProject)
    {
        $this->apiProject = $apiProject;
    }
}
