<?php
/**
 * Created by IntelliJ IDEA.
 * User: zkghsyv
 * Date: 1/22/17
 * Time: 10:37 AM
 */

namespace App\Http\Controllers;

use Excel;
use Exception;
use App\WriterAccessBulkOrderStatus;
use App\Jobs\WriterAccessBulkOrder;

class WriterAccessBulkOrderStatusController extends Controller {

    public function index(){

        $bulkOrderStatuses = WriterAccessBulkOrderStatus::get();

        return response()->json($bulkOrderStatuses);

    }

    public function status($id = null){
        $bulkOrderStatus = WriterAccessBulkOrderStatus::where("id", $id)->get();
        return isset($bulkOrderStatus[0]) ? response()->json($bulkOrderStatus[0]) : array("error"=>"No status found for id '".$id."'.");
    }

    public function sample(){
        try{
            $user = Auth()->user();;

            $workbook = Excel::load("/Users/zkghsyv/Desktop/bulk_sample.xlsx")->get();

            $bulkOrderStatus =  WriterAccessBulkOrderStatus::create([
                "total_orders " => count($workbook),
                "status_percentage " => 0,
                "completed_orders " => 0,
                "completed " => false,
            ]);

            $job = (new WriterAccessBulkOrder($bulkOrderStatus->id, $user, $workbook));

            $this->dispatch($job);

            return response()->json(array(
                "user" => $user,
                "bulkOrderStatus" => $bulkOrderStatus,
                "workbook" => $workbook,
                "job" => $job
            ));
        }catch(Exception $e){
            return response()->json(array(
                "error" => $e->getMessage(),
                "stack" => json_encode($e->getTrace())
            ));
        }
    }
}