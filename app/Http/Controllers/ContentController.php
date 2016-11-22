<?php

namespace App\Http\Controllers;

use App\Http\Requests\Content\ContentRequest;
use Illuminate\Support\Facades\File;
use App\ContentType;
use App\BuyingStage;
use App\Connection;
use App\Attachment;
use App\Campaign;
use App\Content;
use App\User;
use App\Account;
use App\Tag;
use App\Persona;
use App\Helpers;
use App\WriterAccessPrice;
use Storage;
use View;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Response;

class ContentController extends Controller
{
    public function index()
    {
        $selectedAccount = Account::selectedAccount();

        $countContent = $selectedAccount
            ->contents()
            ->count();

        $published = $selectedAccount
            ->contents()
            ->where('published', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        $readyPublished = $selectedAccount
            ->contents()
            ->where('ready_published', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        $written = $selectedAccount
            ->contents()
            ->where('written', 1)
            ->orderBy('updated_at', 'desc')
            ->get();

        $connections = $selectedAccount
            ->connections()
            ->where('active', 1)
            ->get();

        return view('content.index', compact(
            'published', 'readyPublished', 'written', 'countContent', 'connections'
        ));
    }

    public function store(Request $req)
    {
        $content = new Content();

        $content->title = $req->input('title');
        $content->content_type_id = $req->input('content_type');
        $content->save();

        // - Attach to the user
        Auth::user()->contents()->save($content);

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

        $contenttypedd = ContentType::dropdown();
        $campaigndd = Campaign::dropdown();

        $data = compact('contentTypes', 'pricesJson', 'contenttypedd', 'campaigndd');

        return view('content.create', $data);
    }

    public function directPublish(Request $request, $contentId)
    {
        $content = Content::find($contentId);
        $connections = collect(explode(',', $request->input('connections')))
            ->map(function ($connectionId) {
                return Connection::find($connectionId);
            });

        $response = response()->json(['data' => 'Content not found'], 404);

        if ($content) {
            $errors = [];
            $connections_count = 0;
            $connections_failed = 0;

            foreach ($connections as $connection) {
                $response = $this->publish($content, $connection);

                if (!$response['success']) {
                    $connectionName = (string) $connection;
                    $errors [] = [$connectionName => $response['error']];
                    ++$connections_failed;
                }

                ++$connections_count;
            }

            $response = response()
                ->json([
                    'data' => 'Content published',
                    'errors' => $errors,
                    'content' => $content,
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
            'tagsDropdown' => Tag::dropdown(),
            'authorDropdown' => User::dropdown(),
            'relatedContentDropdown' => Content::dropdown(),
            'buyingStageDropdown' => BuyingStage::dropdown(),
            'personaDropdown' => Persona::dropdown(),
            'campaignDropdown' => Campaign::dropdown(),
            'connections' => Connection::dropdown(),
            'contentTypeDropdown' => ContentType::dropdown(),
        ];

        return view('content.editor', $data);
    }

    // - edit content on page
    public function editContent(Content $content)
    {
        $data = [
            'content' => $content,
            'tagsDropdown' => Tag::dropdown(),
            'authorDropdown' => User::dropdown(),
            'relatedContentDropdown' => Content::dropdown(),
            'buyingStageDropdown' => BuyingStage::dropdown(),
            'personaDropdown' => Persona::dropdown(),
            'campaignDropdown' => Campaign::dropdown(),
            'connections' => Connection::dropdown(),
            'contentTypeDropdown' => ContentType::dropdown(),
            'files' => $content->attachments()->where('type', 'file')->get(),
            'images' => $content->attachments()->where('type', 'image')->get(),
        ];

        return view('content.editor', $data);
    }

    public function editStore(ContentRequest $request, $id = null)
    {
        $content = is_numeric($id) ? Content::find($id) : new Content();
        $content = $this->detachRelatedContent($content);
        $content = $this->saveContentAndAttachToUser($request, $content);

        $this->saveContentCampaign($request, $content);
        $this->saveContentBuyingStage($request, $content);
        $this->saveContentPersona($request, $content);
        $this->saveContentType($request, $content);

        // - Attach the related data
        if ($request->input('related')) {
            $content->related()->attach($request->input('related'));
        }

        // - Save connection
        $connection = Connection::find($request->input('connections'));
        $connection->contents()->save($content);

        // Attach authors
        $content->authors()->attach($request->input('author'));

        // Attach Tags
        $content->tags()->attach($request->input('tags'));

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

    private function detachRelatedContent($content)
    {
        $content->related()->detach();
        $content->tags()->detach();
        $content->authors()->detach();

        return $content;
    }

    private function saveContentAndAttachToUser($request, $content)
    {
        $content->configureAction($request->input('action'));

        $content->title = $request->input('title');
        $content->body = $request->input('content');
        $content->due_date = $request->input('due_date');
        $content->meta_title = $request->input('meta_title');
        $content->meta_keywords = $request->input('meta_keywords');
        $content->meta_description = $request->input('meta_descriptor');
        $content->written = 1;
        $content->save();

        Auth::user()->contents()->save($content);

        return $content;
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
        $conType = ContentType::find($request->input('content_type'));
        $conType->contents()->save($content);
    }

    public function delete(Request $request, $content_id)
    {
        $content = Content::find($content_id);

        if ($content) {
            $this->detachRelatedContent($content);
            $content->attachments()->delete();
            $content->delete();

            return redirect()->route('contentIndex')->with([
                'flash_message' => 'You have successfully deleted '.$content->title.'.',
                'flash_message_type' => 'success',
                'flash_message_important' => true,
            ]);
        } else {
            return redirect()->route('contentIndex')->with([
                'flash_message' => 'Unable to delete content: not found.',
                'flash_message_type' => 'danger',
                'flash_message_important' => true,
            ]);
        }
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

    /**
     * Function to upload files to S3 and save them in the database.
     *
     * @param ContentRequest $request  The Request instance
     * @param Content        $content  Content instance
     * @param string         $filetype A string indicating the filetype.
     *                                 Images should be 'image'. Everything else will
     *                                 be treated as files
     */
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
