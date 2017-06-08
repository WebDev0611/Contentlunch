<?php

namespace App\Http\Controllers;

use App\Account;
use App\Attachment;
use App\BuyingStage;
use App\Calendar;
use App\Campaign;
use App\Connection;
use App\Content;
use App\ContentType;
use App\CustomContentType;
use App\Helpers;
use App\Http\Requests\Content\ContentRequest;
use App\Limit;
use App\Persona;
use App\Presenters\CampaignPresenter;
use App\Presenters\ContentTypePresenter;
use App\Services\ContentService;
use App\Tag;
use App\User;
use App\WriterAccessComment;
use App\WriterAccessPrice;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Input;
use Response;
use Storage;
use Validator;

class ContentController extends Controller
{
    protected $selectedAccount;
    protected $content;

    public function __construct(Request $request, ContentService $content)
    {
        $this->selectedAccount = Account::selectedAccount();
        $this->content = $content;
    }

    public function index(Request $request)
    {
        $filters = collect([
            'author' => $request->input('author'),
            'campaign' => $request->input('campaign'),
            'stage' => $request->input('stage'),
        ])->filter()->toArray();

        return $request->ajax()
            ? response()->json([ 'data' => $this->content->recentContent() ])
            : view('content.index', $this->content->contentList($filters));
    }

    public function orders(Request $request)
    {
        if($request->input("bulksuccess") === "true"){
            return redirect()->route('content_orders.index', "fresh")->with([
                'flash_message' => "Your content orders have been placed successfully." ,
                'flash_message_type' => 'success',
            ]);
        }

        $writerAccess = new WriterAccessController($request);
        $approved = [];
        $inProgress = [];
        $open = [];
        $pendingApproval = [];

        try {
            $orders = json_decode(utf8_encode($writerAccess->orders()->getContent()))->orders;
        } catch(Exception $e) {
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

        foreach ($orders as $order) {
            switch ($order->status) {
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

            if(isset($data1->fault) || isset($data2->fault)){
                return redirect()->route('content_orders.index')->with([
                    'flash_message' => isset($data1->fault) ? $data1->fault : $data2->fault,
                    'flash_message_type' => 'danger',
                    'flash_message_important' => true,
                ]);
            }

           //dd($data1);

            $order = $data1->order;
            $writer = $data1->writer;
            $preview = $data1->preview;
            $comments = $data2->orders[0]->comments;

        }catch(Exception $e){
            $order = null;
            $writer = null;
            $preview = null;
            return redirect()->route('content_orders.index')->with([
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
        $validator = $this->onSaveValidation($request->all());

        if ($validator->fails()) {
            $request->flash();
            return $request->ajax() ? response()->json($validator->messages(), 400) :
                redirect()->back()->withErrors($validator, 'content');
        }

        $customContentType = $this->saveCustomContentType($request);

        $content = Content::create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'user_id' => Auth::id(),
            'content_type_id' => $request->input('content_type_id'),
            'custom_content_type_id' => isset($customContentType) ? $customContentType->id : null,
        ]);

        $this->selectedAccount->contents()->save($content);
        $this->saveAsCalendarContent($request, $content);

        if ($request->ajax()) {
            $content->due_date = $request->input('due_date');
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
                    $writerLevels = $this->getWriterLevels($price->asset_type_id, $wordcount->wordcount);

                    foreach ($writerLevels as $writerLevel) {
                        $reformedPrices[$price->asset_type_id][$wordcount->wordcount][$writerLevel->writer_level] = $writerLevel->fee;
                    }
                }
            }
        }

        $pricesJson = json_encode($reformedPrices);

        $contenttypedd = ContentTypePresenter::dropdown();
        $campaigndd = CampaignPresenter::dropdown();

        $promotion = Auth::user()->contentOrdersPromotion();
        $userIsOnPaidAccount = !Account::selectedAccount()->activePaidSubscriptions()->isEmpty();
        $isAgencyAccount = Account::selectedAccount()->isAgencyAccount();

        $data = compact('contentTypes', 'pricesJson', 'contenttypedd', 'campaigndd', 'promotion', 'userIsOnPaidAccount', 'isAgencyAccount');

        return view('content.create', $data);
    }

    public function orderComments (Request $request, $id)
    {
        if(!collect($this->getOrders($request))->contains('id', $id)) {
            return redirect()->route('content_orders.index')->with([
                'flash_message' => 'You don\'t have sufficient permissions to do this.',
                'flash_message_type' => 'danger',
            ]);
        }

        return view('content.order_comments', ['orderId' => $id]);
    }

    protected function getWriterLevels($assetTypeId, $wordcount)
    {
        return DB::table('writer_access_prices')
            ->where('asset_type_id', $assetTypeId)
            ->where('wordcount', $wordcount)
            ->get();
    }

    public function getOrders (Request $request)
    {
        $writerAccess = new WriterAccessController($request);

        return json_decode(utf8_encode($writerAccess->orders()->getContent()))->orders;
    }

    public function getOrdersCount(Request $request) {
        $writerAccess = new WriterAccessController($request);

        try {
            $orders = json_decode(utf8_encode($writerAccess->orders()->getContent()))->orders;
        } catch(Exception $e) {
            return response()->json('Error occurred while trying to fetch the data.', 500);
        }

        $count = count($orders);

        if($request->has('pending-approval')) {
            $pendingApproval = [];

            foreach ($orders as $order) {
                if ($order->status == "Pending Approval") {
                    $pendingApproval[] = $order;
                }
            }

            $count = count($pendingApproval);
        }

        return response()->json(['count' => $count]);
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
        if (Auth::user()->cant('launch', Content::class)) {
            return response()->json([ 'data' => Limit::feedbackMessage('content_launch') ], 403);
        }
        Auth::user()->addToLimit('content_launch');

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
            ], empty($errors) ? 201 : 500);
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
        $classInstance = new $class($content, $connection);

