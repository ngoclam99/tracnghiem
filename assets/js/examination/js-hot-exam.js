(function ($) {
	$(function () {
		$.ajax({
			url: 'controller/exam/get-hot-exam.php',
			type: 'get',
			success: function (data) {
				if (data.statusCode == 200) {
					let exam = data.content;
					$('#he_title').text(exam.title);
					$('#btnHERegulation').attr('data-regulation',exam.regulation);
					$(".load_banner").hide();
					if (exam.thumbnail != '') {
						$(".load_banner").html(`<img id="he_banner" src="${exam.thumbnail}" class="img-responsive"/>`);	
						setTimeout(function() {
							$(".load_banner").fadeIn(200);
						}, 100);
					}

					let bg = new Date(exam.begin);
					$(".title__center_home").fadeOut();
					// <span></span>
		            //     <span></span>
		            //     <span></span>
		            //     <span></span>
					$(".title__center_home").html(`<div class="box1">
		                
		                <h2 class="title mb10" id="he_title">CUỘC THI SẼ ${bg>new Date()?'DIỄN RA':'KẾT THÚC'} TRONG</h2>
		            </div>`);
					setTimeout(function() {
						$(".title__center_home").fadeIn(200);
					}, 100);
					if(bg>new Date()){
						$('#btnHEDoExam').html(`Chưa thể<br> tham gia`);
						$("#btnHEDoExam").attr("disabled", true);
					}else{
						$('#btnHEDoExam').html(`Tham gia<br> ngay`);
						$("#btnHEDoExam").removeAttr('disabled');
					}

					$('#he_begin div.time').text(`${checkTime(bg.getHours())}H${checkTime(bg.getMinutes())}`);
					$('#he_begin div.date').text(`${checkTime(bg.getDate())}/${checkTime(bg.getMonth() + 1)}/${bg.getFullYear()}`);

					let end = new Date(exam.end);
					$('#he_end div.time').text(`${checkTime(end.getHours())}H${checkTime(end.getMinutes())}`);
					$('#he_end div.date').text(`${checkTime(end.getDate())}/${checkTime(end.getMonth() + 1)}/${end.getFullYear()}`);

					$('#he_countdown div.count').attr('data-time',bg>new Date()?exam.begin: exam.end);

					$('#btnHEDoExam').attr('href',`${bg>new Date()?'javascript:void(0);':'index.php?module=examination&act=do-exam&id='+exam.id}`);


					$('.lst').each(function () {
						var $this = $(this),
							countdown = $this.find('.count'),
							endDate = countdown.data('time');
							
						countdown.timeTo({
							timeTo:  new Date(endDate).getTime(),
							displayDays: 2,
							theme: "black",
							fontSize: 25,
						});

					});
				}
			}
		})




		function checkTime(i) {
			return (i < 10) ? "0" + i : i;
		}

		function startTime() {
			var today = new Date(),
				h = checkTime(today.getHours()),
				m = checkTime(today.getMinutes()),
				s = checkTime(today.getSeconds());
			$("#time").text(h + ":" + m + ":" + s);
			t = setTimeout(function () {
				startTime();
			}, 500);
		}
		startTime();
	});
})(jQuery);

$('#btnHERegulation').click(function(e){
	e.preventDefault();
	let regulation = $(this).data('regulation');
	Swal.fire({
		icon: 'info',
		title: 'THỂ LỆ CUỘC THI',
		html: regulation,
		customClass: 'swal-wide',
		confirmButtonText:'Ok, tôi đã hiểu!'		
	  })
})
