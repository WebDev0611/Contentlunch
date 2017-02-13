<?php

namespace App\Http\Controllers;

use App\WriterAccessPrice;
use DateTime;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use App\WriterAccessBulkOrderStatus;
use App\Jobs\WriterAccessBulkOrder;
use App\User;
use App\WriterAccessPartialOrder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\DTO\Order;
use Illuminate\Support\Facades\Redis;

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
    /**
     * @var string
     */
    private $apiUrl = 'https://writeraccess.com/api';

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
     * @param Request $request
     * @param User $user
     * @param string $apiProject
     * @param int $apiProjectId
     */
    public function __construct(Request $request, $user = null, $apiProject = null, $apiProjectId = null)
    {
        $user = $user ? $user : Auth::user();

        if ($user) {
            // Set the project name for writer access calls
            $this->apiProject = $apiProject ? $apiProject : $this->getProjectName($request->root(), $user);

            // Create the users project if it doesn't already exist.
            if (empty($user->writer_access_Project_id)) {
                $this->createProject();
            }

            // Set the projectid for writer access calls
            $this->apiProjectId = $apiProjectId ? $apiProjectId : $user->writer_access_Project_id;
        }
    }

    private function getProjectName($requestRoot, User $user)
    {
        return preg_replace('#https?://#', '', $requestRoot) . '-user-' . $user->id;
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
        // Intercept and take the bulk order path.
        if($order->content_title === "wa-bulk-order" && $order->instructions === "wa-bulk-order"){
            return $this->bulkOrderSubmit($request, $order);
        }

        $validation = $this->validateCard($request->all());

        if ($validation->fails()) {
            return redirect()->route('orderReview', $order)->with('errors', $validation->errors());
        }

        $responseContent = $this->createWriterAccessOrder($order);

        if (isset($responseContent->fault)) {
            return $this->redirectToOrderReview($order, $responseContent->fault, 'danger');
        }

        $this->initStripe();
        $customer = $this->createStripeCustomer($request);

        try {
            $charge = $this->createStripeCharge($customer, $order);
        } catch (\Exception $e) {
            return $this->redirectToOrderReview($order, $e->getMessage(), 'danger');
        }

        return redirect()
            ->route('contentIndex')
            ->with([
                'flash_message' => 'Payment successful. Your order is being processed.',
                'flash_message_type' => 'success'
            ]);
    }

    protected function redirectToOrderReview($order, $message, $message_type = 'success')
    {
        return redirect()
            ->route('orderReview', $order)
            ->with([
                'flash_message' => $message,
                'flash_message_type' => $message_type,
            ]);
    }

    protected function createStripeCharge($customer, WriterAccessPartialOrder $order)
    {
        return \Stripe\Charge::create([
            'customer' => $customer->id,
            'amount' => $order->price * 100,
            'currency' => 'usd'
        ]);
    }

    protected function createStripeCustomer(Request $request)
    {
        $stripeToken = $request->input('stripe-token');

        return \Stripe\Customer::create([
            'email' => Auth::user()->email,
            'source' => $stripeToken
        ]);
    }

    protected function createWriterAccessOrder(WriterAccessPartialOrder $order)
    {
        $params = array_merge($this->projectInfo(), $order->writerAccessFormat());
        $response = $this->post('/orders', $params);

        return json_decode($response->getContent());
    }

    private function validateCard(array $data)
    {
        return Validator::make($data, [
            'stripe-token' => 'required'
        ]);
    }

    public function projectInfo()
    {
        return [
            'apiProject' => $this->apiProject,
            'projectId' => $this->apiProjectId
        ];
    }

    public function createOrder($orderDetails = null)
    {
        if(!$orderDetails){
            $orderDetails = $_POST;
        }

        $errors = [];
        $order = new Order();

        $order->setProjectid($this->apiProjectId);

        $this->validateOrderDetails($order, $errors, $orderDetails);

        // Stop here if we found errors
        if (count($errors) > 0) {
            $errors['debug'] = $order->toArray();
            return array(['errors' => $errors]);
        }

        // Get/create stripe customer
        $customer = \Stripe\Customer::create(array(
            'email' => Auth::user()->email,
            'source' => $order->getStripeToken(),
        ));

        // Try to charge the card
        $charge = null;
        try {
            $charge = \Stripe\Charge::create(array(
                'customer' => $customer->id,
                'amount' => $order->getPrice() * 100, // Stripe processes cents for the ammount
                'currency' => 'usd',
            ));
        } catch (Stripe_CardError $e) {
            $errors['Payment Declined'] = 'Your card was declined, please try another card to complete the order.';
            $errors['Stripe Charge'] = $charge;
        }

        // Stop here if we find errors
        if (count($errors) > 0) {
            $errors['debug'] = $order->toArray();
            return array(['errors' => $errors]);
        }

        if(env("WRITER_ACCESS_TEST_WRITER_ID", false)){
            $params['targetwriter'] = env("WRITER_ACCESS_TEST_WRITER_ID");
        }

        // NOW THAT ALL THE DATA LOOKS GOOD, LET'S TRY TO CREATE THE ORDER
        $response = $this->post('/orders', array_merge($order->toArray()));
        $responseContent = json_decode($response->getContent());

        if (isset($responseContent->fault)) {
            $errors['writeraccess_fault'] = $responseContent->fault;
        }

        // Stop here if we find errors
        if (count($errors) > 0) {
            $errors['debug'] = $order->toArray();
            return array(['errors' => $errors]);
        }

        return response()->json($response->getContent());
    }

    public function queuedOrderSubmit(Order $order)
    {
        try{
            $response = $this->post('/orders', array_merge($order->toArray()));

            $responseContent = json_decode($response->getContent());
        }catch(Exception $e){
            echo $e->getMessage();
            echo $e->getStack();
        }

        return $responseContent;
    }

    private function createDueDate($date)
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

        $response = $this->post('/projects', [ 'projectname' => $this->apiProject ]);
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

    public function bulkOrderSubmit(Request $request, WriterAccessPartialOrder $orderDetails){

        try{

            $user = Auth::user();
            $orders = [];
            $price = 0.00;

            if(file_exists($orderDetails['bulk_file'])){
                $stripeToken = $request->input('stripe-token', false);

                if(!$stripeToken){
                    throw new Exception("Missing Stripe Token.");
                }

                $uploadRows = Excel::load($orderDetails['bulk_file'])->get();
                foreach($uploadRows as $row){

                    $order = new Order();
                    if(env("WRITER_ACCESS_TEST_WRITER_ID", false)){
                        $order->setTargetwriter(env("WRITER_ACCESS_TEST_WRITER_ID"));
                    }
                    $errors = [];
                    $tmpOrderArray = $orderDetails->toArray();
                    $tmpOrderArray['projectid'] = $this->apiProjectId;
                    $tmpOrderArray['title'] = $row->content_title;
                    $tmpOrderArray['instructions'] = $row->instructions;
                    $this->validateOrderDetails($order, $errors, $tmpOrderArray);
                    if(count($errors) === 0){
                        $orders[] = $order;
                        $price = $price + $order->getPrice();
                    }
                }

            }else{
                $error = new Exception();
                return response()->json(array(
                    "error" => "Bulk import file not found: ".$orderDetails['bulk_file'],
                    "orderDetails" => $orderDetails,
                    "stack" => $error->getTrace()
                ));
            }

            //return response()->json($orders);

            $bulkOrderStatus =  WriterAccessBulkOrderStatus::create();

            $job = (new WriterAccessBulkOrder($bulkOrderStatus->id, $user, $orders, $this->apiProject, $this->apiProjectId, $stripeToken, Config::get('services.stripe.secret'), $price));

            $this->dispatch($job);

            return redirect()
                ->to('/writeraccess/bulk-order/'.$bulkOrderStatus->id)
                ->with("orders", $orders);

        }catch(Exception $e){
            return response()->json(array(
                "error" => $e->getMessage(),
                "stack" => $e->getTrace()
            ));
        }
    }

    public function deleteOrder($id){
        return $this->delete('/orders/'.$id);
    }

    /**
     * @param $apiPath
     *
     * @return Response
     */
    private function get($apiPath)
    {
        $url = $this->apiUrl.$apiPath;

        $redis_key = $url;
        $redis_cache = Redis::get( $redis_key );
        if( empty( unserialize($redis_cache) && !isset($_GET['fresh']) ) ){
            $curl = $this->init_curl();
            curl_setopt($curl, CURLOPT_URL, $url);
            $output = curl_exec($curl);
            curl_close($curl);

            Redis::set($redis_key, serialize( $output ));
            Redis::expire($redis_key, 60*20); //set cache for 20 min
        }else{
            $output = unserialize($redis_cache);
        }


        if($output === false){
            return (new Response(["error"=>"API call failed."]))->header('Content-Type', 'application/json');
        }else {
            return (new Response($output))->header('Content-Type', 'application/json');
        }
    }

    /**
     * @param $apiPath
     * @param array $postFields
     *
     * @return Response
     */
    private function post($apiPath, $postFields = null, $cache_key = null)
    {
        $url = $this->apiUrl.$apiPath;
        $fields_string = '';

        echo "\nURL:\n".$url."\n\n";

        if (isset($postFields)) {
            foreach ($postFields as $key => $value) {
                $fields_string .= $key . '=' . $value . '&';
            }
        }

        $redis_key = $url.$cache_key;

        $redis_cache = Redis::get( $redis_key );
        if( empty( unserialize($redis_cache) ) ){

            $curl = $this->init_curl();
            curl_setopt($curl, CURLOPT_URL, $url);

            if (isset($postFields)) {
                rtrim($fields_string, '&');
                curl_setopt($curl, CURLOPT_POST, count($postFields));
                curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
            }

            $output = curl_exec($curl);
            echo "\n\nOutput\n";
            echo json_encode($fields_string)."\n";
            echo $output."\n";
            curl_close($curl);

            Redis::set($redis_key, serialize( $output ));
            Redis::expire($redis_key, 0); //Set cache with no expiration. We never want to post the same info twice.
        }else{
            $output = unserialize($redis_cache);
        }

        if($output === false){
            return (new Response(["error"=>"API call failed."]))->header('Content-Type', 'application/json');
        }else{
            return (new Response($output))->header('Content-Type', 'application/json');
        }

    }

    /**
     * @param $apiPath
     *
     * @return Response
     */
    private function delete($apiPath)
    {
        $curl = $this->init_curl();
        curl_setopt($curl, CURLOPT_URL, $this->apiUrl.$apiPath);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $output = curl_exec($curl);
        curl_close($curl);

        if($output === false){
            return (new Response(["error"=>"API call failed."]))->header('Content-Type', 'application/json');
        }else{
            return (new Response($output))->header('Content-Type', 'application/json');
        }
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

    /**
     * Validates order details and modifies the passed $order and $errors accordingly.
     * @param Order $order
     * @param array $errors
     * @param array $orderDetails
     */
    private function validateOrderDetails(Order &$order, array &$errors, array $orderDetails){
        // Validate projectid
        if (!isset($orderDetails['projectid'])) {
            $errors['projectid'] = "Missing required parameter 'projectid'.";
        } else {
            $order->setProjectid(intval($orderDetails['projectid']));
        }

        // Validate asset type
        if (!isset($orderDetails['asset_type_id'])) {
            $errors['asset_type_id'] = "Missing required parameter 'assetType'.";
        } else {
            $order->setAssetid(intval($orderDetails['asset_type_id']));
        }

        // Validate wordcount
        if (!isset($orderDetails['wordcount'])) {
            $errors['wordcount'] = "Missing required parameter 'wordcount'.";
        } else {
            $wordcount = intval($orderDetails['wordcount']);
            $order->setMinwords(($wordcount - ($wordcount * .1)) < 50 ? 50 : $wordcount - ($wordcount * .1));
            $order->setMaxwords($wordcount + ($wordcount * .1));
        }

        // validate writer_level
        if (!isset($orderDetails['writer_level'])) {
            $errors['writer_level'] = "Missing required parameter 'writer_level'.";
        } else {
            $order->setWriter(intval($orderDetails['writer_level']));
        }

        // If assetType, wordcount, and writer_level are present, then try to get the price for the order.
        $writerAccessPrice = !isset($errors['asset_type_id'], $errors['wordcount'], $errors['writer_level'])
            ? $writerAccessPrice = WriterAccessPrice::where('asset_type_id', $orderDetails['asset_type_id'])
                ->where('writer_level', $orderDetails['writer_level'])
                ->where('wordcount', $orderDetails['wordcount'])
                ->first()
            : null
        ;

        // Validate that a price was found.
        if ($writerAccessPrice) {
            $order->setPrice($writerAccessPrice->fee);
        } else {
            $errors['price'] = 'Error processing form. Please try again later.';
        }

        // Validate duedate
        if (!isset($orderDetails['duedate'])) {
            $errors['duedate'] = "Missing required parameter 'duedate'.";
        } else {
            $order->setHourstocomplete(intval($this->createDueDate($orderDetails['duedate'])));
        }

        // Validate title
        if (!isset($orderDetails['title'])) {
            $errors['title'] = "Missing required parameter 'title'.";
        } else {
            $order->setTitle($orderDetails['title']);
        }

        // Validate instructions
        if (!isset($orderDetails['instructions'])) {
            $errors['instructions'] = "Missing required parameter 'instructions'.";
        } elseif (!isset($orderDetails['target_audience'])) {
            $errors['target_audience'] = "Missing required parameter 'target_audience'.";
        } elseif (!isset($orderDetails['tone_of_writing'])) {
            $errors['tone_of_writing'] = "Missing required parameter 'tone_of_writing'.";
        } elseif (!isset($orderDetails['narrative_voice'])) {
            $errors['narrative_voice'] = "Missing required parameter 'narrative_voice'.";
        } else {
            $order->setInstructions(
                $orderDetails['instructions'] .
                "\nTarget Audience: \n".$orderDetails['target_audience'] .
                "\nTone of Writing: \n".$orderDetails['tone_of_writing'] .
                "\nNarrative Voice: \n".$orderDetails['narrative_voice']
            );
        }
    }
}
