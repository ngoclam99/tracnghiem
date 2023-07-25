loadDoiTuong();
loadCuocThi();

$(document).ready(function() {
	$(".slDoiTuong, .listCuocThi").change(function() {
		loadDT();
	});

	$(".listCuocThiTop").change(function() {
		loadTop10();
	});

	loadDT();

	function loadDT() {
		$.ajax({
			url: 'controller/statistic/statistic.php',
			type: 'POST',
			data: {
				'load_statisc': 1,
				'id_dt': $(".slDoiTuong").val(),
				'id_cuocthi': $(".listCuocThi").val(),
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
                            <td class="text-center"><span class="label label-info lb__static">${val['tongthisinh']}</span></td>
                            <td class="text-center"><span  class="label label-success lb__static">${val['tongluotthisinh']}</span></td>
                        </tr>`;

				});
				$("#tbody_dt").html(html);
				$("#tbody_dt").fadeIn(500);
			} else {
				$("#tbody_dt").fadeIn(500);
				data = JSON.parse(res);
				$("#tbody_dt").html(`<tr>
                            <td class="text-center" colspan="4">Không có dữ liệu</td>
                        </tr>`);
			}
		})
	}
});

function loadTop10() {
	$.ajax({
		url: 'controller/statistic/statistic.php',
		type: 'POST',
		data: {
			'load_statisc': 1,
			'id_dt': $(".slDoiTuongTop10").val(),
			'id_cuocthi': $(".listCuocThiTop").val(),
		},
	})
	.done(function(res) {
		$("#tbody_dttop10").html("");
		$("#tbody_dttop10").hide();
		if (res != 'null') {
			data = JSON.parse(res);
			html = ``;
			data.map(function(val, index) {
				html += `<tr>
                        <td class="text-center">${index+1}</td>
                        <td >${val['title']}</td>
                        <td class="text-center"><span class="label label-info lb__static">${val['tongthisinh']}</span></td>
                        <td class="text-center"><span  class="label label-success lb__static">${val['tongluotthisinh']}</span></td>
                    </tr>`;

			});
			$("#tbody_dttop10").html(html);
			$("#tbody_dttop10").fadeIn(500);
		} else {
			$("#tbody_dttop10").fadeIn(500);
			data = JSON.parse(res);
			$("#tbody_dttop10").html(`<tr>
                        <td class="text-center" colspan="4">Không có dữ liệu</td>
                    </tr>`);
		}
	})
}

function loadCuocThi() {
	$.ajax({
        url: 'controller/member/load_doituong.php',
        type: 'POST',
        data: {
            load_cuocthi: 1,
        },
        success: function (data) {
            if (data != '') {
                list = JSON.parse(data);
                html = '';
                list.forEach(val => {
                	selected = '';
                	if (val['is_stat'] == '1') {
                		selected = 'Selected';
                	}
                    html += '<option ' + selected + ' value="' + val['id'] + '"> ' + val['title'] + ' </option>';
                })
                $(".listCuocThi").html(html);
                $(".listCuocThiTop").html(html);
            }
        }
    })
}

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
                html1 = '';

                list.forEach(val => {
                    html += '<option value="' + val['id'] + '"> ' + val['ten_donvi'] + ' </option>';
                	if (val['id'] == 4) {
                    	html1 += '<option value="' + val['id'] + '"> ' + val['ten_donvi'] + ' </option>';
                	}
                })
                $(".slDoiTuong").html(html);
                $(".slDoiTuongTop10").html(html1);

            }
        }
    })
}
