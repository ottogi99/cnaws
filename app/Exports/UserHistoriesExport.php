<?php

namespace App\Exports;

use App\UserHistory;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Support\Facades\DB;

class UserHistoriesExport implements FromQuery, WithMapping, WithColumnFormatting, WithHeadings, ShouldAutoSize
{
  use Exportable;

  public function query()
  {
      $item = $this->item;
      $keyword = $this->keyword;

      if ($item) {
          $query = \App\UserHistory::when($keyword, function($query, $keyword) {
                                            return $query->whereRaw($item.' like \'%'.$keyword.'%\'');
                                          })
                                          ->orderby('created_at', 'DESC');
      } else {
          $query = \App\UserHistory::orderby('created_at', 'DESC');
      }

      return $query;
  }

  public function map($history): array
  {
      return [
          [
              $history->worker_id,
              $history->target_id,
              $history->contents,
              Date::dateTimeToExcel($history->created_at),
          ],
      ];
  }

  public function columnFormats(): array
  {
      return [
          'D' => NumberFormat::FORMAT_DATE_YYYYMMDD,
      ];
  }

  public function headings(): array
  {
      return [
          '작업자ID', '대상ID', '작업내용', '등록일자',
      ];
  }

  public function forItem($item)
  {
      $this->item = $item;
      return $this;
  }
  // 검색어로 조회
  public function forKeyword($keyword)
  {
      $this->keyword = $keyword;
      return $this;
  }
}
