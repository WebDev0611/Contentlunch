<?php

namespace App\Jobs;

use App\User;
use App\WriterAccessBulkOrderStatus;
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

    /**
     * WriterAccessBulkOrder constructor.
     * @param Integer $bulkOrderStatusId
     * @param User $user
     * @param RowCollection $orders
     */
    public function __construct($bulkOrderStatusId, User $user, RowCollection $orders){
        $this->bulkOrderStatus = WriterAccessBulkOrderStatus::find($bulkOrderStatusId);
        $this->user = $user;
        $this->orders = $orders;

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
     * @param $order
     * @return bool
     */
    public function placeOrder($order){
        echo "Placing order for '".$order->content_title."'.\n\n:";
        sleep(1);
        return true;
    }
}
