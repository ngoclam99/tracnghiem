$(document).ready(function(){
    $.ajax({
        url:'controller/member/profile.php',
        type:'get',
        success:function(data){
            if(data.statusCode == 200){
                let p = data.content;
                localStorage.setItem('candidate',p.id);
                if(p.avatar && p.avatar.trim().length > 0){
                   $('.pf_avatar').attr('src',p.avatar);
                   $('.pf_avatar').attr('alt',p.fullname);
                   $('.pf_avatar').attr('title',p.fullname);
                   $('a.user').css("background-image", `url(${p.avatar})`);  
                } else{
                    $('.pf_avatar').attr('src','assets/images/no_avatar.png');
                    $('a.user').css("background-image", `url(assets/images/no_avatar.png)`);        
                }
                $('.pf_username').text(p.username);
                $('.pf_email').text(p.email);
                $('.pf_role').text(`${p.role_id==1?'Đăng nhập bằng với vai trò Quản trị viên':p.role_id==2?'Đăng nhập với vai trò là Quản trị module':'Đăng nhập với vai trò người dùng'}`);
                let l = new Date(p.newest_login);
                
                $('.pf_newest_login').text(`${p.lasttime_login}`);
                $('.pf_current_ip_address').text(p.current_ip_address);
                $('.pf_dt').text(p.dt);
                if (p.dtct != null) {
                    $('.pf_dtct').text(p.dtct.title);
                } else {
                    $('.pf_dtct').text('N/A');
                }

                $('.pf_fullname').text(p.fullname);
                $('.pf_fullname').attr('data-userid',p.id);
                $('.pf_birthdate').text(p.birthdate);
                $('.pf_gender').text(p.gender);
                $('.pf_phone').text(p.phone);
               

                $('.pf_job').text(p.job);
                $('.pf_address').text(p.address);
                $('.pf_workplace').text(p.workplace);    
                $('.pf_applied_date').text(p.applied_date);
                $('.pf_lasttime_login').text(`${p.lasttime_login}`);  
                $("a.user").css("background-color");  
                
            }
        },
        error: function (jqXHR, exception) {
            console.log(jqXHR)
        }
    })
})
