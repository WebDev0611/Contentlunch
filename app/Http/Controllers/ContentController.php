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
use App\Tag;
use App\Helpers;
use Storage;
use View;
use Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Response;

class ContentController extends Controller
{
    public function index()
    {
        $countContent = Auth::user()->contents()->count();
        $published = Auth::user()->contents()->where('published', 1)->orderBy('updated_at', 'desc')->get();
        $readyPublished = Auth::user()->contents()->where('ready_published', 1)->orderBy('updated_at', 'desc')->get();
        $written = Auth::user()->contents()->where('written', 1)->orderBy('updated_at', 'desc')->get();
        $connections = Auth::user()->connections()->where('active', 1)->get();

        return View::make('content.index', compact('published', 'readyPublished', 'written', 'countContent', 'connections'));
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

                $wordcounts = DB::table('writer_access_prices')
                    ->distinct()
                    ->select('wordcount')
                    ->where('asset_type_id', $price->asset_type_id)
                    ->get();

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

        return View::make('content.create', compact('contentTypes', 'pricesJson', 'contenttypedd', 'campaigndd'));
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
            'tagsdd' => Tag::dropdown(),
            'authordd' => User::dropdown(),
            'relateddd' => Content::dropdown(),
            'stageddd' => BuyingStage::dropdown(),
            'campaigndd' => Campaign::dropdown(),
            'connectionsdd' => Connection::dropdown(),
            'contenttypedd' => ContentType::dropdown(),
        ];

        return View::make('content.editor', $data);
    }

    // - edit content on page
    public function editContent(Content $content)
    {
        $data = [
            'content' => $content,
            'tagsdd' => Tag::dropdown(),
            'authordd' => User::dropdown(),
            'relateddd' => Content::dropdown(),
            'stageddd' => BuyingStage::dropdown(),
            'campaigndd' => Campaign::dropdown(),
            'connectionsdd' => Connection::dropdown(),
            'contenttypedd' => ContentType::dropdown(),
        ];

        return View::make('content.editor', $data);
    }

    public function editStore(ContentRequest $request, $id = null)
    {
        // - Default to creating a new Content
        $content = new Content();

        if (is_numeric($id)) {
            $content = Content::find($id);
            $content->touch();
            // - Remove all related, will attach back ( keeps pivot table clean )
            $content->related()->detach();
            $content->tags()->detach();
            $content->authors()->detach();
        }

        $action = $request->input('action');

        $content->configureAction($request->input('action'));

        $content->title = $request->input('title');
        $content->body = $request->input('content');
        $content->due_date = $request->input('due_date');
        $content->meta_title = $request->input('meta_title');
        $content->meta_keywords = $request->input('meta_keywords');
        $content->meta_description = $request->input('meta_descriptor');
        $content->written = 1;
        $content->save();

        // - Attach to the user
        Auth::user()->contents()->save($content);

        // IF compaign lets attach it
        if ($request->input('campaign')) {
            // - Save Campaign
            $campaign = Campaign::find($request->input('campaign'));
            $campaign->contents()->save($content);
        }

        // - Attach the related data
        if ($request->input('related')) {
            $content->related()->attach($request->input('related'));
        }

        // - Save Content Type
        $contentType = $request->input('content_type');
        $conType = ContentType::find($contentType);
        $conType->contents()->save($content);

        // - Save connection
        $connection = Connection::find($request->input('connections'));
        $connection->contents()->save($content);

        // Attach authors
        $content->authors()->attach($request->input('author'));

        // Attach Tags
        $content->tags()->attach($request->input('tags'));

        // - Images
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

    public function delete(Request $request, $content_id)
    {
        $content = Content::find($content_id);

        if ($content) {
            $content->related()->detach();
            $content->tags()->detach();
            $content->authors()->detach();
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
        $userFolder = 'attachment/' . Auth::id() . ($fileType == 'image' ? '/images/' : '/files/');

        foreach ($files as $fileUrl) {
            $newPath = $this->moveFileToUserFolder($fileUrl, $userFolder);
            $attachment = $this->createAttachment($newPath, $fileType);
            $content->attachments()->save($attachment);
        }
    }

    private function moveFileToUserFolder($fileUrl, $userFolder)
    {
        $fileName = substr(strstr($fileUrl, '_tmp/'), 5);
        $newPath = $userFolder . $fileName;
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
            'mime' => Storage::mimeType($s3Path)
        ]);
    }

    /**
     * Asynchronous attachments and images uploads
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

        return response()->json([ 'file' => $url ]);
    }

    private function attachmentValidator($input)
    {
        return Validator::make($input, [
            'file' => 'file|max:20000'
        ]);
    }

    private function imageValidator($input)
    {
        return Validator::make($input, [
            'file' => 'image|max:3000'
        ]);
    }

    public function get_written($step = 1)
    {
        //need to do proper form validation, etc.
        //this is just to get the UI spit out

        if ($step == 1) {
            return View::make('content.get_written_1');
        }
        if ($step == 2) {
            return View::make('content.get_written_2');
        }
        if ($step == 3) {
            return View::make('content.get_written_3');
        }
    }
}
