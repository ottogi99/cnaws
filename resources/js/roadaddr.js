function getAddr(pageNo){
	// 적용예 (api 호출 전에 검색어 체크)
	if (!checkSearchedWord(document.form.keyword)) {
		return ;
	}

	if (pageNo > 1) {
		document.getElementsByName('currentPage')[0].value = pageNo;
	} else {
		document.getElementsByName('currentPage')[0].value = 1;
	}

	$.ajax({
		 url :"http://www.juso.go.kr/addrlink/addrLinkApiJsonp.do"  //인터넷망
		,type:"post"
		,data:$("#form").serialize()
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
		,error: function(xhr, status, error){
			console.log(status);
			console.log(error);
			alert("에러발생");
		}
	});
}

function setMapping(idx) {
	var roadAddr = $("#roadAddrDiv"+idx).text();
	// console.log(roadAddr);
	// var setDataOpener(roadAddr);
	opener.document.getElementById('address').value = roadAddr;

	self.close();
}

function makeListJson(jsonStr){
	var num = 0;
	var htmlStr = "";
	htmlStr += "<p>도로명주소 검색 결과(" + jsonStr.results.common.totalCount +")";
	htmlStr += "<table>";
	htmlStr += "<tr><th>도로명주소</th><th>우편번호</th>";
	$(jsonStr.results.juso).each(function(){
		num++;

		htmlStr += "<tr>";
		htmlStr += "<td>";
		htmlStr += "<a href='#' onclick='setMapping(" + num + ")'>";
		htmlStr += "<div id='roadAddrDiv" + num + "'><b>"+this.roadAddr+"</b></div>";
		htmlStr += "<span style='font-size:11px;'>[지번]"+this.jibunAddr+"</span>";
		htmlStr += "</a>";
		htmlStr += "</td>";
		// htmlStr += "<td>"+this.roadAddrPart1+"</td>";
		// htmlStr += "<td>"+this.roadAddrPart2+"</td>";
		// htmlStr += "<td>"+this.jibunAddr+"</td>";
		// htmlStr += "<td>"+this.engAddr+"</td>";
		htmlStr += "<td>"+this.zipNo+"</td>";
		// htmlStr += "<td>"+this.admCd+"</td>";
		// htmlStr += "<td>"+this.rnMgtSn+"</td>";
		// htmlStr += "<td>"+this.bdMgtSn+"</td>";
		// htmlStr += "<td>"+this.detBdNmList+"</td>";
		/** API 서비스 제공항목 확대 (2017.02) **/
		// htmlStr += "<td>"+this.bdNm+"</td>";
		// htmlStr += "<td>"+this.bdKdcd+"</td>";
		// htmlStr += "<td>"+this.siNm+"</td>";
		// htmlStr += "<td>"+this.sggNm+"</td>";
		// htmlStr += "<td>"+this.emdNm+"</td>";
		// htmlStr += "<td>"+this.liNm+"</td>";
		// htmlStr += "<td>"+this.rn+"</td>";
		// htmlStr += "<td>"+this.udrtYn+"</td>";
		// htmlStr += "<td>"+this.buldMnnm+"</td>";
		// htmlStr += "<td>"+this.buldSlno+"</td>";
		// htmlStr += "<td>"+this.mtYn+"</td>";
		// htmlStr += "<td>"+this.lnbrMnnm+"</td>";
		// htmlStr += "<td>"+this.lnbrSlno+"</td>";
		// htmlStr += "<td>"+this.emdNo+"</td>";
		htmlStr += "</tr>";
	});
	htmlStr += "</table>";
	$("#list").html(htmlStr);

	//Paging(전체데이타수, 페이지당 보여줄 데이타수, 페이지 그룹 범위, 현재페이지 번호, token명)
	var totalCount = jsonStr.results.common.totalCount;
	var perPage = jsonStr.results.common.countPerPage;
	var groupRange = 5;
	var currentPage = jsonStr.results.common.currentPage
	// var page_viewList = Paging(27, 10, 10 ,1, "PagingView");
	var page_viewList = paging(totalCount, perPage, groupRange, currentPage, "");
	// $("#paginate").html(page_viewList);
	$(".bot_pagination").html(page_viewList);
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
		getAddr();
	}
}

var addrWindow;

function openAddrPopup(){
  addrWindow = window.open("/apis/popup", "도로명주소 검색", "height=700,width=500,resizable=yes");
}

// 농가검색
function openSearchPopup(type){
	var nid = $('#nonghyup_id').val();
	addrWindow = window.open("/apis/searchPopup?type="+type+"&nid="+nid, "검색", "height=700,width=500,resizable=yes");
}

