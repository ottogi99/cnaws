<?php

function attachments_path($path = '')
{
    return public_path('files'.($path ? DIRECTORY_SEPARATOR.$path : $path));
}


function str_insert_pattern($str, $len, $pattern)
{
    $preg = "/[^ \n<>]{".$len."}/i";    // 대소문자 구분안함 : "/i"
    $pattern = "\\0".$pattern."\n";
    return preg_replace($preg, $pattern, $str);
}

function options_for_farmers($farmers, $farmer_id, $except_all=false)
{
    $html = '';
    if (!$except_all)
      $html = '<option value="">전체</option>';

    foreach($farmers as $farmer) {
        if ($farmer->id == $farmer_id) {
            $selected = 'selected = "selected"';
        } else {
            $selected = '';
        }
        $html = $html.'<option value="'.$farmer->id.'" '.$selected.'>'.$farmer->name.'</option>';
    }

    return $html;
}

function options_for_supporters($supporters, $supporter_id, $except_all=false)
{
    $html = '';
    if (!$except_all)
      $html = '<option value="">전체</option>';

    foreach($supporters as $supporter) {
        if ($supporter->id == $supporter_id) {
            $selected = 'selected = "selected"';
        } else {
            $selected = '';
        }
        $html = $html.'<option value="'.$supporter->id.'" '.$selected.'>'.$supporter->name.'</option>';
    }

    return $html;
}

function options_for_sigun($siguns, $sigun_code, $except_all=false)
{
    $html = '';
    if (!$except_all)
      $html = '<option value="">전체</option>';

    foreach($siguns as $sigun) {
        if ($sigun->code == $sigun_code) {
            $selected = 'selected = "selected"';
        } else {
            $selected = '';
        }
        $html = $html.'<option value="'.$sigun->code.'" '.$selected.'>'.$sigun->name.'</option>';
    }

    return $html;
}

function options_for_nonghyup($nonghyups, $nonghyup_id, $isAdmin, $except_all=false)
{
    $html = '';

    if ($isAdmin) {
      if (!$except_all)
        $html = '<option value="">전체</option>';

      foreach($nonghyups as $nonghyup) {
          if ($nonghyup->nonghyup_id == $nonghyup_id) {
              $selected = 'selected = "selected"';
          } else {
              $selected = '';
          }
          $html = $html.'<option value="'.$nonghyup->nonghyup_id.'" '.$selected.'>'.$nonghyup->name.'</option>';
      }
    } else {
        $html = $html.'<option value="'.$user->nonghyup_id.'">'.$user->name.'</option>';
    }

    return $html;
}

function options_for_year($selected_year)
{
    $html = '';
    $selected_year = ($selected_year) ? $selected_year : now()->year;

    for($year = 2019; $year <= now()->year; $year++) {
        if ($year == $selected_year) {
            $selected = 'selected = "selected"';
        } else {
            $selected = '';
        }

        $html = $html.'<option value="'.$year.'" '.$selected.'>'.$year.'</option>';
    }

    return $html;
}


function link_for_sort($column, $text, $params = [])
{
    $direction = request()->input('order');
    $reverse = ($direction == 'asc') ? 'desc' : 'asc';

    // 정렬방식이 동일한 경우, 오름차순/내림차순 토글
    if (request()->input('sort') == $column) {
        $text = sprintf("%s %s",
            $direction == 'asc'
                ? '<i class="fa fa-sort-alpha-asc"></i>'
                : '<i class="fa fa-sort-alpha-desc"></i>',
            $text
        );
    }

    // http_build_query(array $query_data) PHP 함수는 인자로 받은 연관 배열을 쿼리 스트링으로 바꾼다.
    // ['foo'=>'bar', 'baz'=>'qux'] 를 넣을 경우, foo=bar&baz=qux
    $queryString = http_build_query(array_merge(
        request()->except(['sort', 'order']),
        ['sort' => $column, 'order' => $reverse],
        $params
    ));

    // 기존의 쿼리 스트링을 버리고 URL 경로까지만 사용한다.
    return sprintf(
        '<a href="%?%s">%s</a>',
        urldecode(request()->url()),
        $queryString,
        $text
    );
}
// 첫번째 인자는 정렬 기준이 되는 테이블의 열 이름이다. -->
// 두번째 인자는 링크 텍스트로 표시할 문자열이다. -->
// 세번째 인자는 링크 태그에 더 추가할 속성값이다. -->
