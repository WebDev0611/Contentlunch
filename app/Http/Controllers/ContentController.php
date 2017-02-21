<?php

namespace App\Http\Controllers;

use App\Account;
use App\Attachment;
use App\BuyingStage;
use App\Campaign;
use App\Connection;
use App\Content;
use App\ContentType;
use App\Helpers;
use App\Http\Requests\Content\ContentRequest;
use App\Persona;
use App\Presenters\ContentTypePresenter;
use App\Presenters\CampaignPresenter;
use App\Tag;
use App\User;
use App\WriterAccessPrice;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Input;
use Response;
use Storage;
use Validator;

class ContentController extends Controller
{
    public function __construct(Request $request)
    {
        $this->selectedAccount = Account::selectedAccount();
    }

    public function index()
    {
        $countContent = $this->selectedAccount
            ->contents()
            ->count();

        $this->selectedAccount->cleanContentWithoutStatus();

        $published = $this->selectedAccount
            ->contents()
            ->where('published', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        $readyPublished = $this->selectedAccount
            ->contents()
            ->where('ready_published', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        $written = $this->selectedAccount
            ->contents()
            ->where('written', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        $connections = $this->selectedAccount
            ->connections()
            ->where('active', 1)
            ->get();

        return view('content.index', compact(
            'published', 'readyPublished', 'written', 'countContent', 'connections'
        ));
    }

    public function orders(Request $request)
    {

        $writerAccess = new WriterAccessController($request);
        $approved = [];
        $inProgress = [];
        $open = [];
        $pendingApproval = [];

        try{
            $orders = json_decode(utf8_encode($writerAccess->orders()->getContent()))->orders;

        }catch(Exception $e){
            $orders = [];
            /*switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    echo ' - No errors';
                    break;
                case JSON_ERROR_DEPTH:
                    echo ' - Maximum stack depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    echo ' - Underflow or the modes mismatch';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    echo ' - Unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    echo ' - Syntax error, malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    echo ' - Unknown error';
                    break;
            }*/
        }

        foreach ($orders as $order){
            switch ($order->status){
                case "Approved" :
                    $approved[] = $order;
                case "In Progress" :
                    $inProgress[] = $order;
                case "Open" :
                    $open[] = $order;
                case "Pending Approval" :
                    $pendingApproval[] = $order;
            }
        }


        $countOrders = count($orders);

        $connections = $this->selectedAccount
            ->connections()
            ->where('active', 1)
            ->get();


        return view('content.orders', compact(
            'orders', 'countOrders', 'approved', 'inProgress', 'open', 'pendingApproval', 'connections'
        ));

    }

    public function order(Request $request, $id)
    {

        $writerAccess = new WriterAccessController($request);
        $error = null;

        try{
            $data1 = json_decode(utf8_encode($writerAccess->orders($id)->getContent()));
            $data2 = json_decode(utf8_encode($writerAccess->comments($id)->getContent()));
            /*$data2 = json_decode('{
            "orders": [ {
                "id": 7865, "comments": [
                    {
                        "timestamp": "2011-04-06T08:20:01",
                        "writer": {
                            "id": 1310,
                            "name": "Tim G",
                            "note": "A note from a Writer"
                        }
                    },
                    {
                        "timestamp": "2011-04-06T08:20:01",
                        "editor": {
                            "id": 2653,
                            "name": "Caitlin W",
                            "note": "A note from an Editor"
                        }
                    },
                    {
                        "timestamp": "2011-04-09T11:15:09",
                        "client": {
                            "note": "A note from the client"
                        }
                    }
                ]
            }
        ]}');*/

            if(isset($data1->fault) || isset($data2->fault)){
                return redirect()->route('contentOrders')->with([
                    'flash_message' => isset($data1->fault) ? $data1->fault : $data2->fault,
                    'flash_message_type' => 'danger',
                    'flash_message_important' => true,
                ]);
            }


          /*  var_dump($data1);
            die();*/

            $order = $data1->order;
            $writer = $data1->writer;
            $preview = $data1->preview;
            $comments = $data2->orders[0]->comments;

        }catch(Exception $e){
            $order = null;
            $writer = null;
            $preview = null;
            return redirect()->route('contentOrders')->with([
                'flash_message' => $e->getMessage(),
                'flash_message_type' => 'danger',
                'flash_message_important' => true,
            ]);
        }

        $connections = $this->selectedAccount
            ->connections()
            ->where('active', 1)
            ->get();

        return view('content.order', compact(
            'order', 'connections', 'writer', 'preview', 'comments'
        ));

    }

    public function store(Request $request)
    {
        $content = Content::create([
            'title' => $request->input('title'),
            'body' => $request->has('body') ? $request->input('body') : "",
            'content_type_id' => $request->input('content_type'),
        ]);

        $this->selectedAccount->contents()->save($content);

        if($request->ajax()) {
            $response =  response()->json(['status' => 'ok'], 200);
            $content->due_date = $request->input('due_date');
            $content->body = $request->input('body');
            $content->created_at = $request->input('created_at');
            $content->setWritten();
            $this->ensureCollaboratorsExists($content);
            if($content->save()) {
                return $content;
            }

            return response()->json(['status' => 'error saving'], 500);
        }

        return redirect('edit/'.$content->id);
    }

    public function create()
    {
        $contentTypes = DB::table('writer_access_asset_types')->get();

        $prices = DB::table('writer_access_prices')->distinct()->select('asset_type_id')->get();

        $reformedPrices = [];

        foreach ($prices as $price) {
            if (!isset($reformedPrices[$price->asset_type_id])) {
                $reformedPrices[$price->asset_type_id] = [];

                $wordcounts = WriterAccessPrice::availableWordcountsByAssetType($price->asset_type_id);

                foreach ($wordcounts as $wordcount) {
                    $reformedPrices[$price->asset_type_id][$wordcount->wordcount] = [];
                    $writerLevels = DB::table('writer_access_prices')
                        ->where('asset_type_id', $price->asset_type_id)
                        ->where('wordcount', $wordcount->wordcount)
                        ->get();

                    foreach ($writerLevels as $writerLevel) {
                        $reformedPrices[$price->asset_type_id][$wordcount->wordcount][$writerLevel->writer_level] = $writerLevel->fee;
                    }
                }
            }
        }

        $pricesJson = json_encode($reformedPrices);

        $contenttypedd = ContentTypePresenter::dropdown();
        $campaigndd = CampaignPresenter::dropdown();

        $data = compact('contentTypes', 'pricesJson', 'contenttypedd', 'campaigndd');

        return view('content.create', $data);
    }

    public function trendShare(Request $request, Connection $connection)
    {
        $content = (object) Input::all();
        $errors = [];
        $publishedConnections = [];

        $response = $this->publishTrend($content, $connection);

        if (!$response['success']) {
            $connectionName = (string) $connection;
            $errors [] = [$connectionName => $response['error']];
        } else {
            $publishedConnections[] = $connection->provider->slug;
        }

        $response = response()->json([
            'data' => 'Content published',
            'errors' => $errors,
            'content' => $content->body,
            'published_connections' => $publishedConnections,
        ], 201);

        return $response;
    }

    public function directPublish(Request $request, Content $content)
    {
        $connections = collect(explode(',', $request->input('connections')))
            ->map(function ($connectionId) {
                return Connection::find($connectionId);
            });

        $response = response()->json(['data' => 'Content not found'], 404);

        if ($content) {
            $errors = [];
            $connectionsCount = 0;
            $failedConnections = 0;
            $publishedConnections = [];

            foreach ($connections as $connection) {
                $response = $this->publish($content, $connection);

                if (!$response['success']) {
                    $connectionName = (string) $connection;
                    $errors [] = [$connectionName => $response['error']];
                    ++$failedConnections;
                } else {
                    $publishedConnections[] = $connection->provider->slug;
                }

                ++$connectionsCount;
            }

            $response = response()->json([
                'data' => 'Content published',
                'errors' => $errors,
                'content' => $content,
                'published_connections' => $publishedConnections,
            ], 201);
        }

        return $response;
    }

    public function publish(Content $content, $connection = null)
    {
        // - this will need to be dynamic ( database provider table? )
        // -- Once we hook up another API i will know how i should organize this
        if (!$connection) {
            $connection = $content->connection;
        }

        $class = 'Connections\API\\'.$connection->provider->class_name;
        $create = (new $class($content, $connection))->createPost();

        $content->published = 1;
        $content->ready_published = 0;
        $content->written = 0;
        $content->save();

        return $create;
    }

    public function publishTrend($content, Connection $connection = null)
    {
        $class = 'Connections\API\\'.$connection->provider->class_name;
        $create = (new $class($content, $connection))->createPost();

        return $create;
    }

    public function publishAndRedirect(Request $request, $contentId)
    {
        $content = Content::find($contentId);

        $this->publish($content);

        return redirect()->route('contentIndex')->with([
            'flash_message' => 'You have published '.$content->title.' to '.$content->connection->provider->slug,
            'flash_message_type' => 'success',
            'flash_message_important' => true,
        ]);
    }

    // this is technically create content
    public function createContent()
    {
        $data = [
            'tagsJson' => $this->selectedAccount->present()->tagsJson,
            'contentTagsJson' => collect([])->toJson(),
            'authorDropdown' => $this->selectedAccount->authorsDropdown(),
            'relatedContentDropdown' => $this->selectedAccount->relatedContentsDropdown(),
            'buyingStageDropdown' => BuyingStage::dropdown(),
            'personaDropdown' => Persona::dropdown(),
            'campaignDropdown' => CampaignPresenter::dropdown(),
            'connections' => Connection::dropdown(),
            'contentTypeDropdown' => ContentTypePresenter::dropdown(),
        ];

        return view('content.editor', $data);
    }

    // - edit content on page
    public function editContent(Content $content)
    {
        $this->ensureCollaboratorsExists($content);

        $data = [
            'content' => $content,
            'tagsJson' => $this->selectedAccount->present()->tagsJson,
            'contentTagsJson' => $content->present()->tagsJson,
            'authorDropdown' => $this->selectedAccount->authorsDropdown(),
            'relatedContentDropdown' => $this->selectedAccount->relatedContentsDropdown(),
            'buyingStageDropdown' => BuyingStage::dropdown(),
            'personaDropdown' => Persona::dropdown(),
            'campaignDropdown' => CampaignPresenter::dropdown(),
            'connections' => Connection::dropdown(),
            'contentTypeDropdown' => ContentTypePresenter::dropdown(),
            'files' => $content->attachments()->where('type', 'file')->get(),
            'images' => $content->attachments()->where('type', 'image')->get(),
            'isCollaborator' => $content->hasCollaborator(Auth::user()),
            'tasks' => $content->tasks()->with('user')->get(),
        ];

        return view('content.editor', $data);
    }

    public function getContentTypes() {
        return ContentType::with('provider')->get();
    }

    protected function ensureCollaboratorsExists(Content $content)
    {
        if ($content->collaborators->isEmpty()) {
            $content->collaborators()->attach(Auth::user());
        }
    }

    public function editStore(Request $request, Content $content)
    {
        if ($request->input('action') == 'written_content') {
            $validation = $this->onSaveValidation($request->all());
        } else {
            $validation = $this->onSubmitValidation($request->all());
        }

        if ($validation->fails()) {
            return redirect("/edit/$content->id")->with('errors', $validation->errors());
        }

        if (!$content->hasCollaborator(Auth::user())) {
            return redirect()->route('contentIndex')->with([
                'flash_message' => 'You don\'t have permission to edit this content.',
                'flash_message_type' => 'danger',
                'flash_message_important' => true,
            ]);
        }

        $content = $this->detachRelatedContent($content);
        $content = $this->saveContentAndAttachToAccount($request, $content);

        $this->saveContentCampaign($request, $content);
        $this->saveContentBuyingStage($request, $content);
        $this->saveContentPersona($request, $content);
        $this->saveContentType($request, $content);
        $this->saveConnections($request, $content);
        $this->saveContentTags($request, $content);

        // - Attach the related data
        if ($request->input('related')) {
            $content->related()->attach($request->input('related'));
        }

        $this->handleImages($request, $content);
        $this->handleFiles($request, $content);

        if ($content->published) {
            return $this->publishAndRedirect($request, $content->id);
        } else {
            return redirect()->route('contentIndex')->with([
                'flash_message' => 'You have created content titled '.$content->title.'.',
                'flash_message_type' => 'success',
                'flash_message_important' => true,
            ]);
        }
    }

    private function onSubmitValidation(array $requestData)
    {
        return Validator::make($requestData, [
            'content_type' => 'required',
            'due_date' => 'required',
            'title' => 'required',
            'connections' => 'required',
            'content' => 'required',
        ]);
    }

    private function onSaveValidation(array $requestData)
    {
        return Validator::make($requestData, [
            'title' => 'required',
        ]);
    }

    private function detachRelatedContent($content)
    {
        $content->related()->detach();
        $content->tags()->detach();

        return $content;
    }

    private function saveContentAndAttachToAccount($request, $content)
    {
        $content->configureAction($request->input('action'));

        $content->title = $request->input('title');
        $content->body = $request->input('content');
        $content->due_date = $request->input('due_date');
        $content->meta_title = $request->input('meta_title');
        $content->meta_keywords = $request->input('meta_keywords');
        $content->meta_description = $request->input('meta_descriptor');
        $content->email_subject = $request->input('email_subject');
        $content->save();

        $this->selectedAccount->contents()->save($content);

        return $content;
    }

    private function saveConnections($request, $content)
    {
        if ($request->has('connections')) {
            $connection = Connection::find($request->input('connections'));
            $connection->contents()->save($content);
        }
    }

    private function saveContentCampaign($request, $content)
    {
        if ($request->input('campaign')) {
            $campaign = Campaign::find($request->input('campaign'));
            $campaign->contents()->save($content);
        }
    }

    private function saveContentBuyingStage($request, $content)
    {
        if ($request->input('buying_stage')) {
            $buyingStage = BuyingStage::find($request->input('buying_stage'));
            $buyingStage->contents()->save($content);
        }
    }

    private function saveContentPersona($request, $content)
    {
        if ($request->input('persona')) {
            $persona = Persona::find($request->input('persona'));
            $persona->contents()->save($content);
        }
    }

    private function saveContentType($request, $content)
    {
        if ($request->input('content_type')) {
            $conType = ContentType::find($request->input('content_type'));
            $conType->contents()->save($content);
        }
    }

    private function saveContentTags($request, $content)
    {
        if ($request->input('tags')) {
            $content->tags()->detach();

            $tagsArray = explode(',', $request->input('tags'));
            collect($tagsArray)
                ->filter(function($tagString) { return $tagString !== ""; })
                ->map(function($tagString) {
                    return $this->selectedAccount
                        ->tags()
                        ->firstOrCreate([ 'tag' => $tagString ]);
                })
                ->each(function($tag) use ($content) {
                    $content->tags()->attach($tag);
                });
        }
    }

    public function delete(Request $request, $content_id)
    {
        $content = Content::find($content_id);
        $redirect = $this->redirectDeleteFailed();

        if ($content) {
            $this->detachRelatedContent($content);
            $content->attachments()->delete();
            $content->delete();
            $redirect = $this->redirectDeleteSuccessful($content);
        }

        return $redirect;
    }

    protected function redirectDeleteSuccessful($content)
    {
        return redirect()->route('contentIndex')->with([
           'flash_message' => 'You have successfully deleted '.$content->title.'.',
           'flash_message_type' => 'success',
           'flash_message_important' => true,
       ]);
    }

    protected function redirectDeleteFailed()
    {
        return redirect()->route('contentIndex')->with([
            'flash_message' => 'Unable to delete content: not found.',
            'flash_message_type' => 'danger',
            'flash_message_important' => true,
        ]);
    }

    public function my()
    {
        $content = $this->selectedAccount
            ->contents()
            ->with('authors')
            ->get();

        return response()->json($content);
    }

    /**
     * Shortcut functions to remove possibility of error.
     * Handling of images.
     *
     * @param ContentRequest $request The Request instance
     * @param Content        $content Content instance
     */
    private function handleImages($request, $content)
    {
        $this->handleAttachments($request->input('images'), $content, 'image');
    }

    /**
     * Shortcut functions to remove possibility of error.
     * Handling of files.
     *
     * @param ContentRequest $request The Request instance
     * @param Content        $content Content instance
     */
    private function handleFiles($request, $content)
    {
        $this->handleAttachments($request->input('files'), $content, 'file');
    }

    private function handleAttachments($files, $content, $fileType = 'file')
    {
        $files = collect($files)->filter()->flatten()->toArray();
        $userFolder = $fileType == 'image' ?
            Helpers::userImagesFolder() :
            Helpers::userFilesFolder();

        foreach ($files as $fileUrl) {
            $newPath = $this->moveFileToUserFolder($fileUrl, $userFolder);
            $attachment = $this->createAttachment($newPath, $fileType);
            $content->attachments()->save($attachment);
        }
    }

    private function moveFileToUserFolder($fileUrl, $userFolder)
    {
        $fileName = substr(strstr($fileUrl, '_tmp/'), 5);
        $newPath = $userFolder.$fileName;
        $s3Path = Helpers::s3Path($fileUrl);
        Storage::move($s3Path, $newPath);

        return $newPath;
    }

    private function createAttachment($s3Path, $fileType)
    {
        return Attachment::create([
            'filepath' => $s3Path,
            'filename' => Storage::url($s3Path),
            'type' => $fileType,
            'extension' => Helpers::extensionFromS3Path($s3Path),
            'mime' => Storage::mimeType($s3Path),
        ]);
    }

    /**
     * Asynchronous attachments and images uploads.
     */
    public function images(Request $request)
    {
        $validation = $this->imageValidator($request->all());

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        return $this->handleAsyncUploads($request->file('file'));
    }

    public function attachments(Request $request)
    {
        $validation = $this->attachmentValidator($request->all());

        if ($validation->fails()) {
            return response()->json($validation->errors(), 400);
        }

        return $this->handleAsyncUploads($request->file('file'));
    }

    private function handleAsyncUploads($file)
    {
        $url = Helpers::handleTmpUpload($file, true);

        return response()->json(['file' => $url]);
    }

    private function attachmentValidator($input)
    {
        return Validator::make($input, [
            'file' => 'file|max:20000',
        ]);
    }

    private function imageValidator($input)
    {
        return Validator::make($input, [
            'file' => 'image|max:3000',
        ]);
    }

}
