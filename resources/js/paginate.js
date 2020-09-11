function paging(total, perPage, groupSize, currentPage, token) {
	countTotal = parseInt(total);							//전체 레코드수
	countPerPage = parseInt(perPage);					//페이지당 보여줄 데이터수
	groupSize = parseInt(groupSize);				//페이지 그룹 범위 (1 2 3 4 5 6 7 8 9 10)
	currentPage = parseInt(currentPage);	//현재 페이지

	var html = new Array();
	if (total == 0) {
		return "";
	}

	// 페이지 카운트
	var countPage = countTotal % countPerPage;						// 페이지수 = 전체항목수 / 페이지당 항목수(또는 페이자당 항목수 + 1)
	if (countPage == 0) {
		countPage = parseInt(countTotal / countPerPage);
	} else {
		countPage = parseInt(countTotal / countPerPage) + 1;
	}

	var countPrevGroup = parseInt(currentPage / groupSize);					// 현제페이지 번호 / 페이지 그룹 범위   pRCnt = 5 / 5, pRCnt = 1, pRCnt = 6 / 5, pRCnt = 1, pRCnt = 10 / 5, pRCnt = 2
	if (currentPage % groupSize == 0) {										// 5 / 5 == true, 			6 / 5 == false,					10 / 5 == true
		countPrevGroup = parseInt(currentPage / groupSize) - 1;				// pRCnt = (5 / 5) - 1, pRCnt = 0								pRCnt = 1
	}																											// 1, 2, 3, 4, 5 까지는 pRCnt = 0이고 6, 7, 8, 9, 10 까지는 pRCnt = 1이다

	html.push('<ul class="pagination">');
	//이전 화살표 : 현재페이지 번호가 페이지 그룹 범위를 넘어선 경우 (currentPage:6, pageSize:5)
	if (currentPage > groupSize) {
		var s2;
		if (currentPage % groupSize == 0) {
			s2 = currentPage - groupSize;										// s2 = 10 - 5, s2 = 5;
		} else {
			s2 = currentPage - (currentPage % groupSize);		// s2 = 6 - (6 % 5), s2 = 5;  s2 = 7 - (7 % 5), s2 = 5
		}
		html.push("<li><a href='#' onclick='getAddr(");
		html.push(s2);
		html.push(");'>");
		html.push('◀');
		html.push('</a></li>');
	} else {
		html.push('<li class="page-item disabled">');
		html.push('<span class="page-link"><</span>');
		html.push('</li>');
	}

	//페이지 바
	for(var index = countPrevGroup * groupSize + 1; index < (countPrevGroup + 1) * groupSize + 1; index++) {
		if (index == currentPage) {
			html.push('<li class="page-item active"><span class="page-link">');
			html.push(index);
			html.push('</span></li>');
		} else {
			html.push("<li class='page-item'><a class='page-link' href='#' onclick='getAddr(");
			html.push(index);
			html.push(");'>");
			html.push(index);
			html.push('</a></li>');
		}

		// if (index == countPage) {
		// 	break;
		// } else {
		// 	html.push('|');
		// }
		console.log(index);
	}

	//다음 화살표
	if (countPage > (countPrevGroup + 1) * groupSize) {
		html.push("<li class='page-item'><a class='page-link' href='#' onclick='getAddr(");
		html.push((countPrevGroup + 1) * groupSize + 1);
		html.push(");'>");
		html.push('>');
		html.push('</a></li>');
	} else {
		// html.push('<a href="#">\n');
		// html.push('▶');
		// html.push('</a>');
	}
	html.push('</ul>');

	return html.join("");
}
