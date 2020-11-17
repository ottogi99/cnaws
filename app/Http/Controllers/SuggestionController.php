<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SuggestionController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth', ['except' => ['index', 'show']]);
        $this->middleware('auth');
    }

    public function index()
    {
        $keyword = request()->input('q');
        $query = \App\Suggestion::when($keyword, function($query, $keyword) {
                                    return $query->whereRaw('MATCH(title, content) AGAINST (? IN BOOLEAN MODE)', ['+'.$keyword.'*']);
                                })
                                ->orderby('created_at', 'desc');

        $suggestions = $query->paginate(20);

        return view('suggestion.index', compact('suggestions'));
    }

    public function create()
    {
        $suggestion = new \App\Suggestion;

        return view('suggestion.create', compact('suggestion'));
    }

    public function store(Request $request)
    {
        // 유효성 검사
        $rules = [
            'title' => ['required'],
            'content' => ['required'],
            'files' => ['array'],
            // 'files.*' => ['mimes:hwp,xls,zip', 'max:30000'],
            'files.*' => ['max:15360'], //10240 = 10MB
        ];

        // $validator = \Validator::make($request->all(), $rules, $messages);
        // if ($validtor->fails()) {
        //     return back()->withErrors($validator)->withInput();
        // }

        $messages = [
            'required' => ':attribute은(는)은 필수 입력 항목입니다.',
            'max' => ':attribute은(는)의 최대 크기는 15M입니다.',
        ];

        $attributes = [
            'titile'    => '제목',
            'content'   => '내용',
            'files'     => '첨부파일',
            'files.0'   => '첨부파일',
        ];

        $this->validate($request, $rules, $messages, $attributes);

        $payload = array_merge($request->all(), [
          'hit' => $request->input('hit', 0),
        ]);

        // $suggestion = \App\User::find(1)->articles()->create($request->all());
        $suggestion = $request->user()->suggestions()->create($payload);

        // 파일 저장
        if ($request->hasFile('files')) {
           $files = $request->file('files');

           foreach($files as $file) {
              $filename = Str::random().filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);
              $filesize = $file->getSize();
              $file->move(attachments_path(), $filename);

              $suggestion->attachments()->create([
                  'stored_name' => $filename,
                  'original_name' => $file->getClientOriginalName(),
                  'bytes' => $filesize,
                  'mime' => $file->getClientMimeType()
              ]);
           }
        }

        flash()->success('건의사항을 등록하였습니다.');

        return redirect(route('suggestion.index', $suggestion->id));
    }

    public function show($id)
    {
        $suggestion = \App\Suggestion::findOrFail($id);

        $this->authorize('suggestion-show', $suggestion);

        // get previous user id
        // $previous = \App\Suggestion::where('id', '<', $id)->max('id');
        $previous = \App\Suggestion::where('id', '<', $id)->orderBy('id', 'desc')->first();
        // get next user id
        // $next = \App\Suggestion::where('id', '>', $id)->min('id');
        $next = \App\Suggestion::where('id', '>', $id)->first();

        $suggestion->update(['hit' => $suggestion->hit + 1]);

        // 댓글
        $comments = $suggestion->comments()->latest()->get();

        return view('suggestion.show', compact('suggestion', 'previous', 'next', 'comments'));
    }

    public function edit($id)
    {
        $suggestion = \App\Suggestion::findOrFail($id);
        $this->authorize('suggestion-edit', $suggestion);

        return view('suggestion.edit', compact('suggestion'));
    }

    public function update(Request $request, $id)
    {
        $suggestion = \App\Suggestion::findOrFail($id);
        $suggestion->update($request->all());

        if ($request->hasFile('files')) {
           $files = $request->file('files');

           foreach($files as $file) {
              $filename = Str::random().filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);
              $filesize = $file->getSize();
              $file->move(attachments_path(), $filename);

              $suggestion->attachments()->create([
                  'stored_name' => $filename,
                  'original_name' => $file->getClientOriginalName(),
                  'bytes' => $filesize,
                  'mime' => $file->getClientMimeType()
              ]);
           }
        }

        flash()->success('수정하신 내용을 저장했습니다.');

        return redirect(route('suggestion.show', $suggestion->id));
    }

    public function destroy($id)
    {
        $suggestion = \App\Suggestion::findOrFail($id);

        $this->authorize('suggestion-delete', $suggestion);

        DB::beginTransaction();
        try
        {
            $attachments = $suggestion->attachments;
            Log::debug($attachments);
            foreach($attachments as $attachment) {
                $attachment->delete();
            }

            $suggestion->delete();
            DB::commit();

            flash()->success('공지사항을 삭제하였습니다.');
            return response()->json([], 204);
        }
        catch (Exception $e)
        {
            DB::rollback();
            return response()->json([], 500);
        }
    }

    public function download_file($id)
    {
        $attachment = \App\Attachment::findOrFail($id);
        // $attachment->mime;
        // $attachment->filename;
        // $attachment->bytes;

        // header('Content-Type', 'text/plain');
        // $file = public_path("myReportFile.pdf");
        // $headers = ['Content-Type: application/pdf'];

        $filename = $attachment->stored_name;
        $file = public_path('files/').$attachment->stored_name;
        $headers = ['Content-Type: '.$attachment->mime];
        $name = $attachment->original_name;

        return response()->download($file, $name, $headers);
    }

    public function delete_file($id)
    {
        $attachment = \App\Attachment::findOrFail($id);

        // $this->authorize('suggestion-delete', $suggestion);
        $attachment->delete();

        return response()->json([
          'success' => '첨부파일이 삭제되었습니다.'
        ], 204);
    }
}
