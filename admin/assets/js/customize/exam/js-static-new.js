LoadProvinces();
loadCuocThi();
loadDoiTuong();

$(function() {
	let dBegin = new Date();
	let ngayBD = new Date(dBegin.getFullYear() - 1, dBegin.getMonth()  + 1, dBegin.getDate());
	dBegin.addDays(-10);
	$('#dtpBegin').datetimepicker({
	    defaultDate: ngayBD,
	    format: 'DD/MM/YYYY HH:mm',
	});
	$('#dtpEnd').datetimepicker({
	    defaultDate: dBegin,
	    format: 'DD/MM/YYYY HH:mm'
	});
	
    $('.listTinh').change(function() {
        if ($('.listTinh').val() == '') {
        	$('.listHuyen').html('<option value="0">--- Chọn huyện --- </option>');
        	$('.listXa').html('<option value="0">--- Chọn xã --- </option>');
        }
        LoadDistrictsByPro($(this).val());
    });

    $(".listCuocThi").change(function() {
        loadThongKe();
    })

    $(".slDoiTuong").change(function() {
        loadDoiTuongChiTiet($(this).val());
        loadThongKe();
        $(".slDoiTuongChiTiet").change(function() {
            loadThongKe();
        });
    });

});

function loadThongKe() {
    $.ajax({
         url: 'controller/exam/report-statistic-new.php',
         type: 'POST',
         data: {
            'load_thongke': 1,
            'id_cuocthi': $('.listCuocThi').selectpicker('val'),
            'id_dt' : $(".slDoiTuong").val(),
            'id_dtct' : $(".slDoiTuongChiTiet").val(),
        },
        success: function(res) {
            html = ``;
            if (res != '') {
                $("#tong").html("0");
                $("#tbody").html("Không có dữ liệu");
                data = JSON.parse(res);
                data['arr'].map(function(index, elem) {
                    html += `<tr>
                        <td class="text-center"><span class="img_logo_rank">
                                <span class="rank__flex_left_number">${elem+1}</span>
                                <img src="../assets/images/${index['logo']}" width="50px">
                            </span></td>
                        <td class="text-center">${index['fullname']}</td>
                        <td class="text-center">${index['tinh']['full_name']}</td>
                        <td class="text-center">${index['doituong']['title']}</td>
                        <td class="text-center"><strong> ${index['tonglanthi']}</strong></td>
                        <td class="text-center"><span>${index['tongcaudung'] * index['mark_per_question']} điểm</span></td>
                    </tr>`;
                });
                $("#tong").html(data['total']);
                $("#tongluothi").html(data['tong_luotthi']);
                // $("#tbody").html(html);
            } else {
                $("#tbody").html("Không có dữ liệu");
            }

        }
    })
}

function loadDoiTuong() {
    $.ajax({
        url: location.href,
        type: 'POST',
        data: {
            load_dt: 1,
        },
        success: function (data) {
            if (data != '') {
                list = JSON.parse(data);
                html = ' <option value="">---Chọn đối tượng ---</option>';
                list.forEach(val => {
                    html += '<option value="' + val['id'] + '"> ' + val['ten_donvi'] + ' </option>';
                })
                $(".slDoiTuong").html(html);
                loadThongKe();
            }
        }
    })
}
function loadDoiTuongChiTiet(id) {
    $(".slDoiTuongChiTiet").html("");
    if (parseInt(id) > 0) {
        $.ajax({
            url: location.href,
            type: 'POST',
            data: {
                load_dt_chitiet: 1,
                id_doituong: id,
            },
            success: function (data) {
                if (data != '') {
                    list = JSON.parse(data);
                    html = '<option value="">---Chọn đơn vị ---</option>';
                    if (list.length > 0) {
                        $(".slDoiTuongChiTiet").fadeIn(500);
                        list.forEach(val => {
                            html += '<option value="' + val['id'] + '"> ' + val['title'] + ' </option>';
                        })
                    }
                    $(".slDoiTuongChiTiet").html(html);
                }
            }
        });
    }
}

function LoadProvinces() {
    $.ajax({
        url: 'controller/exam/report-statistic-new.php',
        type: 'POST',
        data: {
        	'loadtinh': 1
        },
        success: function (data) {
        	data      
            let default_pro = -1;
            $('.listTinh').empty();
            $('.listTinh').append(`<option value="" selected>--- Tất cả --- </option>`);
            data.forEach(p => {
                if (p.default_pro == 1) {
                    default_pro = p.code;
                }
                $('.listTinh').append(`<option value="${p.code}">${p.full_name}</option>`);
            })

            $(`.listTinh option[value=${default_pro}]`);
        }
    })
}

function LoadDistrictsByPro(province_code) {
    $.ajax({
        url: 'controller/location/districts.php',
        type: 'get',
        data: { province_code },
        success: function (data) {
            if (parseInt(province_code) > 0) {   
                $('.listHuyen').empty();
                html = `<option value="0">--- Chọn huyện --- </option>`;
                data.forEach(d => {
                	html += `<option value="${d.code}">${d.full_name}</option>`;
                })
                $('.listHuyen').append(html);
            }
            $('.listHuyen').change(function() {
		        LoadWardsByDist($(this).val());
		    });
        }
    });
}

function LoadWardsByDist(district_code) {
    $.ajax({
        url: 'controller/location/wards.php',
        type: 'get',
        data: { district_code },
        success: function (data) {
            $('.listXa').empty();
            html = `<option value="0">--- Chọn xã --- </option>`;
            data.forEach(w => {
            	html += `<option value="${w.code}">${w.full_name}</option>`;
            })
            $('.listXa').html(html);

        }
    })
}

function loadCuocThi() {
    $.ajax({
        url: '../controller/member/load_doituong.php',
        type: 'POST',
        data: {
            load_cuocthi: 1,
        },
        success: function (data) {
            if (data != '') {
                list = JSON.parse(data);
                html = '<option>--- Chọn cuộc thi ---</option>';
                list.forEach(val => {
                    html += '<option value="' + val['id'] + '"> ' + val['title'] + ' </option>';
                })
                $(".listCuocThi").html(html);
           		$('.listCuocThi').selectpicker('refresh');
                loadThongKe();
            }
        }
    })
}

Date.prototype.addDays = function(days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
}

function Date2TimeStamp(datetime) {
    let arr = datetime.split(' ');
    let part1 = arr[0].split('/');
    let year = part1[2];
    let month = part1[1];
    let day = part1[0];

    let part2 = arr[1].split(':');
    let hour = part2[0];
    let minute = part2[1];

    return `${year}-${month}-${day} ${hour}:${minute}:00`;
}
