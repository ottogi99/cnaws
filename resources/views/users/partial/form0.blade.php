@if ($viewName === 'users.create')
    <div class="form-group" {{ auth()->user()->isAdmin() ? '' : 'style=display:none' }}>
      <label for="sigun_code">시군명</label>
      <select class="form-control" name="sigun_code" id="sigun_code">
        {!! options_for_sigun($siguns, request()->input('sigun_code'), true) !!}
      </select>
    </div>

    <div class="form-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
        <label for="content">농협ID</label>
        <input type="text" name="nonghyup_id" id="nonghyup_id" placeholder="사용자(농협) ID"
                value="{{ old('nonghyup_id', $nonghyup->nonghyup_id) }}" class="form-control" {{ ($nonghyup->nonghyup_id) ? 'readonly' : '' }}/>
        {!! $errors->first('nonghyup_id', '<span class="form-error">:message</span>') !!}
    </div>
@endif

@if ($viewName === 'users.edit')
  <div class="form-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
      <label for="content">농협ID</label>
      <input type="text" name="nonghyup_id" id="nonghyup_id" placeholder="사용자(농협) ID"
              value="{{ old('nonghyup_id', $nonghyup->nonghyup_id) }}" class="form-control" {{ ($nonghyup->nonghyup_id) ? 'readonly' : '' }}/>
      {!! $errors->first('nonghyup_id', '<span class="form-error">:message</span>') !!}
  </div>
  @if (auth()->user()->isAdmin())
    <div class="form-group" {{ auth()->user()->isAdmin() ? '' : 'style=display:none' }}>
      <label for="sigun_code">시군명</label>
      <select class="form-control" name="sigun_code" id="sigun_code">
        {!! options_for_sigun($siguns, request()->input('sigun_code'), true) !!}
      </select>
    </div>
    {!! $errors->first('sigun_code', '<span class="form-error">:message</span>') !!}
  @else
    <div class="form-group {{ $errors->has('sigun_code') ? 'has-error' : '' }}">
        <input type="hidden" name="sigun_code" id="sigun_code" value="{{ old('sigun_code', $nonghyup->sigun->code) }}" class="form-control" readonly/>
    </div>
    <div class="form-group {{ $errors->has('nonghyup_id') ? 'has-error' : '' }}">
        <input type="hidden" name="nonghyup_id" id="nonghyup_id" value="{{ old('nonghyup_id', $nonghyup->nonghyup_id) }}" class="form-control" readonly/>
    </div>
  @endif
@endif

