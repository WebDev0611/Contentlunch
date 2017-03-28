<?php

namespace App\Http\Controllers;

use App\WriterAccessPrice;
use DateTime;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\ApiRequestor;
use Stripe\HttpClient\CurlClient;
use Stripe\Customer;
use Stripe\Charge;
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
        Stripe::setApiKey(Config::get('services.stripe.secret'));
        ApiRequestor::setHttpClient(new CurlClient(array(CURLOPT_PROXY => '')));
    }

    /**
     * Try to read the stripe customer ID from the user table before creating one.
     * @param String $stripeToken
     * @return string
     */
    private function getStripeCustomer(String $stripeToken){

        $user = Auth::user();
        if($user->stripe_customer_id !== null){
            $customerId = $user->stripe_customer_id;
        }else{
            $customer = Customer::create(array(
                'email' => $user->email,
                'source' => $stripeToken,
            ));

            $customerId = $customer->id;
            $user->stripe_customer_id = $customerId;
            $user->save();
        }

        return $customerId;
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
            $this->apiProjectId = $apiProjectId ? $apiProjectId : $user->writer_access_Project_id === "zero" ? 0 : $user->writer_access_Project_id;
        }
    }

    private function getProjectName($requestRoot, User $user)
    {
        return preg_replace('#https?://#', '', $requestRoot) . '-user-' . $user->id . "-hash-".substr(md5(microtime()),rand(0,26),5);;
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

        if($this->apiProjectId !== 0){
            $parameters['project'] = $this->getApiProjectId();
        }else{
            $this->createProject();
            $parameters['project'] = $this->getApiProjectId();
        }

        if (isset($_GET['status'])) {
            if (isset($_GET['status'])) {
                $parameters['status'] = $_GET['status'];
            }
        }

        if (count($parameters) > 0) {
            $queryString = '?'.http_build_query($parameters);
        }

        if (isset($id)) {
            return $this->post('/orders/'.$id."/previewfull");
        } else {
            return $this->get('/orders'.$queryString);
        }
    }

    /**
     * Return an array of comment for the order id passed.
     * @param $id
     * @return Response
     */
    public function comments($id)
    {
        return $this->get('/orders/'.$id."/comments");
    }

    public function postComment(Request $request, $id)
    {
        $comment = $request->input("comment");
        return $this->post('/orders/'.$id."?action=revise", ["notes"=>$comment]);
    }

    public function orderApprove(Request $request, $id)
    {
        $data = json_decode(utf8_decode($this->post('/orders/'.$id."?action=approve")->content()));

        if(isset($data->fault)){
            return redirect()
                ->route('contentOrder', $id)
                ->with([
                    'flash_message' => $data->fault,
                    'flash_message_type' => "danger",
                ]);
        }else{
            return redirect()
                ->route('contentOrder', $id)
                ->with([
                    'flash_message' => "Order has been approved successfully.",
                    'flash_message_type' => "success",
                ]);
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


    public function orderSubmit(Request $request, WriterAccessPartialOrder $partialOrder)
    {
        // Intercept and take the bulk order path.
        if($partialOrder->order_count > 1){
            return $this->bulkOrderSubmit($request, $partialOrder);
        }

        // Make and validate the order object
        $order = new Order();
        $errors = [];

        if(env("WRITER_ACCESS_TEST_WRITER_ID", false)){
            $order->setTargetwriter(env("WRITER_ACCESS_TEST_WRITER_ID"));
        }

        $tmpOrderArray = $partialOrder->toArray();
        $tmpOrderArray['projectid'] = $this->apiProjectId;
        $tmpOrderArray['title'] = $tmpOrderArray['content_title'];
        $this->validateOrderDetails($order, $errors, $tmpOrderArray);

        if(count($errors) !== 0){
            return $this->redirectToOrderReview($partialOrder, implode("<br />", $errors), 'danger');
        }

        // Validate the stripe token
        $validation = $this->validateCard($request->all());

        if ($validation->fails()) {
            return redirect()->route('orderReview', $partialOrder)->with('errors', $validation->errors());
        }

        // Try to charge their card before making the order...
        $this->initStripe();
        $customer = $this->createStripeCustomer($request);

        try {
            $charge = $this->createStripeCharge($customer, $order);
        } catch (\Exception $e) {
            return $this->redirectToOrderReview($partialOrder, $e->getMessage(), 'danger');
        }

        if($charge->status !== "succeeded"){
            return $this->redirectToOrderReview($partialOrder, "We're sorry, your card was declined. Please try another card.", 'danger');
        }

        // Now that they've paid, lets create the order.
        $response = $this->post('/orders', $order->toArray());
        $responseContent = json_decode($response->getContent());

        if (isset($responseContent->fault)) {
            $errorData['user_name'] = Auth::user()->name;
            $errorData['user_email'] = Auth::user()->email;
            $errorData['acc_id'] = Auth::user()->selectedAccount->id;
            $errorData['api_response'] = $responseContent->fault;
            Mail::send('emails.writeraccess_error', ['data' => $errorData], function($message) {
                $message->from("no-reply@contentlaunch.com", "Content Launch")
                    ->to('jon@contentlaunch.com')
                    ->subject('WriterAccess API error occurred');
            });

            $errorMsg = 'An error occurred while trying to place your order. Please contact ContentLaunch support for more info. Thanks.';
            return $this->redirectToOrderReview($partialOrder, $errorMsg, 'danger');
        }

        return redirect()
            ->route('content_orders.index', ['fresh' => true])
            ->with([
                'flash_message' => 'Payment successful. Your order is complete.',
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

    protected function createStripeCharge($customer, Order $order)
    {
        return \Stripe\Charge::create([
            'customer' => $customer->id,
            'amount' => $order->getPrice() * 100,
            'currency' => 'usd',
            'description' => 'ContentLaunch Order',
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
        $user = Auth::user();

        $response = $this->post('/projects', [ 'projectname' => $this->apiProject ]);
        $responseContent = json_decode($response->getContent());

        if (!isset($responseContent->fault)) {
            $this->apiProjectId = $responseContent->projects[0]->id;
            $user->writer_access_Project_id = $this->apiProjectId;
            $user->save();
        } else {
            header('Content-type: application/json');
            echo '['.$response->getContent().',{projectname:'.$this->apiProject.',message: "Project did not exist, but failed to create it."}]';
            die();
        }
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

            $bulkOrderStatus =  WriterAccessBulkOrderStatus::create();

            $job = (new WriterAccessBulkOrder($bulkOrderStatus->id, $user, $orders, $this->apiProject, $this->apiProjectId, $stripeToken, Config::get('services.stripe.secret'), $price));

            $this->dispatch($job);

            return redirect()
                ->to('/get_content_written/bulk-order/'.$bulkOrderStatus->id)
                ->with("orders", $orders);

        }catch(Exception $e){
            return redirect()->route('orderReview', $order)->with('errors', $e->getMessage());
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

        if (isset($postFields)) {
            foreach ($postFields as $key => $value) {
                $fields_string .= $key . '=' . url_encode($value) . '&';
            }
            rtrim($fields_string, '&');
        }

        $redis_key = $url.$cache_key;

        $redis_cache = Redis::get( $redis_key );
        if( empty( unserialize($redis_cache) ) ){

            $curl = $this->init_curl();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POST => count($postFields),
                CURLOPT_POSTFIELDS => $fields_string,
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "authorization: Basic am9uQGNvbnRlbnRsYXVuY2guY29tOjQwMDJkNzY4M2JkYjVmYWRkZTQ2NzMxNGJkZDQ3N2Y2",
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));


            $output = curl_exec($curl);
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
        curl_setopt($curl, CURLOPT_PROXY, '');
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
            $errors[] = "Missing required parameter 'projectid'.";
        } else {
            $order->setProjectid(intval($orderDetails['projectid']));
        }

        // Validate asset type
        if (!isset($orderDetails['asset_type_id'])) {
            $errors[] = "Missing required parameter 'assetType'.";
        } else {
            $order->setAssetid(intval($orderDetails['asset_type_id']));
        }

        // Validate wordcount
        if (!isset($orderDetails['wordcount'])) {
            $errors[] = "Missing required parameter 'wordcount'.";
        } else {
            $wordcount = intval($orderDetails['wordcount']);
            $order->setMinwords(($wordcount - ($wordcount * .1)) < 50 ? 50 : $wordcount - ($wordcount * .1));
            $order->setMaxwords($wordcount + ($wordcount * .1));
        }

        // validate writer_level
        if (!isset($orderDetails['writer_level'])) {
            $errors[] = "Missing required parameter 'writer_level'.";
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
            $errors[] = 'Error processing form. Please try again later.';
        }

        // Validate duedate
        if (!isset($orderDetails['duedate'])) {
            $errors[] = "Missing required parameter 'duedate'.";
        } else {
            $order->setHourstocomplete(intval($this->createDueDate($orderDetails['duedate'])));
        }

        // Validate title
        if (!isset($orderDetails['title'])) {
            $errors[] = "Missing required parameter 'title'.";
        } else {
            $order->setTitle($orderDetails['title']);
        }

        // Validate instructions
        if (!isset($orderDetails['instructions'])) {
            $errors[] = "Missing required parameter 'instructions'.";
        } elseif (!isset($orderDetails['target_audience'])) {
            $errors[] = "Missing required parameter 'target_audience'.";
        } elseif (!isset($orderDetails['tone_of_writing'])) {
            $errors[] = "Missing required parameter 'tone_of_writing'.";
        } elseif (!isset($orderDetails['narrative_voice'])) {
            $errors[] = "Missing required parameter 'narrative_voice'.";
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