        $contentType = strtolower($content->contentType->name);

        if($contentType == 'landing page' && method_exists($classInstance,'createPage')) {
            $create = $classInstance->createPage();
        }
        elseif($contentType == 'email' && method_exists($classInstance,'createEmail')) {
            $create = $classInstance->createEmail();
        }
        else {
            // default action
            $create = $classInstance->createPost();
        }

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
        if (Auth::user()->cant('launch', Content::class)) {
            return redirect()->route('contents.index')->with([
                'flash_message' => Limit::feedbackMessage('content_launch'),
                'flash_message_type' => 'danger',
            ]);
        }
        Auth::user()->addToLimit('content_launch');

        $content = Content::find($contentId);

        try {
            $this->publish($content);
        }
        catch (Exception $e) {
            return redirect()->route('contents.index')->with([
                'flash_message' => $e->getMessage(),
                'flash_message_type' => 'danger',
            ]);
        }

        return redirect()->route('contents.index')->with([
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
            'calendarsDropdown' => $this->selectedAccount->calendarsDropdown(),
            'buyingStageDropdown' => BuyingStage::dropdown(),
            'personaDropdown' => Persona::dropdown(),
            'campaignDropdown' => CampaignPresenter::dropdown(),
            'connections' => Connection::dropdown(),
            'contentTypeDropdown' => ContentTypePresenter::dropdown(),
            'isPublished' => false,
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
            'connectionsDetails' => $this->selectedAccount->getActiveConnections(),
            'mailchimpSettings' => json_decode($content->mailchimp_settings),
            'contentTagsJson' => $content->present()->tagsJson,
            'authorDropdown' => $this->selectedAccount->authorsDropdown(),
            'relatedContentDropdown' => $this->selectedAccount->relatedContentsDropdown(),
            'calendarsDropdown' => $this->selectedAccount->calendarsDropdown(),
            'buyingStageDropdown' => BuyingStage::dropdown(),
            'personaDropdown' => Persona::dropdown(),
            'campaignDropdown' => CampaignPresenter::dropdown(),
            'connections' => Connection::dropdown(),
            'contentTypeDropdown' => ContentTypePresenter::dropdown(),
            'files' => $content->attachments()->where('type', 'file')->get(),
            'images' => $content->attachments()->where('type', 'image')->get(),
            'isCollaborator' => $content->hasCollaborator(Auth::user()),
            'tasks' => $content->tasks()->with('user')->get(),
            'isPublished' => isset($content) && $content->status  && $content->status->slug == 'published',
            'guidelines' => $this->selectedAccount->guidelines,
            'customContentType' => isset($content->customContentType) ? $content->customContentType->name : ''
        ];

        return view('content.editor', $data);
    }

    public function getContentTypes()
    {
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
        } elseif($request->input('action') == 'ready_to_publish') {
            $validation = $this->onSubmitValidation($request->all());
        } else {
            $validation = $this->onPublishValidation($request->all());
        }

        if ($validation->fails()) {
            $request->flash();
            return redirect("/edit/$content->id")->with('errors', $validation->errors());
        }

