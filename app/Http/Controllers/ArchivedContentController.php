<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Content;
use Illuminate\Http\Request;

use App\Http\Requests;

class ArchivedContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $archived = Content::archived()->latest()->get();
        $archivedCampaigns = Campaign::archived()->latest()->get();

        return view('content.archived.index', compact('archived', 'archivedCampaigns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Archives a specific piece of content.
     *
     * @param Content $content
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function update(Content $content)
    {
        $content->setArchived();

        return redirect()->route('archived_contents.index')->with([
            'flash_message' => 'Content archived',
            'flash_message_type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
