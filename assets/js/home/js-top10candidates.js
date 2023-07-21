
$(function () {
    $.ajax({
        url: 'controller/statistic/top10candidates.php',
        type: 'get',
        success: function (data) {
            if (data.statusCode == 200) {
				// for(var i = 0; i < data.content.length - 1; i++)
				// {
				// 	for(var j = i+1; j < data.content.length; j++)
				// 	{
				// 		if(data.content[j].id == data.content[i].id)
				// 		{
				// 			data.content.splice(j, 1);
				// 		}
				// 	}
				// }
				i = 1;
                data.content.forEach(m => {
					let tr = `<li>
                        <div class="rank__flex">
                            <div class="rank__flex_left">
                               <span class="img_logo_rank">
                                    <span class="rank__flex_left_number">${i}</span>
                                    <img src="assets/images/${m.logo}">
                                </span>
                            </div>
                            <div class="rank__flex_right">
                                <span class="rank__flex_right_auth">
                                    <span class="title__rank">${m.fullname}</span>
                                    <span class="red">(${m.mark}/${m.number_of_questions})</span>
                                </span>
                                <p class="rank__flex_right_time">${m.exam_date} <span class="red"> (${m.spent_duration}) </span></p> 
                            </div>

                            <div class="rank__flex_right_point">
                                <span class="rank__flex_right_point_text text--social">
                                    ${m.tong_mark} điểm
                                </span>
                            </div>
                        </div>
                    </li>`;
                    i++;
                    // let tr = `<div class="list-item">
                    //     <p class="date">
                    //         ${m.exam_date}
                    //     </p>
                    //     <p class="title">
                    //         <a href="javascript:void(0);" class="title__rank">
                    //             <span>${m.fullname}</span> - <span class="red">${m.mark}/${m.number_of_questions} (${m.tong_mark})</span>
                    //         </a>
                    //     </p>
                    // </div>`;
                    $('.top10candidates').append(tr);
                })
            }
        }
    })
})

function formatDuration(duration) {
    let minutes = Math.floor(duration / 60);
    let seconds = duration % 60;
    return `${minutes < 10 ? '0' + minutes : minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
}