        if (!$content->hasCollaborator(Auth::user())) {
            return redirect()->route('contents.index')->with([
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
        $this->saveMailchimpSettings($request, $content);
        $this->saveAsCalendarContent($request, $content);
        $this->saveCustomContentType($request, $content);

        // - Attach the related data
        if ($request->input('related')) {
            $content->related()->attach($request->input('related'));
        }

        $this->handleImages($request, $content);
        $this->handleFiles($request, $content);

        if ($request->action == 'publish') {
            return $this->publishAndRedirect($request, $content->id);
        } else {
            $content->configureAction($request->input('action'));
            $content->save();

            return redirect()->route('contents.index')->with([
                'flash_message' => 'You have created content titled '.$content->title.'.',
                'flash_message_type' => 'success',
                'flash_message_important' => true,
            ]);
        }
    }

    private function onSubmitValidation(array $requestData)
    {
        return Validator::make($requestData, [
            'content_type_id' => 'required|exists:content_types,id',
            'due_date' => 'required',
            'title' => 'required',
            'content_id' => 'required|exists:contents,id',
            'body' => 'required',
        ],
        [
            'content_type_id.required' => 'The content type is required.',
            'body.required' => 'The content body field is required.',
        ]);
    }

    private function onPublishValidation(array $requestData)
    {
        return Validator::make($requestData, [
            'content_type_id' => 'required|exists:content_types,id',
            'due_date' => 'required',
            'title' => 'required',
            'connection_id' => 'required|exists:connections,id',
            'content_id' => 'required|exists:contents,id',
            'body' => 'required',
        ],
            [
                'content_type_id.required' => 'The content type is required.',
                'body.required' => 'The content body field is required.',
                'connection_id.required' => 'The content destination is required.'
            ]);
    }

    private function onSaveValidation(array $requestData)
    {
        return Validator::make($requestData, [
            'content_type_id' => 'required|exists:content_types,id',
            'custom_content_type' => 'required_if:custom_content_type_present,true',
            'title' => 'required',
        ],
        [
            'content_type_id.required' => 'The content type is required.',
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
        $content->title = $request->title;
        $content->body = $request->body;
        $content->due_date = $request->due_date;
        $content->meta_title = $request->meta_title;
        $content->meta_keywords = $request->meta_keywords;
        $content->meta_description = $request->meta_description;
        $content->email_subject = $request->email_subject;
        $content->save();

        $this->selectedAccount->contents()->save($content);

        return $content;
    }

    private function saveConnections($request, $content)
    {
        if ($request->connection_id) {
            $connection = Connection::find($request->connection_id);
            $connection->contents()->save($content);
        }
    }

    private function saveContentCampaign($request, $content)
    {
        if ($request->campaign_id) {
            $campaign = Campaign::find($request->campaign_id);
            $campaign->contents()->save($content);
        }
    }

    private function saveContentBuyingStage($request, $content)
    {
        if ($request->buying_stage_id) {
            $buyingStage = BuyingStage::find($request->buying_stage_id);
            $buyingStage->contents()->save($content);
        }
    }

    private function saveContentPersona($request, $content)
    {
        if ($request->persona_id) {
            $persona = Persona::find($request->persona_id);
            $persona->contents()->save($content);
        }
    }

    private function saveContentType($request, $content)
    {
        if ($request->content_type_id) {
            $conType = ContentType::find($request->content_type_id);
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

    private function saveMailchimpSettings($request, $content)
    {
        $mailchimpSettings = [
            'list' => $request->input('mailchimp_list'),
            'from_name' => $request->input('mailchimp_from_name'),
            'reply_to' => $request->input('mailchimp_reply_to'),
            'feed_url' => $request->input('mailchimp_feed_url')
        ];

        $content->mailchimp_settings = json_encode($mailchimpSettings);
        $content->save();
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
        return redirect()->route('contents.index')->with([
           'flash_message' => 'You have successfully deleted '.$content->title.'.',
           'flash_message_type' => 'success',
           'flash_message_important' => true,
       ]);
    }

    protected function redirectDeleteFailed()
    {
        return redirect()->route('contents.index')->with([
            'flash_message' => 'Unable to delete content: not found.',
            'flash_message_type' => 'danger',
            'flash_message_important' => true,
        ]);
    }

    protected function saveAsCalendarContent(Request $request, Content $content)
    {
        $calendarId = $request->input('calendar_id');
        $calendar = Calendar::find($calendarId);

        if ($calendarId && $calendar->count()) {
            $content->calendar()->associate($calendar);
            $content->save();
        }
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

    private function saveCustomContentType (Request $request, Content $content = null)
    {
        $customContentType = null;

        if($request->has('custom_content_type')) {
            $customContentType = CustomContentType::whereName($request->input('custom_content_type'))->first();

            if (!$customContentType) {
                $customContentType = CustomContentType::create([
                    'name' => $request->input('custom_content_type')
                ]);
            }

            if($content !== null) {
                $content->customContentType()->associate($customContentType);
                $content->save();
            }
        }

        return $customContentType;
    }

}
