<?php

namespace App\Http\Controllers;

use App\Account;
use App\Content;
use App\Idea;
use App\IdeaContent;
use App\Limit;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Storage;

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
        $idea = Idea::where([
            'id' => $id,
            'user_id' => Auth::id(),
        ])->first();

        $idea->park();

        return response()->json($idea);
    }

    public function activate(Request $request)
    {
        $id = $request->input('idea_id');
        $idea = Idea::where([
            'id' => $id,
            'user_id' => Auth::id(),
        ])->first();

        $idea->activate();

        return response()->json($idea);
    }

    public function reject(Request $request, $id)
    {
        $idea = Idea::where([
            'id' => $id,
            'user_id' => Auth::id(),
        ])->first();

        $idea->reject();

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
        if (Auth::user()->cant('create', Idea::class)) {
            return response()->json([ 'data' => Limit::feedbackMessage('ideas_created') ], 403);
        }
        Auth::user()->addToLimit('ideas_created');

        $idea = Idea::create([
            'name' => $request->name,
            'text' => $request->idea,
            'tags' => $request->tags,
            'status' => $request->status,
            'user_id' => Auth::id(),
            'account_id' => Account::selectedAccount()->id,
        ]);

        if ($request->collaborators) {
            $idea->collaborators()->sync($request->collaborators);
        } else {
            $idea->collaborators()->attach(Auth::user());
        }

        $idea_contents = $this->createIdeaContents($idea, $request->input('content'));
        
        return response()->json([
            $idea->name,
            $idea->text,
            $idea->tags,
            $idea_contents,
        ]);
    }

    protected function createIdeaContents($idea, $contents = [])
    {
        return collect($contents)->map(function($content) use ($idea) {

            if (isset($content['keyword'])) {

                return IdeaContent::create([
                    'title' => $content['keyword'],
                    'body' => $content['keyword'],
                    'idea_id' => $idea->id,
                    'user_id' => Auth::id(),
                ]);

            } else {

                return IdeaContent::create([
                    'author' => $content['author'],
                    'body' => $content['body'],
                    'fb_shares' => $content['fb_shares'],
                    'google_shares' => $content['google_shares'],
                    'image' => $content['image'],
                    'link' => $content['link'],
                    'source' => $content['source'],
                    'title' => $content['title'],
                    'total_shares' => $content['total_shares'],
                    'tw_shares' => $content['tw_shares'],
                    'idea_id' => $idea->id,
                    'user_id' => Auth::id(),
                ]);
            }
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Idea $idea
     * @return \Illuminate\Http\Response
     * @internal param int $id
     *
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
     * @param Idea $idea
     * @return \Illuminate\Http\RedirectResponse
     */
    public function write(Request $request, Idea $idea)
    {
        $content = $this->createContentFromIdea($idea);
        $idea->park();

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
