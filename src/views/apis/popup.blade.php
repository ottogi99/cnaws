<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>

</head>
<script language="javascript">
// opener관련 오류가 발생하는 경우 아래 주석을 해지하고, 사용자의 도메인정보를 입력합니다. ("주소입력화면 소스"도 동일하게 적용시켜야 합니다.)
//document.domain = "abc.go.kr";

/*
		모의 해킹 테스트 시 팝업API를 호출하시면 IP가 차단 될 수 있습니다.
		주소팝업API를 제외하시고 테스트 하시기 바랍니다.
*/

function init(){
	var url = location.href;
	//var confmKey = "승인키";
	var confmKey = "devU01TX0FVVEgyMDIwMDcwNjIwMjkwMjEwOTkzMDE=";
	var resultType = "4"; // 도로명주소 검색결과 화면 출력내용, 1 : 도로명, 2 : 도로명+지번, 3 : 도로명+상세건물명, 4 : 도로명+지번+상세건물명
	var inputYn= "{{ $post_data['inputYn'] }}";
	if(inputYn != "Y"){
		document.form.confmKey.value = confmKey;
		document.form.returnUrl.value = url;
		document.form.resultType.value = resultType;
		document.form.action="http://www.juso.go.kr/addrlink/addrLinkUrl.do"; //인터넷망
		//document.form.action="http://www.juso.go.kr/addrlink/addrMobileLinkUrl.do"; //모바일 웹인 경우, 인터넷망
		document.form.submit();
	}else{
    opener.jusoCallBack(
      "{{ $post_data['roadFullAddr'] }}",
      "{{ $post_data['roadAddrPart1'] }}",
      "{{ $post_data['addrDetail'] }}",
      "{{ $post_data['roadAddrPart2'] }}",
      "{{ $post_data['engAddr'] }}",
      "{{ $post_data['jibunAddr'] }}",
      "{{ $post_data['zipNo'] }}",
      "{{ $post_data['admCd'] }}",
      "{{ $post_data['rnMgtSn'] }}",
      "{{ $post_data['bdMgtSn'] }}",
      "{{ $post_data['detBdNmList'] }}",
      "{{ $post_data['bdNm'] }}",
      "{{ $post_data['bdKdcd'] }}",
      "{{ $post_data['siNm'] }}",
      "{{ $post_data['sggNm'] }}",
      "{{ $post_data['emdNm'] }}",
      "{{ $post_data['liNm'] }}",
      "{{ $post_data['rn'] }}",
      "{{ $post_data['udrtYn'] }}",
      "{{ $post_data['buldMnnm'] }}",
      "{{ $post_data['buldSlno'] }}",
      "{{ $post_data['mtYn'] }}",
      "{{ $post_data['lnbrMnnm'] }}",
      "{{ $post_data['lnbrSlno'] }}",
      "{{ $post_data['emdNo'] }}"
    );
		window.close();
	}
}
</script>
<body onload="init();">
	<form id="form" name="form" method="post">
		<input type="hidden" id="confmKey" name="confmKey" value=""/>
		<input type="hidden" id="returnUrl" name="returnUrl" value=""/>
		<input type="hidden" id="resultType" name="resultType" value=""/>
    <input type="hidden" id="_token" name="_token" value=""/>
		<!-- 해당시스템의 인코딩타입이 EUC-KR일경우에만 추가 START-->
		<!--input type="hidden" id="encodingType" name="encodingType" value="EUC-KR"/-->
		<!-- 해당시스템의 인코딩타입이 EUC-KR일경우에만 추가 END-->
	</form>
</body>
</html>