<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="content">농협명</label>
    <input type="text" name="name" id="name" value="{{ old('name', $nonghyup->name) }}" class="form-control" />
    {!! $errors->first('name', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
    <label for="content">비밀번호</label>
    <input type="password" name="password" id="password" placeholder="비밀번호" value="" class="form-control" />
    {!! $errors->first('password', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
    <label for="password_confirmation">비밀번호 확인</label>
    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="비밀번호 확인" value="" class="form-control" />
    {!! $errors->first('password_confirmation', '<span class="form-error">:message</span>') !!}
</div>

권한:
<div class="form-group">
    <input type="radio" id="general" name="is_admin" value="0" {{ ($nonghyup->is_admin) ? '' : 'checked' }}>
    <label for="general">일반(농협)</label><br>
    <input type="radio" id="admin" name="is_admin" value="1" {{ ($nonghyup->is_admin) ? 'checked' : '' }}>
    <label for="admin">관리자</label><br>
</div>
{!! $errors->first('is_admin', '<span class="form-error">:is_admin</span>') !!}

<!-- 테스트 끝나고 user.edit 밑으로 집어넣어라 -->
<div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
    <label for="content">도로명 주소</label>
    <input type="text" name="address" id="address" value="{{ old('address', $nonghyup->address) }}" class="form-control" />
    {!! $errors->first('address', '<span class="form-error">:message</span>') !!}
    <input type="button" value="주소검색" onclick="openAddrPopup();">
</div>

<div class='addr_list'>
</div>

@if ($viewName == 'users.edit')
<!-- 여기에 넣어라. -->
<div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
    <label for="content">연락처</label>
    <input type="text" name="contact" id="contact" value="{{ old('contact', $nonghyup->contact) }}" maxlength="11" class="form-control" numberOnly/>
    {!! $errors->first('contact', '<span class="form-error">:message</span>') !!}
</div>

<div class="form-group {{ $errors->has('representative') ? 'has-error' : '' }}">
    <label for="content">대표자</label>
    <input type="text" name="representative" id="representative" value="{{ old('representative', $nonghyup->representative) }}" class="form-control" />
    {!! $errors->first('representative', '<span class="form-error">:message</span>') !!}
</div>
@endif

@section('script')
  @parent
  <script type="text/javascript">
    window.onload = function() {
    		jusoCallBack('roadFullAddr');
    }

    function jusoCallBack(roadFullAddr){
      if (roadFullAddr != 'roadFullAddr')
    	 document.getElementById('address').value = roadFullAddr;
    }

    function goPopup(){
    	var pop = window.open("/apis/popup","pop","width=570,height=420, scrollbars=yes, resizable=yes");
    }

    function showAddr() {
      // $('#form_addr').show();
      $('#form_addr').toggle();
    }

    function getAddr(){
    	// 적용예 (api 호출 전에 검색어 체크)

      var keyword = $('#address').val();
      // document.form.keyword
    	if (!checkSearchedWord(document.getElementById('address'))) {
    		return ;
    	}

    	$.ajax({
    		 url :"http://www.juso.go.kr/addrlink/addrLinkApiJsonp.do"  //인터넷망
    		,type:"post"
    		,data:$("#form_addr").serialize()
    		,dataType:"jsonp"
    		,crossDomain:true
    		,success:function(jsonStr){
    			$("#list").html("");
    			var errCode = jsonStr.results.common.errorCode;
    			var errDesc = jsonStr.results.common.errorMessage;
    			if(errCode != "0"){
    				alert(errCode+"="+errDesc);
    			}else{
    				if(jsonStr != null){
    					makeListJson(jsonStr);
    				}
    			}
    		}
    	    ,error: function(xhr,status, error){
    	    	alert("에러발생");
    	    }
    	});
    }

    function makeListJson(jsonStr){
    	var htmlStr = "";
    	htmlStr += "<table>";
    	$(jsonStr.results.juso).each(function(){
    		htmlStr += "<tr>";
    		htmlStr += "<td>"+this.roadAddr+"</td>";
    		htmlStr += "<td>"+this.roadAddrPart1+"</td>";
    		htmlStr += "<td>"+this.roadAddrPart2+"</td>";
    		htmlStr += "<td>"+this.jibunAddr+"</td>";
    		htmlStr += "<td>"+this.engAddr+"</td>";
    		htmlStr += "<td>"+this.zipNo+"</td>";
    		htmlStr += "<td>"+this.admCd+"</td>";
    		htmlStr += "<td>"+this.rnMgtSn+"</td>";
    		htmlStr += "<td>"+this.bdMgtSn+"</td>";
    		htmlStr += "<td>"+this.detBdNmList+"</td>";
    		/** API 서비스 제공항목 확대 (2017.02) **/
    		htmlStr += "<td>"+this.bdNm+"</td>";
    		htmlStr += "<td>"+this.bdKdcd+"</td>";
    		htmlStr += "<td>"+this.siNm+"</td>";
    		htmlStr += "<td>"+this.sggNm+"</td>";
    		htmlStr += "<td>"+this.emdNm+"</td>";
    		htmlStr += "<td>"+this.liNm+"</td>";
    		htmlStr += "<td>"+this.rn+"</td>";
    		htmlStr += "<td>"+this.udrtYn+"</td>";
    		htmlStr += "<td>"+this.buldMnnm+"</td>";
    		htmlStr += "<td>"+this.buldSlno+"</td>";
    		htmlStr += "<td>"+this.mtYn+"</td>";
    		htmlStr += "<td>"+this.lnbrMnnm+"</td>";
    		htmlStr += "<td>"+this.lnbrSlno+"</td>";
    		htmlStr += "<td>"+this.emdNo+"</td>";
    		htmlStr += "</tr>";
    	});
    	htmlStr += "</table>";
    	$("#list").html(htmlStr);
    }

    //특수문자, 특정문자열(sql예약어의 앞뒤공백포함) 제거
    function checkSearchedWord(obj){
    	if(obj.value.length >0){
    		//특수문자 제거
    		var expText = /[%=><]/ ;
    		if(expText.test(obj.value) == true){
    			alert("특수문자를 입력 할수 없습니다.") ;
    			obj.value = obj.value.split(expText).join("");
    			return false;
    		}

    		//특정문자열(sql예약어의 앞뒤공백포함) 제거
    		var sqlArray = new Array(
    			//sql 예약어
    			"OR", "SELECT", "INSERT", "DELETE", "UPDATE", "CREATE", "DROP", "EXEC",
                 		 "UNION",  "FETCH", "DECLARE", "TRUNCATE"
    		);

    		var regex;
    		for(var i=0; i<sqlArray.length; i++){
    			regex = new RegExp( sqlArray[i] ,"gi") ;

    			if (regex.test(obj.value) ) {
    			    alert("\"" + sqlArray[i]+"\"와(과) 같은 특정문자로 검색할 수 없습니다.");
    				obj.value =obj.value.replace(regex, "");
    				return false;
    			}
    		}
    	}
    	return true ;
    }

    function enterSearch() {
    	var evt_code = (window.netscape) ? ev.which : event.keyCode;
    	if (evt_code == 13) {
    		event.keyCode = 0;
    		getAddr(); //jsonp사용시 enter검색
    	}
    }
  </script>
@stop
