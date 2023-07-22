$(function () {
    $.ajax({
        url: 'controller/statistic/top10units.php',
        type: 'get',
        success: function (data) {
            if(data.statusCode == 200){
                let index = 1;
                var top = '';
                data.content.forEach(u=>{
                    let tr = `<tr class="align-middle">
                                <td class="text-center" >
                                    ${index++}
                                </td>
                                <td class="fw-bold text-primary">
                                    ${u.name}
                                </td>
                                <td class="text-center"><span class="label label-info lb__static">${u.tongthisinh.toLocaleString()}<span></td>
                                <td class="text-center" >
                                    <span class="label label-success lb__static">${u.tongluotthi.toLocaleString()}</span>
                                </td>
                            </tr>`;
                    $('.top10units').append(tr);
                })
                
            }
        }
    })
})
