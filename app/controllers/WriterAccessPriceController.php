<?php

/**
 * Class WriterAccessPriceController
 */
class WriterAccessPriceController extends BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $this->contentView = "writerAccessPrices.index";
    return $this->formatResponse(array(
        "writerAccessPrices" => WriterAccessPrice::
            orderBy("asset_type_id", "ASC")
            ->orderBy("writer_level", "DESC")
            ->orderBy("wordcount", "ASC")
            ->join('writer_access_asset_types', 'writer_access_prices.asset_type_id', '=', 'writer_access_asset_types.writer_access_id')
            ->select('writer_access_prices.*', 'writer_access_asset_types.name')
            ->get()));
  }


  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    $this->contentView = "writerAccessPrices.create";
    return $this->formatResponse(null);
  }


  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $writerAccessPrice = new WriterAccessPrice();

    $writerAccessPrice->asset_type_id = Input::get('asset_type_id');
    $writerAccessPrice->writer_level = Input::get('writer_level');
    $writerAccessPrice->wordcount = Input::get('wordcount');
    $writerAccessPrice->fee = Input::get('fee');

    if ($writerAccessPrice->save()) {
      $this->contentView = "writerAccessPrices.index";
      return $this->formatResponse(array("writerAccessPrices" => $this->show($writerAccessPrice->id)));
    }

    $this->contentView = "writerAccessPrices.create";
    return $this->responseError($writerAccessPrice->errors()->toArray());

  }


  /**
   * Display the specified resource.
   *
   * @param  int $id
   * @return Response
   */
  public function show($id)
  {
    if ($WriterAccessPrice = WriterAccessPrice::find($id)->orderBy('asset_type_id', 'asc')->orderBy('writer_level', 'desc')->orderBy('wordcount', 'asc')) {
      return $WriterAccessPrice;
    }
    return $this->responseError("Record not found");
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int $id
   * @return Response
   */
  public function edit($id)
  {
    $writerAccessPrice = WriterAccessPrice::find($id);
    if (!$writerAccessPrice) {
      return $this->responseError("Record not found");
    }

    $this->contentView = "writerAccessPrices.edit";
    return $this->formatResponse(array("writerAccessPrice" => $writerAccessPrice));
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  int $id
   * @return Response
   */
  public function update($id)
  {
    // Restrict to global admins
    if (!$this->isGlobalAdmin()) {
      return $this->responseAccessDenied();
    }
    $writerAccessPrice = WriterAccessPrice::find($id);
    if (!$writerAccessPrice) {
      return $this->responseError("Record not found");
    }

    $writerAccessPrice->asset_type_id = Input::get('asset_type_id');
    $writerAccessPrice->writer_level = Input::get('writer_level');
    $writerAccessPrice->wordcount = Input::get('wordcount');
    $writerAccessPrice->fee = Input::get('fee');

    if ($writerAccessPrice->save()) {
      return $this->show($writerAccessPrice->id);
    }

    return $this->responseError($writerAccessPrice->errors()->toArray());
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int $id
   * @return Response
   */
  public function destroy($id)
  {
    $WriterAccessPrice = WriterAccessPrice::find($id);
    if (!$WriterAccessPrice) {
      return $this->responseAccessDenied();
    }

    if ($WriterAccessPrice->delete()) {
      return ['success' => 'OK'];
    }

    return $this->responseError("Error deleting WriterAccessPrice");
  }


  public function fee(){

    if(!isset($_GET['asset_type_id']) || !isset($_GET['writer_level']) || !isset($_GET['wordcount'])){
      return $this->responseError("Missing required GET parameters. (asset_type_id, writer_level, and wordcount are required.)");
    }

    $asset_type_id = $_GET['asset_type_id'];
    $writer_level = $_GET['writer_level'];
    $wordcount = $_GET['wordcount'];

    if ($WriterAccessPrice = WriterAccessPrice::where("asset_type_id", $asset_type_id)
        ->where("writer_level", $writer_level)
        ->where("wordcount", $wordcount)
        ->first()) {
      return $WriterAccessPrice;
    }
    return $this->responseError("Record not found");
  }

}
