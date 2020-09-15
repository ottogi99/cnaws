<!-- left menu starts -->
<div class="sidebar-nav" style="width:210px; padding-bottom:100%;">
  <div class="nav-canvas">
    <div class="nav-sm nav nav-stacked">
    </div>
    <ul class="nav nav-pills nav-stacked main-menu">
      <li class="nav-header" style="height:30px;"></li>
      @if (auth()->user()->isAdmin())
      <li class="accordion">
        <a class="ajax-link" href="#">
          <img src="/img/icon-1.png"/ style="margin:0px 10px 0px 10px;">
          <span>사용자(농협)정보</span>
        </a>
        <ul class="nav nav-pills nav-stacked">
          <li><a href="{{ route('users.index') }}">사용자(농협)</a></li>
          <li><a href="{{ route('schedules.show') }}">입력관리</a></li>
          <li><a href="{{ route('user_histories.index') }}">이력조회</a></li>
        </ul>
      </li>
      @endif
      <li class="accordion"><a class="ajax-link" href="#">
        <img src="/img/icon-2.png"/ style="margin:0px 10px 0px 10px;">
        <span>사업관리</span></a>
        <ul class="nav nav-pills nav-stacked">
          <li><a href="{{ route('budgets.index') }}">사업비</a></li>
        </ul>
      </li>
      <li class="accordion"><a class="ajax-link" href="#">
        <img src="/img/icon-3.png"/ style="margin:0px 10px 0px 10px;">
        <span>모집현황</span></a>
        <ul class="nav nav-pills nav-stacked">
          <li><a href="{{ route('small_farmers.index') }}">소규모·영세소농</a></li>
          <li><a href="{{ route('large_farmers.index') }}">대규모·전업농가</a></li>
          <li><a href="{{ route('machine_supporters.index') }}">농기계지원반</a></li>
          <li><a href="{{ route('manpower_supporters.index') }}">인력지원반</a></li>
        </ul>
      </li>
      <li class="accordion"><a class="ajax-link" href="#">
        <img src="/img/icon-4.png"/ style="margin:0px 10px 0px 10px;">
        <span>지원현황</span></a>
        <ul class="nav nav-pills nav-stacked">
          <li><a href="{{ route('status_education_promotions.index') }}">교육·홍보비</a></li>
          <li><a href="{{ route('status_machine_supporters.index') }}">소규모·영세소농</a></li>
          <li><a href="{{ route('status_manpower_supporters.index') }}">대규모·전업농가</a></li>
          <li><a href="{{ route('status_labor_payments.index') }}">센터운영비(인건비)</a></li>
          <li><a href="{{ route('status_operating_costs.index') }}">센터운영비(운영비)</a></li>
        </ul>
      </li>
      <li class="accordion"><a class="ajax-link" href="#">
        <img src="/img/icon-5.png"/ style="margin:0px 10px 0px 10px;">
        <span>사업현황</span></a>
        <ul class="nav nav-pills nav-stacked">
          <li><a href="{{ route('performance_operating.index') }}">운영실적</a></li>
          <li><a href="{{ route('performance_executive.index') }}">집행실적</a></li>
        </ul>
      </li>
      <li class="accordion"><a class="ajax-link" href="#">
        <img src="/img/icon-6.png"/ style="margin:0px 10px 0px 10px;">
        <span>업무포털</span></a>
        <ul class="nav nav-pills nav-stacked">
          <li><a href="6task.html">공지사항</a></li>
          <li><a href="6task-2.html">사용자매뉴얼</a></li>
          <li><a href="6task-3.html">건의사항</a></li>
        </ul>
      </li>

      <div class="box col-md-12">
        <!-- <div class="box-inner">
          <div class="box-header well">
            <h2 style="color:#ffffff;"><i class="glyphicon glyphicon-bullhorn"></i> 알림</h2>

            <div class="box-icon">
            <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
            </div>
          </div>
          <div class="box-content alerts">
            <div class="alert alert-danger">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>오류</strong><br> 성공 37 오류 3
            </div>
            <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>성공</strong><br> 성공 40 오류 0
            </div>
            <div class="alert alert-info">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>로그인</strong><br> 로그인에 성공하였습니다.
            </div>
          </div>
        </div> -->
      </div>
  </div>
</div>
