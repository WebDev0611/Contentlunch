<?php
/**
 * Created by IntelliJ IDEA.
 * User: zkghsyv
 * Date: 1/22/17
 * Time: 10:37 AM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use Exception;
use App\WriterAccessBulkOrderStatus;
use App\Jobs\WriterAccessBulkOrder;
use Illuminate\Support\Facades\Auth;

class WriterAccessBulkOrderStatusController extends Controller {

    public function index(){
        $bulkOrderStatuses = WriterAccessBulkOrderStatus::get();
        return response()->json($bulkOrderStatuses);
    }

    public function status($id = null){
        $bulkOrderStatus = WriterAccessBulkOrderStatus::where("id", $id)->get();
        return isset($bulkOrderStatus[0]) ? response()->json($bulkOrderStatus[0]) : array("error"=>"No status found for id '".$id."'.");
    }

    public function show(Request $request, $id){
        $bulkOrderStatus = WriterAccessBulkOrderStatus::findOrFail($id);

        if (!$bulkOrderStatus) {
            abort(404);
        }

        return view('writerAccessBulkOrderStatuses.show', compact('bulkOrderStatus'));
    }


}