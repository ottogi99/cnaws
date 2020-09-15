<div class="box col-md-12">
  <div class="btn-group pull-left">
  </div>
</div>
<div class="box col-md-6">
  <div class="box-inner" style="background-color:#ffffff; padding-bottom:50px;">
    <div class="box-header well" data-original-title=""
      style="background:none; height:70px; line-height:60px; font-size:23px;">
      <span>데이터 입력관리</span>
    </div>

    <form class="box-content" style="padding-bottom:50px;">
      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:140px; font-size:13px;">데이터입력</span>
        <span class="input-group-addon" style="width:41px; font-size:13px;">허용</span>
        <input type="radio" name="is_allow" id="is_allow1" value="1" class="form-control" style="width:18px; box-shadow:none;" {{ ($schedule->is_allow) ? 'checked' : '' }} >
        <span class="input-group-addon" style="width:41px; font-size:13px;">불가</span>
        <input type="radio" name="is_allow" id="is_allow2" value="0" class="form-control" style="width:18px; box-shadow:none;" {{ !($schedule->is_allow) ? 'checked' : '' }}>
      </div>

      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">입력기간설정</span>
        <input type="checkbox" name="is_period" id="is_period" value="1" class="form-control" style="width:18px; box-shadow:none; margin-top:0;"
          {{ ($schedule->is_period) ? 'checked' : '' }}>
      </div>

      <div class="input-group input-group-lg" style="padding-bottom:10px;">
        <span class="input-group-addon" style="width:150px; font-size:13px;">기간</span>
        <input type="text" name="input_start_date" id="input_start_date" class="form-control datePicker" style="width:165px; height:25px;  margin-top:11px;"
        value="{{ old('input_start_date', ($schedule->input_start_date) ? $schedule->input_start_date->format('Y-m-d') : now()->format('Y-m-d')) }}">
        <span class="input-group-addon" style="width:20px; font-size:13px;">~</span>
        <input type="text" name="input_end_date" id="input_end_date" class="form-control datePicker" style="width:165px; height:25px;"
        value="{{ old('input_end_date', ($schedule->input_end_date) ? $schedule->input_end_date->format('Y-m-d') : now()->format('Y-m-d')) }}">
      </div>

      <div class="input-group pull-right" style="margin-right:10px;">
        <button type="submit" class="btn btn-sm btn-primary" >수정</button>
      </div>
    </form>
  </div>
</div>
