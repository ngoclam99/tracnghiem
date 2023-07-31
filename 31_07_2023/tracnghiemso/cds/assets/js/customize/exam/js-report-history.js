var page = 1,
    pageSize = 10;
$(function () {
    loadDoiTuong();
    $('#btnSearch').click();
    $(document).on('click', 'a.btnDelete', function (e) {
        e.preventDefault();
        let result_id = $(this).closest('tr').attr('id');
        Swal.fire({
            title: 'Bạn có chắc muốn xóa kết quả bài thi này?',
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Xác nhận xóa',
            denyButtonText: `Để tôi suy nghĩ lại!`,
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'controller/exam/delete-result.php',
                    type: 'post',
                    data: { result_id },
                    success: function (data) {
                        console.log(data);
                        if (data.statusCode == 200) {
                            Swal.fire({
                                position: 'top-end',
                                icon: data.icon,
                                title: data.title,
                                text: data.content,
                                showConfirmButton: false,
                                timer: 300
                            })
                            console.log(data)
                            LoadHistory();
                        }
                    }
                })
            }
        })
    })
    $("#pagination").on("click", "li a", function (event) {
        event.preventDefault();
        page = $(this).text();
        LoadHistory();
    });
})

$('#btnSearch').click(function () {
    page = 1;
    LoadHistory();
})

function LoadHistory() {
    $.ajax({
        url: 'controller/exam/report-history.php',
        type: 'get',
        data: {
            page,
            pageSize,
            search: $('#txtSearch').val(),
            exams: $('#slExams').selectpicker('val'),
            id_doituong: $('.slDoiTuong').val(),
            workplaces: $('#slUnits').selectpicker('val')
        },
        success: function (data) {
            $('#tblData').empty();
            if (data.statusCode == 200) {
                let idx = pageSize != 'All' ? (page - 1) * pageSize : 0;
                data.content.forEach(t => {
                    let tr = `<tr id = "${t.result_id}">
                              <td>${++idx}</td>
                                <td class="text-nowrap fw-bold text-primary">${t.username}</td>
                                <td class="text-nowrap">${t.fullname}</td>
                                <td class="text-nowrap">${t.id_doituong}</td>
                                <td class="text-nowrap">${t.get_birthdate == 1 ? t.birthdate : ''}</td>
                                <td class="text-nowrap">${t.phone}</td>
                                <td class="text-nowrap">${t.email}</td>
                                <td class="text-nowrap">${t.get_job == 1 ? t.job : ''}</td>
                                <td class="text-nowrap">${t.get_workplace == 1 ? t.workplace: ''}</td>
                                <td class="text-nowrap">${t.get_position == 1 ? t.position : ''}</td>
								<td class="text-nowrap">${t.forecast}</td> // tinh
                                <td class="text-nowrap text-warning fw-bold">${t.exam}</td>
                                <td class="text-center fw-bold text-info">${t.times}</td>
                                <td class="text-center fw-bold text-info">${t.mark}/${t.total_marks}.</td>
                                <td class="text-nowrap">${t.exam_date}</td>
                                <td class="text-center fw-bold text-info">${formatDuration(t.spent_duration)}</td>
                                <td class="text-nowrap">
                                    <a href="index.php?module=exam&act=history-detail&id=${t.result_id}&candidate=${t.candidate}"><i class="fa fa-eye text-info"></i></a>
                                    <a href="#" class="btnDelete"><i class="fa fa-trash-o text-danger ml-2"></i></a>
                                </td>
                    </tr>`;
                    $('#tblData').append(tr);
                })

                $('#pagination').empty();
                if (data.pages > 1) {
                    for (i = 1; i <= data.pages; i++) {
                        $('#pagination').append(`<li class="${page == i ? 'active' : ''}"><a href="#">${i}</a></li>`);
                    }
                }
				
				document.getElementById("soluotthi").innerText = data.soluotthi;
				document.getElementById("songuoithi").innerText = data.content.length;

            }
        }
    })
}

function formatDuration(duration) {
    let minutes = Math.floor(duration / 60);
    let seconds = duration % 60;
    return `${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
}

$('#slPageSize').on('change', function () {
    pageSize = $('#slPageSize option:selected').text();
    $('#btnSearch').click();
})


$('#btnExportExcel').click(function () {
    $("#tableData").table2excel({
        name: "Sheet1",
        filename: "data",
        fileext: ".xlsx",
        exclude_rows: false,
        exclude_cols: false,
        preserveColors: false,
        exportOptions: {
            format: {
              date: {
                display: function (data, format) {
                  // Modify this function to remove date formatting
                  return data;
                }
            }
        }
      }});
})

function loadDoiTuong() {
    $.ajax({
        url: 'controller/exam/report-history.php',
        type: 'POST',
        data: {
            load_dt: 1,
        },
        success: function (data) {
            if (data != '') {
                list = JSON.parse(data);
                html = '<option>---Tất cả đơn vị---</option>';
                list.forEach(val => {
                    html += '<option value="' + val['id'] + '"> ' + val['ten_donvi'] + ' </option>';
                })
                $(".slDoiTuong").html(html);
            }
        }
    })
}
