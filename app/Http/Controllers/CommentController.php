<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $keyword = request()->input('q');
        $query = \App\UserManual::when($keyword, function($query, $keyword) {
                                    return $query->whereRaw('MATCH(title, content) AGAINST (? IN BOOLEAN MODE)', ['+'.$keyword.'*']);
                                })
                                ->orderby('created_at', 'desc');

        $manuals = $query->paginate(20);

        return view('user_manual.index', compact('manuals'));
    }

    public function create($request)
    {
        // Log::debug($request->all());

        // $comment = new \App\Comment;
        // $comment->update($request->all());

        // $comment->create([
        //     'user_id' => $request->user()->id,
        //     'suggestion_id' => $request->input('suggestion_id'),
        //     'content' => $request->input('content'),
        // ]);

        // return response()->json([
        //   'success' => '생성되었습니다'
        // ], 204);
    }

    public function store(Request $request)
    {
        Log::debug($request->all());

        // 유효성 검사
        // $rules = [
        //     'content' => ['required', 'min:10'],
        // ];
        //
        // $messages = [
        //     'content.min' => '댓글은 최소 10자 이상입니다.'
        // ];
        //
        // $this->validate($request, $rules, $messages);

        // $suggestion = \App\Suggestion::findOrFail($id);
        //
        // $comment = $suggestion->comments()->creae(array_merge(
        //       $request->all(),
        //       ['user_id' => $request->user()->id]
        //   )
        // );
        //
        // flash()->success('댓글이 저장되었습니다.');
        //
        // return redirect(route('suggestion.show', $suggestion->id));

        $suggestion = \App\Suggestion::findOrFail($request->input('suggestion_id'));

        $comment = $suggestion->comments()->create([
            'user_id' => $request->user()->id,
            'suggestion_id' => $request->input('suggestion_id'),
            'content' => $request->input('content'),
        ]);

        // Log::debug($comment);
        // Log::debug($comment->user);

        return response()->json([
          'status' => 'success',
          'comment' => $comment,
          'user' => $comment->user
        ], 200);
    }

    public function show($id)
    {
        // $manual = \App\UserManual::findOrFail($id);
        // // get previous user id
        // // $previous = \App\UserManual::where('id', '<', $id)->max('id');
        // $previous = \App\UserManual::where('id', '<', $id)->orderBy('id', 'desc')->first();
        // // get next user id
        // // $next = \App\UserManual::where('id', '>', $id)->min('id');
        // $next = \App\UserManual::where('id', '>', $id)->first();
        //
        // $manual->update(['hit' => $manual->hit + 1]);
        //
        // return view('user_manual.show', compact('manual', 'previous', 'next'));
    }

    public function edit($id)
    {
        // $manual = \App\UserManual::findOrFail($id);
        // $this->authorize('user-manual-update', $manual);
        //
        // return view('user_manual.edit', compact('manual'));
    }

    public function update(Request $request, $id)
    {
        $comment = \App\Comment::findOrFail($id);
        $comment->update($request->all());

        return response()->json([
          'success' => '수정되었습니다'
        ], 204);
    }

    public function destroy($id)
    {
        $comment = \App\Comment::findOrFail($id);

        // $this->authorize('comment-delete', $manual);
        $comment->delete();

        return response()->json([
          'success' => '수정되었습니다'
        ], 204);
    }


    public function delete_file($id)
    {
        $attachment = \App\Attachment::findOrFail($id);

        // $this->authorize('manual-delete', $manual);
        $attachment->delete();

        return response()->json([
          'success' => '첨부파일이 삭제되었습니다.'
        ], 204);
    }
}
