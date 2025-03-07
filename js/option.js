let ContentPrice = document.querySelector('.price_final'); //=상품금액
let TotalPrice = document.querySelector('.total_price'); //=총금액
ContentPrice.innerHTML = ContentPrice.innerHTML.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ','); //콤마찍기
ContentPrice = ContentPrice.innerHTML.replace(',', ''); //콤마제거

let Price = 0; //상품금액*상품수량 계산값
let Option3Price = document.querySelector('.option3_price'); //=상품금액*상품수량

function SelectOption() {
	let CheckedColor = $('input[name=color]:checked').val(); //체크되어 있는 color 라디오값
	let CheckedSize = $('input[name=size]:checked').val(); //체크되어 있는 size 라디오값
	let Option1Color = document.querySelector('.option1_color');
	let Option1Size = document.querySelector('.option1_size');
	let Option2Amount = $('input[name=option2_amount]'); //=상품수량

	//색상옵션과 사이즈옵션 모두 선택되어졌다면
	if ($('input:radio[name=color]').is(':checked') == true && $('input:radio[name=size]').is(':checked') == true) {
		Option1Color.innerHTML = CheckedColor; //option을 선택된 radio값으로 바꾸기
		Option1Size.innerHTML = CheckedSize; //option을 선택된 radio값으로 바꾸기
		Price = Number(ContentPrice) * Number(Option2Amount.val());
		Price = Price.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',');
		Option3Price.innerHTML = Price;
		TotalPrice.innerHTML = Price;
		document.querySelector('.content').style.display = 'flex';
		$('input:hidden[name=content_options]').attr('value', CheckedColor + ' / ' + CheckedSize);
	}
}

// 수량이 변경되면, 금액 다시 계산
function AmountChange() {
	let Option2Amount = $('input[name=option2_amount]'); //=상품수량
	Price = Number(ContentPrice) * Number(Option2Amount.val());
	Price = Price.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',');
	Option3Price.innerHTML = Price;
	TotalPrice.innerHTML = Price;
}

//X 선택하면 상품옵션 선택 없어짐
const deleteBtn = document.querySelector('.delete');

deleteBtn.addEventListener('click', function () {
	document.querySelector('.content').style.display = 'none';
	$('input:radio[name=color]').prop('checked', false);
	$('input:radio[name=size]').prop('checked', false);
});
