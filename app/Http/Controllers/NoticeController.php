<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Exports\NoticeExport;
// use App\Imports\NoticeImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;

class NoticeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth', ['except' => ['index', 'show']]);
        $this->middleware('auth');
    }

    public function index()
    {
        $keyword = request()->input('q');
        $query = \App\Notice::when($keyword, function($query, $keyword) {
                                    return $query->whereRaw('MATCH(title, content) AGAINST (? IN BOOLEAN MODE)', ['+'.$keyword.'*']);
                                })
                                ->orderby('created_at', 'desc');

        $notices = $query->paginate(20);

        return view('notice.index', compact('notices'));
    }

    public function create()
    {
        $notice = new \App\Notice;

        return view('notice.create', compact('notice'));
    }

    public function store(Request $request)
    {
        // 유효성 검사
        $rules = [
            'title' => ['required'],
            'content' => ['required'],
            'files' => ['array'],
            // 'files.*' => ['mimes:hwp,xls,zip', 'max:30000'],
            'files.*' => ['max:30000'],
        ];

        $messages = [
            'title.required' => '제목은 필수 입력 항목입니다.'
        ];

        // $validator = \Validator::make($request->all(), $rules, $messages);
        // if ($validtor->fails()) {
        //     return back()->withErrors($validator)->withInput();
        // }

        $this->validate($request, $rules, $messages);

        $payload = array_merge($request->all(), [
          'hit' => $request->input('hit', 0),
        ]);

        // $notice = \App\User::find(1)->articles()->create($request->all());
        $notice = $request->user()->notices()->create($payload);

        // 파일 저장
        Log::debug($request->hasFile('files'));

        if ($request->hasFile('files')) {
           $files = $request->file('files');

           foreach($files as $file) {
              Log::debug($file->getClientOriginalName());
              $filename = Str::random().filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);
              $filesize = $file->getSize();
              $file->move(attachments_path(), $filename);

              $notice->attachments()->create([
                  'stored_name' => $filename,
                  'original_name' => $file->getClientOriginalName(),
                  'bytes' => $filesize, //$file->getSize(),
                  'mime' => $file->getClientMimeType()
              ]);
           }
        }

        flash()->success('공지사항이 등록하였습니다.');

        return redirect(route('notice.index', $notice->id));
    }

    public function show($id)
    {
        $notice = \App\Notice::findOrFail($id);
        // get previous user id
        // $previous = \App\Notice::where('id', '<', $id)->max('id');
        $previous = \App\Notice::where('id', '<', $id)->orderBy('id', 'desc')->first();
        // get next user id
        // $next = \App\Notice::where('id', '>', $id)->min('id');
        $next = \App\Notice::where('id', '>', $id)->first();

        $notice->update(['hit' => $notice->hit + 1]);

        return view('notice.show', compact('notice', 'previous', 'next'));
    }

    public function edit($id)
    {
        $notice = \App\Notice::findOrFail($id);
        $this->authorize('notice-update', $notice);

        return view('notice.edit', compact('notice'));
    }

    public function update(Request $request, $id)
    {
        $notice = \App\Notice::findOrFail($id);
        $notice->update($request->all());

        Log::debug($request->hasFile('files'));

        if ($request->hasFile('files')) {
           $files = $request->file('files');

           foreach($files as $file) {
              Log::debug($file->getClientOriginalName());
              $filename = Str::random().filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);
              Log::debug($filename);
              $file->move(attachments_path(), $filename);

              $notice->attachments()->create([
                  'stored_name' => $filename,
                  'original_name' => $file->getClientOriginalName(),
                  'bytes' => $file->getSize(),
                  'mime' => $file->getClientMimeType()
              ]);
           }
        }

        flash()->success('수정하신 내용을 저장했습니다.');

        return redirect(route('notice.show', $notice->id));
    }

    public function destroy($id)
    {
        $notice = \App\Notice::findOrFail($id);

        $this->authorize('notice-delete', $notice);

        DB::beginTransaction();
        try
        {
            $attachments = $notice->attachments;
            Log::debug($attachments);
            foreach($attachments as $attachment) {
                $attachment->delete();
            }

            $notice->delete();
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

        // $this->authorize('notice-delete', $notice);
        $attachment->delete();

        return response()->json([
          'success' => '첨부파일이 삭제되었습니다.'
        ], 204);
    }
}
