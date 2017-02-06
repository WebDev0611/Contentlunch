<?php

namespace App\Jobs;

use App\Http\Controllers\WriterAccessBulkOrderStatusController;
use App\Http\Controllers\WriterAccessController;
use Illuminate\Http\Request;
use App\User;
use App\WriterAccessBulkOrderStatus;
use App\DTO\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Collections\RowCollection;
use Illuminate\Foundation\Bus\DispatchesJobs;

class WriterAccessBulkOrder extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    private $bulkOrderStatus;
    private $user;
    private $orders;
    private $failedOrders = [];
    private $originalRequest;

    /**
     * WriterAccessBulkOrder constructor.
     * @param Integer $bulkOrderStatusId
     * @param User $user
     * @param Order[] $orders
     * @param Request $originalRequest
     */
    public function __construct($bulkOrderStatusId, User $user, array $orders, Request $originalRequest){
        $this->bulkOrderStatus = WriterAccessBulkOrderStatus::find($bulkOrderStatusId);
        $this->user = $user;
        $this->orders = $orders;
        $this->originalRequest = $originalRequest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
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
    }

    /**
     * Places a single order through WriterAccess for the order details provided.
     * 
     * @param Order $order
     * @return bool
     */
    public function placeOrder($order){
        echo "\nPlacing order for '".$order->getTitle()."'.\n";

        $writerAccessController = new WriterAccessController($this->originalRequest);

        echo $writerAccessController->createOrder($order->toArray());

    }
}
