loadDoiTuong();

$(document).ready(function() {
	$(".slDoiTuong").change(function() {
		loadDT();
	});

	loadDT();

	function loadDT() {
		$.ajax({
			url: 'controller/statistic/statistic.php',
			type: 'POST',
			data: {
				'load_statisc': 1,
				'id_dt': $(".slDoiTuong").val(),
			},
		})
		.done(function(res) {
			$("#tbody_dt").html("");
			$("#tbody_dt").hide();
			if (res != 'null') {
				data = JSON.parse(res);
				html = ``;
				data.map(function(val, index) {
					html += `<tr>
                            <td class="text-center">${index+1}</td>
                            <td >${val['title']}</td>
                            <td class="text-center"><span class="label label-info lb__static">${val['tongnguoithi']}</span></td>
                            <td class="text-center"><span  class="label label-success lb__static">${val['tongluotthi']}</span></td>
                        </tr>`;
				});
				$("#tbody_dt").html(html);
				$("#tbody_dt").fadeIn(500);
			} else {
				$("#tbody_dt").fadeIn(500);
				data = JSON.parse(res);
				$("#tbody_dt").html(`<tr>
                            <td class="text-center">${data['id']}</td>
                            <td>${data['title']}</td>
                            <td>${data['tongnguoithi']}</td>
                            <td>${data['tongluotthi']}</td>
                        </tr>`);
			}
		})
	}
});

function loadDoiTuong() {
    $.ajax({
        url: 'controller/member/load_doituong.php',
        type: 'POST',
        data: {
            load_dt: 1,
        },
        success: function (data) {
            if (data != '') {
                list = JSON.parse(data);
                html = '';
                list.forEach(val => {
                    html += '<option value="' + val['id'] + '"> ' + val['ten_donvi'] + ' </option>';
                })
                $(".slDoiTuong").html(html);
            }
        }
    })
}
