<?php

namespace app\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use Auth;
use App\Account;
use App\Idea;
use App\IdeaContent;
use App\User;
use App\Content;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ideas = Account::selectedAccount()
            ->ideas()
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->get()
            ->map(function ($idea) {
                $idea->created_diff = $idea->createdAtDiff;
                $idea->updated_diff = $idea->updatedAtDiff;

                return $idea;
            });

        return response()->json($ideas);
    }

    // Parks the idea
    public function park(Request $request)
    {
        $id = $request->input('idea_id');
        $idea = Idea::where([['id', $id], ['user_id', Auth::id()]])->first();
        $idea->status = 'parked';
        $idea->save();

        return response()->json($idea);
    }

    public function activate(Request $request)
    {
        $id = $request->input('idea_id');
        $idea = Idea::where([['id', $id], ['user_id', Auth::id()]])->first();
        $idea->status = 'active';
        $idea->save();

        return response()->json($idea);
    }

    public function reject(Request $request, $id)
    {
        $idea = Idea::where([['id', $id], ['user_id', Auth::id()]])->first();
        $idea->status = 'rejected';
        $idea->save();

        return response()->json($idea);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $text = $request->input('idea');
        $tags = $request->input('tags');
        $contents = $request->input('content');
        $status = $request->input('status');

        $idea = new Idea();
        $idea->name = $name;
        $idea->text = $text;
        $idea->tags = $tags;
        $idea->status = $status;

        $idea->user_id = Auth::id();
        $idea->account_id = Account::selectedAccount()->id;
        $idea->save();

        $idea->collaborators()->attach(Auth::user());

        $idea_contents = array();

        if (!empty($contents) && is_array($contents)) {
            foreach ($contents as $content) {
                //if its just keywords
                if (isset($content['keyword'])) {
                    $idea_content = new IdeaContent();
                    $idea_content->title = 'Trending Topic';
                    $idea_content->body = $content['keyword'];

                    $idea_content->idea_id = $idea->id;
                    $idea_content->user_id = Auth::id();

                    $idea_content->save();
                    $idea_contents[] = $idea_content;

                //if its actual content trends
                } else {
                    $idea_content = new IdeaContent();
                    $idea_content->author = $content['author'];
                    $idea_content->body = $content['body'];
                    $idea_content->fb_shares = $content['fb_shares'];
                    $idea_content->google_shares = $content['google_shares'];
                    $idea_content->image = $content['image'];
                    $idea_content->link = $content['link'];
                    $idea_content->source = $content['source'];
                    $idea_content->title = $content['title'];
                    $idea_content->total_shares = $content['total_shares'];
                    $idea_content->tw_shares = $content['tw_shares'];

                    $idea_content->idea_id = $idea->id;
                    $idea_content->user_id = Auth::id();

                    $idea_content->save();
                    $idea_contents[] = $idea_content;
                }
            }
        }

        //do sanity/success checks here
        return response()->json([$idea->name, $idea->text, $idea->tags, $idea_contents]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Idea $idea)
    {
        $response = response()->json(['error' => 'User not authorized'], 403);

        if ($idea->hasCollaborator(Auth::user())) {
            $idea->update([
                'name' => $request->input('name'),
                'idea' => $request->input('idea'),
                'tags' => $request->input('tags'),
            ]);

            $response = response()->json($idea);
        }

        return response()->json($idea);
    }

    /**
     * Converts the idea to a piece of content and redirects the
     * user to edit it.
     *
     * @param Request $request
     * @param Idea    $idea
     *
     * @return \Illuminate\Http\Redirect
     */
    public function write(Request $request, Idea $idea)
    {
        if (!$this->isLoggedUserCollaborator($idea)) {
            return $this->redirectWithoutPermission();
        }

        $content = $this->createContentFromIdea($idea);

        return $this->redirectToContentEditor($content);
    }

    private function createContentFromIdea(Idea $idea)
    {
        $newContent = Content::create([
            'title' => $idea->name,
            'text' => $idea->text,
        ]);

        $newContent->authors()->attach(Auth::user());
        $idea->contents()->attach($newContent);

        Account::selectedAccount()->contents()->save($newContent);

        return $newContent;
    }

    private function redirectWithoutPermission()
    {
        return redirect('/')->with([
            'flash_message' => 'You don\'t have the permission to do that.',
            'flash_message_type' => 'danger',
            'flash_message_important' => true,
        ]);
    }

    private function redirectToContentEditor(Content $content)
    {
        return redirect()
            ->route('editContent', $content)
            ->with([
                'flash_message' => 'Content was created successfully.',
                'flash_message_type' => 'success',
                'flash_message_important' => true,
            ]);
    }

    private function isLoggedUserCollaborator(Idea $idea)
    {
        return $idea->hasCollaborator(Auth::user());
    }
}
