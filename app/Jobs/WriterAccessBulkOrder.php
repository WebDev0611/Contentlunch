<?php

namespace App\Jobs;

use App\Http\Controllers\WriterAccessController;
use Exception;
use Illuminate\Http\Request;
use App\User;
use App\WriterAccessBulkOrderStatus;
use App\DTO\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Stripe;
use Illuminate\Support\Facades\Config;

class WriterAccessBulkOrder extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    private $bulkOrderStatus;
    private $user;
    private $orders;
    private $apiProject;
    private $apiProjectId;
    private $failedOrders = [];
    private $stripeToken;
    private $stripeApiKey;
    private $totalOrderPrice;

    /**
     * WriterAccessBulkOrder constructor.
     * @param Integer $bulkOrderStatusId
     * @param User $user
     * @param Order[] $orders
     * @param Request $originalRequest
     */
    public function __construct($bulkOrderStatusId, User $user, array $orders, $apiProject = null, $apiProjectId = null, $stripeToken, $stripeApiKey, $totalOrderPrice){
        $this->bulkOrderStatus = WriterAccessBulkOrderStatus::find($bulkOrderStatusId);
        $this->user = $user;
        $this->orders = $orders;
        $this->apiProject = $apiProject;
        $this->apiProjectId = $apiProjectId;
        $this->stripeToken = $stripeToken;
        $this->stripeApiKey = $stripeApiKey;
        $this->totalOrderPrice = $totalOrderPrice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        echo "\nHandling the Bulk order\n";

        if(count($this->orders) === 0){
            echo "\nNo orders found.\n\n";
            return;
        }

        $this->bulkOrderStatus->completed = false;
        $this->bulkOrderStatus->total_orders = count($this->orders);
        $this->bulkOrderStatus->completed_orders = 0;

        try{
            echo "APIKEY: ".$this->stripeApiKey."\n";
            Stripe::setApiKey($this->stripeApiKey);
            // set up your tweaked Curl client
            $curl = new \Stripe\HttpClient\CurlClient(array(CURLOPT_PROXY => ''));
            // tell Stripe to use the tweaked client
            \Stripe\ApiRequestor::setHttpClient($curl);
        }catch(Exception $e){
            echo $e->getMessage();
        }

        // Get/create stripe customer
        try{
            echo "Trying to get stripe customer\n";

            if($this->user->stripe_customer_id !== null){
                $customerId = $this->user->stripe_customer_id;
            }else{
                $customer = Customer::create(array(
                    'email' => $this->user->email,
                    'source' => $this->stripeToken,
                ));

                $customerId = $customer->id;
                $this->user->stripe_customer_id = $customerId;
                $this->user->save();
            }

            echo $customerId."\n";

        }catch(Exception $e){
            echo $e->getMessage();
            echo $e->getTrace();
            // TODO: We need to do something here to let the user know we could not process the payment.
            die();
        }

        try{
            echo "Trying to create a charge.\n";
            $charge = Charge::create(array(
                'amount' => $this->totalOrderPrice * 100,
                'currency' => 'usd',
                'description' => 'ContentLaunch Order',
                "customer" => $customerId
            ));


            if($charge->status !== "succeeded"){
                throw new Exception("Card not authorized");
            }

            echo $charge->id."\n";

        }catch(Exception $e){
            echo $e->getMessage();
            echo $e->getTrace();
            // TODO: We need to do something here to let the user know that the card was not authorized. Maybe move this back to the controller.
            die();
        }

        foreach ($this->orders as $key=>$order){
            if(!$this->placeOrder($order)){
                $this->failedOrders[] = $order;
            }
            
            $statusPercentage = round(($key+1)/count($this->orders)*100);
            $this->bulkOrderStatus->status_percentage = $statusPercentage;
            $this->bulkOrderStatus->completed_orders = $key+1;

            $this->bulkOrderStatus->save();
        }

        $this->bulkOrderStatus->completed = true;
        $this->bulkOrderStatus->save();

        $job = (new DeleteWriterAccessBulkOrder($this->bulkOrderStatus->id))->delay(10);
        $this->dispatch($job);
    }

    /**
     * Places a single order through WriterAccess for the order details provided.
     * 
     * @param Order $order
     * @return bool
     */
    public function placeOrder($order){
        echo "\nPlacing order for: '".$order->getTitle()."'.\n";

        $writerAccessController = new WriterAccessController(new Request(), $this->user, $this->apiProject, $this->apiProjectId);

        $response = $writerAccessController->queuedOrderSubmit($order);

        echo "\nThe response";
        var_dump($response);

        if(isset($response->fault)){
            echo "\nError: " . $response->fault . "\n";
            return false;
        } else if(isset($response->error)){
            echo "\nError: " . $response->error . "\n";
            return false;
        } else{
            echo "\nComplete!\n";
            return true;
        }
    }
}