function enterSearchKeyword(type) {
	var evt_code = (window.netscape) ? ev.which : event.keyCode;
	if (evt_code == 13) {
		event.keyCode = 0;

		console.log(type);
		getSearchResult(type, 1);
		// if (type == 'small') {
		// 	getSearchResult(type);
		// } else if (type == 'large') {
		// 	getSearchResult(type);
		// } else if (type == 'machine') {
		// 	getSearchResult(type);
		// } else if (type == 'manpower') {
		// 	getSearchResult(type);
		// }
	}
}

function getSearchResult(type, pageNo){
	// 적용예 (api 호출 전에 검색어 체크)
	if (!checkSearchedWord(document.form.keyword)) {
		return;
	}

	if (pageNo > 1) {
		document.getElementsByName('currentPage')[0].value = pageNo;
	} else {
		document.getElementsByName('currentPage')[0].value = 1;
	}
	$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')   // X-CSRF-TOKEN HTTP 요청 헤더
			}
	});

	var url = '';
	if (type == 'small')
		url = "/api/search/small_farmers";
	else if (type == 'large')
		url = "/api/search/large_farmers";
	else if (type == 'machine')
		url = "/api/search/machine_supporters";
	else if (type == 'manpower')
		url = "/api/search/manpower_supporters";

	$.ajax({
		 // url :"http://www.juso.go.kr/addrlink/addrLinkApiJsonp.do"  //인터넷망
		 // url :"{{ route('api.searchSmallFarmers') }}"  //인터넷망
		 // url: "/api/search/small_farmers"
		 url: url
		,type:"post"
		,data:$("#form").serialize()
		,dataType:"json"
		// ,crossDomain:true
		,success: function(jsonStr){
			$("#list").html("");
			$(".bot_pagination").html("");
			makeListJsonSearch(type, jsonStr);
		}
    ,error: function(xhr, status, error){
			console.log(status + ' : ' + error);
			alert("에러발생");
    }
	});
}

function setMappingFarmer(type, idx) {
	var name = $("#name"+idx).text();
	var id = $("#name"+idx).data('id');//text();
	var address = $("#address"+idx).text();
	// var setDataOpener(roadAddr);

	if (type == 'small' || type == 'large') {
		opener.document.getElementById('farmer_name').value = name;
		opener.document.getElementById('farmer_id').value = id;
		opener.document.getElementById('address').value = address;
	}
	else {
		opener.document.getElementById('supporter_name').value = name;
		opener.document.getElementById('supporter_id').value = id;
	}

	self.close();
}

function makeListJsonSearch(type, jsonStr){
	var num = 0;
	var htmlStr = "";
	htmlStr += "<p>검색 결과(" + jsonStr.results.total +")";
	htmlStr += "<table>";
	htmlStr += "<tr><th style='width:80px;'>농가명</th><th style='width:50px;'>성별</th><th style='width:50px;'>나이</th><th>연락처</th><th style='width:200px;'>주소</th>";
	$(jsonStr.results.data).each(function(){
		num++;

		htmlStr += "<tr>";
		htmlStr += "<td >";
		htmlStr += '<a href="#" onclick="setMappingFarmer(\'' + type + '\',' + num + ')">';
		htmlStr += "<div id='name" + num + "' data-id='"+this.id+"' ><b>"+this.name+"</b></a></div>";
		htmlStr += "</td>";
		if (this.sex == 'M')
			htmlStr += "<td>남</td>";
		else
			htmlStr += "<td>여</td>";

		htmlStr += "<td>"+this.age+"</td>";
		htmlStr += "<td>"+this.contact+"</td>";
		if (this.address == "")
			htmlStr += "<td><div id='address" + num + "'></div></td>";
		else
			htmlStr += "<td><div id='address" + num + "'>"+this.address+"</div></td>";

		// htmlStr += "<td><div id='id" + num + "' style='visibility:hidden'>"+this.id+"</div></td>";
		htmlStr += "</tr>";
	});
	htmlStr += "</table>";
	$("#list").html(htmlStr);

	//Paging(전체데이타수, 페이지당 보여줄 데이타수, 페이지 그룹 범위, 현재페이지 번호, token명)
	var totalCount = jsonStr.results.total;
	var perPage = jsonStr.results.per_page;
	var groupRange = 5;
	var currentPage = jsonStr.results.current_page;
	// var page_viewList = Paging(27, 10, 10 ,1, "PagingView");

	console.log(totalCount, perPage, groupRange, currentPage);
	var page_viewList = pagingSmallFarmer(totalCount, perPage, groupRange, currentPage, type);
	// $("#paginate").html(page_viewList);
	$(".bot_pagination").html(page_viewList);
}
