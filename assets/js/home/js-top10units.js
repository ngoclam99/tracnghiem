$(function () {
    $.ajax({
        url: 'controller/statistic/top10units.php',
        type: 'get',
        success: function (data) {
            if(data.statusCode == 200){
                let index = 1;
                var top = '';
                data.content.forEach(u=>{
                    if (u.info_max_point != '') {
                        console.log(u.info_max_point);
                        top = u.info_max_point['fullname'] + ' (' + (u.info_max_point['tongcaudung'] + '/' + u.info_max_point['number_of_questions']) + ') <span class="label label-info lb__static">' + (u.info_max_point['tongcaudung'] * u.info_max_point['mark_per_question']) + ' điểm</span>';
                    } else {
                        top = 'Không có dữ liệu';
                    }
                    console.log(top);
                    let tr = `<tr class="align-middle">
                                <td class="text-center" >
                                    ${index++}
                                </td>
                                <td class="fw-bold text-primary">
                                    ${u.workplace}
                                </td>
                                <td class="text-center"><span class="label label-info lb__static">${u.total_members.toLocaleString()}<span></td>
                                <td class="text-center" >
                                    <span class="label label-success lb__static">${u.candidates.toLocaleString()}</span>
                                </td>
                                <td class="text-center">` + top + `</td>
                            </tr>`;
                    $('.top10units').append(tr);
                })
                
            }
        }
    })
})
