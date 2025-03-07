const SortSales = document.querySelector('.sort_nav01'); //판매량순 버튼
const SortHighPrice = document.querySelector('.sort_nav02'); // 높은가격순 버튼
const SortLowPrice = document.querySelector('.sort_nav03'); //낮은가격순 버튼
const SortStart = document.querySelector('.sort_nav04'); //평점순 버튼

//판매량순으로 정렬
SortSales.addEventListener('click', function () {
	SortHighPrice.style.backgroundColor = ' rgb(212, 212, 212)';
	SortSales.style.backgroundColor = 'rgb(255, 198, 42)';
	SortLowPrice.style.backgroundColor = ' rgb(212, 212, 212)';
	SortStart.style.backgroundColor = ' rgb(212, 212, 212)';
});
//높은가격순으로 정렬
SortHighPrice.addEventListener('click', function () {
	SortHighPrice.style.backgroundColor = 'rgb(255, 198, 42)';
	SortSales.style.backgroundColor = ' rgb(212, 212, 212)';
	SortLowPrice.style.backgroundColor = ' rgb(212, 212, 212)';
	SortStart.style.backgroundColor = ' rgb(212, 212, 212)';
});
//낮은가격순으로 정렬
SortLowPrice.addEventListener('click', function () {
	SortHighPrice.style.backgroundColor = ' rgb(212, 212, 212)';
	SortSales.style.backgroundColor = ' rgb(212, 212, 212)';
	SortLowPrice.style.backgroundColor = 'rgb(255, 198, 42)';
	SortStart.style.backgroundColor = ' rgb(212, 212, 212)';
});
//평점순으로 정렬
SortStart.addEventListener('click', function () {
	SortHighPrice.style.backgroundColor = ' rgb(212, 212, 212)';
	SortSales.style.backgroundColor = ' rgb(212, 212, 212)';
	SortLowPrice.style.backgroundColor = ' rgb(212, 212, 212)';
	SortStart.style.backgroundColor = 'rgb(255, 198, 42)';
});
