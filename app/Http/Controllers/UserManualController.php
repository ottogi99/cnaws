<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class UserManualController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth', ['except' => ['index', 'show']]);
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

    public function create()
    {
        $manual = new \App\UserManual;

        return view('user_manual.create', compact('manual'));
    }

    public function store(Request $request)
    {
        // 유효성 검사
        $rules = [
            'title' => ['required'],
            'content' => ['required'],
            'files' => ['array'],
            // 'files.*' => ['mimes:hwp,xls,zip', 'max:30000'],
            'files.*' => ['max:30720'], //10240 = 10MB
        ];

        $messages = [
            'title.required' => '제목은 필수 입력 항목입니다.',
            'max' => ':attribute은(는)의 최대 크기는 30M입니다.',
        ];

        $attributes = [
            'files'     => '첨부파일',
            'files.0'   => '첨부파일',
        ];

        $this->validate($request, $rules, $messages, $attributes);

        // $validator = \Validator::make($request->all(), $rules, $messages);
        // if ($validtor->fails()) {
        //     return back()->withErrors($validator)->withInput();
        // }

        $payload = array_merge($request->all(), [
          'hit' => $request->input('hit', 0),
        ]);

        // $manual = \App\User::find(1)->articles()->create($request->all());
        $manual = $request->user()->manuals()->create($payload);

        // 파일 저장
        if ($request->hasFile('files')) {
           $files = $request->file('files');

           foreach($files as $file) {
              $filename = Str::random().filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);
              $filesize = $file->getSize();
              $file->move(attachments_path(), $filename);

              $manual->attachments()->create([
                  'stored_name' => $filename,
                  'original_name' => $file->getClientOriginalName(),
                  'bytes' => $filesize,
                  'mime' => $file->getClientMimeType()
              ]);
           }
        }

        flash()->success('사용자매뉴얼이 등록되었습니다.');

        return redirect(route('user_manual.index', $manual->id));
    }

    public function show($id)
    {
        $manual = \App\UserManual::findOrFail($id);
        // get previous user id
        // $previous = \App\UserManual::where('id', '<', $id)->max('id');
        $previous = \App\UserManual::where('id', '<', $id)->orderBy('id', 'desc')->first();
        // get next user id
        // $next = \App\UserManual::where('id', '>', $id)->min('id');
        $next = \App\UserManual::where('id', '>', $id)->first();

        $manual->update(['hit' => $manual->hit + 1]);

        return view('user_manual.show', compact('manual', 'previous', 'next'));
    }

    public function edit($id)
    {
        $manual = \App\UserManual::findOrFail($id);
        $this->authorize('user-manual-update', $manual);

        return view('user_manual.edit', compact('manual'));
    }

    public function update(Request $request, $id)
    {
        $manual = \App\UserManual::findOrFail($id);
        $manual->update($request->all());

        if ($request->hasFile('files')) {
           $files = $request->file('files');

           foreach($files as $file) {
              $filename = Str::random().filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);
              $filesize = $file->getSize();
              $file->move(attachments_path(), $filename);

              $manual->attachments()->create([
                  'stored_name' => $filename,
                  'original_name' => $file->getClientOriginalName(),
                  'bytes' => $filesize,
                  'mime' => $file->getClientMimeType()
              ]);
           }
        }

        flash()->success('수정하신 내용을 저장했습니다.');

        return redirect(route('user_manual.show', $manual->id));
    }

    public function destroy($id)
    {
        $manual = \App\UserManual::findOrFail($id);

        $this->authorize('user-manual-delete', $manual);

        DB::beginTransaction();
        try
        {
            $attachments = $manual->attachments;
            Log::debug($attachments);
            foreach($attachments as $attachment) {
                $attachment->delete();
            }

            $manual->delete();
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

        // $this->authorize('manual-delete', $manual);
        $attachment->delete();

        return response()->json([
          'success' => '첨부파일이 삭제되었습니다.'
        ], 204);
    }
}
