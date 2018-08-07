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
use Illuminate\Support\Facades\Mail;
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

    /**
     * WriterAccessBulkOrder constructor.
     * @param Integer $bulkOrderStatusId
     * @param User    $user
     * @param Order[] $orders
     * @param null    $apiProject
     * @param null    $apiProjectId
     * @internal param Request $originalRequest
     */
    public function __construct($bulkOrderStatusId, User $user, array $orders, $apiProject = null, $apiProjectId = null){
        $this->bulkOrderStatus = WriterAccessBulkOrderStatus::find($bulkOrderStatusId);
        $this->user = $user;
        $this->orders = $orders;
        $this->apiProject = $apiProject;
        $this->apiProjectId = $apiProjectId;
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

        foreach ($this->failedOrders as $failedOrder) {
            Mail::send('emails.order_failed', ['order' => $failedOrder], function($message) {
                $message->from("no-reply@contentlaunch.com", "Content Launch")
                    ->to('jon@contentlaunch.com')
                    ->subject('Order failed!');
            });
        }
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
