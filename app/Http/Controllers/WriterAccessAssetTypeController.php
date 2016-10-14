<?php

namespace App\Http\Controllers;
use App\WriterAccessAssetType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WriterAccessAssetTypeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        //
		$this->contentView = "writerAccessAssetTypes.index";

		$writerAccessAssetTypes = array();
		foreach(WriterAccessAssetType::all() as $writerAccessAssetType){
			$prices = DB::table("writer_access_prices")->where("asset_type_id", "=", $writerAccessAssetType->writer_access_id)->get();
			$wordcounts = DB::table("writer_access_prices")->select("wordcount")->where("asset_type_id", "=", $writerAccessAssetType->writer_access_id)->distinct()->get();
			$writerAccessAssetType['prices'] = $prices;
			$writerAccessAssetType['wordcounts'] = $wordcounts;

			$writerAccessAssetTypes[] = $writerAccessAssetType;
		}

		return $this->formatResponse(array("writerAccessAssetTypes" => $writerAccessAssetTypes));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        //
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        //
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        //
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        //
		$this->contentView = "writerAccessAssetTypes.edit";

		$writerAccessAssetType = WriterAccessAssetType::find($id);
		$writerAccessAssetType['prices'] = DB::table("writer_access_prices")->where("asset_type_id", "=", $writerAccessAssetType->writer_access_id)->get();

		return $this->formatResponse(array("writerAccessAssetType" => $writerAccessAssetType));
	}


    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        // Restrict to global admins
        if (!$this->isGlobalAdmin()) {
            return redirect("writerAccessAssetTypes/".$id."/edit")->with([
                'flash_message' => 'You do not have sufficient privileges to make changes to this page.',
                'flash_message_type' => 'danger',
                'flash_message_important' => true
            ]);
        }

        $input = $request->input("writer_access_asset_type");

        $writerAccessAssetType = WriterAccessAssetType::findOrFail($id);

        $writerAccessAssetType->writer_access_id = $input['writer_access_id'];
        $writerAccessAssetType->name = $input['name'];



        if ($writerAccessAssetType->save()) {
            return redirect("writerAccessAssetTypes")->with([
                'flash_message' => 'Asset Type updated successfully.',
                'flash_message_type' => 'success',
                'flash_message_important' => true
            ]);
        }else{
            return $this->responseError($writerAccessAssetType->errors()->toArray());
        }
    }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        //
	}


}
