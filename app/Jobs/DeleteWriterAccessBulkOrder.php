<?php

namespace App\Jobs;

use App\WriterAccessBulkOrderStatus;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteWriterAccessBulkOrder extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $bulkOrderStatus;

    /**
     * Create a new job instance.
     * @param $bulkOrderStatusId
     * @return void
     */
    public function __construct($bulkOrderStatusId)
    {
        $this->bulkOrderStatus = WriterAccessBulkOrderStatus::find($bulkOrderStatusId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo "\nDeleting WriterAccessBulkOrderStatus with id of '".$this->bulkOrderStatus->id."'... ";
        if ($this->bulkOrderStatus->delete()){
            echo "Done!\n\n";
        }else{
            echo "Failed!\nn";
        }
    }
}
