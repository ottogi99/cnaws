<!-- topbar starts -->
<div class="navbar navbar-default" role="navigation">
  <div class="navbar-inner">
    <a class="navbar-brand" href="{{ route('home.index') }}" style="width:210px; height:90px; background-color:#188859;">
    <img src="/img/logo.png"/ style="width:96px; height:68px;"></a>

    <span style="color:#ffffff; font-family:'ngb'; font-size:30px; margin-left:100px; line-height:80px;">@yield('title')</span>
    <!-- user dropdown starts -->
    <div class="btn-group pull-right">
      <a onclick="logout()" style="color:#555555; text-decoration:none;">
        <button class="btn btn-default" style="width:85px;">
          <span class="hidden-sm hidden-xs">로그아웃</span>
        </button>
      </a>
    </div>

    <div class="btn-group pull-right">
      <button id="adminname" class="btn btn-default btn-setting" onClick="location.href='{{ route("users.edit", auth()->user()->id) }}'" style="background-color:#323333; border:1px solid #ffffff; background-image:none;">
        <a href="#" class="" style="text-decoration:none;">
          <i class="glyphicon glyphicon-user" style="color:#ffffff;"></i>
          <span class="hidden-sm hidden-xs" style="color:#ffffff;">{{ auth()->user()->name }}</span>
        </a>
      </button>
    </div>
  </div>
</div>

<div style="width:210px; height:40px; background-color:#085e38; margin-top:-20px; position:relative;">
	<span style="font-family:'ng'; color:#ffffff; line-height:38px; padding-left:15px;">{{ config('app.name', '충남농작업지원단 업무시스템') }}</span>
</div>
<!-- topbar ends -->
