let ReviewCount = $('.look').length; //리뷰의 개수
if (ReviewCount > 0) {
	let count = 1; //각각의 리뷰에 고유번호를 주기 위한 변수
	let reviewScore = '';
	//리뷰가 몇개 인지 세고, 반복문 돌리기
	while (ReviewCount > 0) {
		//평점 1~5 까지의 점수만큼 별 채우기

		//리뷰의 고유번호생성 : '.review' + count + ' ' + '.review_star_count'
		reviewScore = document.querySelector('.review' + count + ' ' + '.review_star_count').innerHTML; // 별점
		console.log('.review' + count + ' ' + '.review_star_count' + '의 별점 : ' + reviewScore);
		console.log(reviewScore);
		reviewScore = Number(reviewScore);

		//각 리뷰에 대한 별모양에 별점만큼 색 채우기
		switch (reviewScore) {
			case 1:
				document.querySelector('.review' + count + ' ' + '.score1').style.color = '#ffb55e';
				break;
			case 2:
				document.querySelector('.review' + count + ' ' + '.score1').style.color = '#ffb55e';
				document.querySelector('.review' + count + ' ' + '.score2').style.color = '#ffb55e';
				break;
			case 3:
				document.querySelector('.review' + count + ' ' + '.score1').style.color = '#ffb55e';
				document.querySelector('.review' + count + ' ' + '.score2').style.color = '#ffb55e';
				document.querySelector('.review' + count + ' ' + '.score3').style.color = '#ffb55e';
				break;
			case 4:
				document.querySelector('.review' + count + ' ' + '.score1').style.color = '#ffb55e';
				document.querySelector('.review' + count + ' ' + '.score2').style.color = '#ffb55e';
				document.querySelector('.review' + count + ' ' + '.score3').style.color = '#ffb55e';
				document.querySelector('.review' + count + ' ' + '.score4').style.color = '#ffb55e';
				break;
			case 5:
				document.querySelector('.review' + count + ' ' + '.score1').style.color = '#ffb55e';
				document.querySelector('.review' + count + ' ' + '.score2').style.color = '#ffb55e';
				document.querySelector('.review' + count + ' ' + '.score3').style.color = '#ffb55e';
				document.querySelector('.review' + count + ' ' + '.score4').style.color = '#ffb55e';
				document.querySelector('.review' + count + ' ' + '.score5').style.color = '#ffb55e';
				break;
			default:
				console.log('오류');
		}

		count++;
		ReviewCount--;
	}
}
