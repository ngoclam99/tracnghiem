var set_province_code = '';
var set_district_code = '';
var set_ward_code = '';
var avatar = null;


$(async function () {
    await $.ajax({
        url: 'controller/location/provinces.php',
        type: 'get',
        success: function (data) {
            if (data.statusCode == 200) {
                data.content.forEach(p => {
                    $('.slProvinces').append(`<option value="${p.code}">${p.full_name}</option>`);
                })
                $('.slProvinces').trigger('change');
            }
        }
    })



    LoadJobs();
    LoadWorkPlaces();
    LoadMemberDetail();

    $(document).on('change', '#slProvinces', function () {
        LoadDistricts($('#slProvinces option:selected').val());
    })

    $(document).on('change', '.slDistricts', function () {
        LoadWards($('#slDistricts option:selected').val());
    })


})

$('#btnSaveChanges').click(function () {
    let user_id = $('#pf_username').data('userid');
    let fullname = $('#pf_fullname').val();
    let birthdate = $('#txtBirthdate').val();
    let gender = $('#rbtM').is(':checked') ? 1 : $('#rbtF').is(':checked') ? 0 : -1;
    let phone = $('#pf_phone').val();
    let email = $('#pf_email').val();
    let province_code = $('#slProvinces option:selected').val();
    let district_code = $('#slDistricts option:selected').val();
    let ward_code = $('#slWards option:selected').val();
    let address = $('#txtAddress').val();
    let job_id = $('#slJobs option:selected').val();
    let workplace_id = $('#slWorkplaces option:selected').val();
    let position = $('#txtPosition').val();
    let working_unit = $('#txtWorkingUnit').val();

    if (fullname.trim().length == 0) {
        $('#msgFullname').text('Họ tên không được để trống!');
        $('#pf_fullname').select();
        return;
    }
    $('#msgFullname').text('');
    $('#msgPhone').text('');
    $('#msgEmail').text('');
    // if (validateEmail(email)) {
    //     $.ajax({
    //         url: 'controller/member/check-duplicate-email.php',
    //         type: 'get',
    //         data: { user_id, email },
    //         success: function (data) {
    //             if (data.statusCode == 200) {
    //                 if (parseInt(data.content) > 0) {
    //                     $('#msgEmail').text('Email này đã được thành viên khác sử dụng trên hệ thống!');
    //                     $('#pf_email').select();
    //                     return;
    //                 }
    //             } else {
    //                 $.toast({
    //                     heading: data.title,
    //                     text: data.content,
    //                     showHideTransition: 'fade',
    //                     icon: data.icon
    //                 })
    //             }
    //         }
    //     })
    // } else {
    //     if (email.length == 0) {
    //         $('#msgEmail').text("Vui lòng cung cấp email của bạn!!");
    //         $('#pf_email').select();
    //         return;
    //     } else {
    //         $('#msgEmail').text("Định dạng email không hợp lệ. Email là chữ không dấu, không chứa khoảng trắng và phải có địa chỉ máy chủ cụ thể, ví dụ: @gmail.com hoặc @yahoo.com!!");
    //         $('#pf_email').select();
    //         return;
    //     }
    // }

    if (validatePhoneNumber(phone)) {
        $.ajax({
            url: 'controller/member/check-duplicate-phone.php',
            type: 'get',
            data: { user_id, phone },
            success: function (data) {
                if (data.statusCode == 200) {
                    if (parseInt(data.content) > 0) {
                        $('#msgPhone').text('Số điện thoại này đã được thành viên khác sử dụng trên hệ thống!');
                        $('#pf_phone').select();
                        return;
                    }
                } else {
                    $.toast({
                        heading: data.title,
                        text: data.content,
                        showHideTransition: 'fade',
                        icon: data.icon
                    })
                }
            }
        })
    } else {
        if (phone.length == 0) {
            $('#msgPhone').text('Vui lòng cung cấp số điện thoại của bạn!');
            $('#pf_phone').select();
            return;
        } else {
            $('#msgPhone').text('Số điện thoại không hợp lệ. Số điện thoại gồm 10 chữ số, bắt đầu bằng số 0, không được chứa khoảng trắng!');
            $('#pf_phone').select();
            return;
        }
    }
   
    if ($(".slDoiTuong").val() == '') {
        Swal.fire({
            title: 'Thông báo',
            icon: 'error',
            html: 'Đơn vị không được để trống',
            customClass: 'swal-wide',
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
        });
        return !1;
    }

    if ($(".slDoiTuong").val() != '' && $(".slDoiTuongChiTiet").val() == '') {
        Swal.fire({
            title: 'Thông báo',
            icon: 'error',
            html: 'Đơn vị chi tiết không được để trống',
            customClass: 'swal-wide',
            showCloseButton: false,
            showCancelButton: false,
            focusConfirm: false,
        });
        return !1;
    }
 

    let formData = new FormData();

    formData.append("id", user_id);
    formData.append("fullname", fullname);
    formData.append("avatar", avatar == null ? '' : avatar);
    formData.append("gender", gender);
    formData.append("birthdate", formatDate(birthdate));
    formData.append("phone", phone);
    formData.append("email", email);
    formData.append("province_code", province_code);
    formData.append("district_code", district_code);
    formData.append("ward_code", ward_code);
    formData.append("address", address);
    formData.append("job_id", job_id);
    formData.append("workplace_id", workplace_id);
    formData.append("position", position);
    formData.append("working_unit", working_unit);
    formData.append("doituong", $(".slDoiTuong").val());
    formData.append("doituong_chitiet", $(".slDoiTuongChiTiet").val());

    $.ajax({
        url: 'controller/member/update-profile.php',
        type: 'post',
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            console.log(data);
            if (data.statusCode == 200) {
                Swal.fire({
                    icon: data.icon,
                    title: 'Successfull!!!',
                    text: data.title,
                })
            } else {
                $.toast({
                    heading: "Thông báo",
                    text: "Có lỗi xảy ra trong quá trình cập nhật dữ liệu",
                    showHideTransition: 'fade',
                    icon: data.icon
                })
            }
        }
    })
})


function LoadMemberDetail() {
    $.ajax({
        url: 'controller/member/detail.php',
        type: 'get',
        success: async function (data) {
            console.log(data);
            if (data.statusCode == 200) {
                let p = data.content;
                $('#pf_username').val(p.username);
                $('#pf_username').attr('data-userid', p.id);
                $('#pf_fullname').val(p.fullname);

                if(p.avatar && p.avatar.trim().length > 0){
                   $('.pf_avatar').attr('src',p.avatar);
                   $('.pf_avatar').attr('alt',p.fullname);
                   $('.pf_avatar').attr('title',p.fullname);
                   $('a.user').css("background-image", `url(${p.avatar})`);  
                }else{
                    $('.pf_avatar').attr('src','assets/images/no_avatar.png');
                    $('a.user').css("background-image", `url(assets/images/no_avatar.png)`);        
                }

                if (p.get_birthdate == 1) {
                    $('#txtBirthdate').val(p.mBirthdate);
                }
                if (p.get_gender == 1) {
                    if (p.gender == 1) {
                        $('#rbtM').prop('checked', true);
                    } else if (p.gender == 0) {
                        $('#rbtF').prop('checked', true);
                    } else {
                        $('#rbtN').prop('checked', true);
                    }
                }

                $('#pf_phone').val(p.phone);
                $('#pf_cmnd').val(p.cmnd);

                if (p.get_address == 1) {
                    await $('#slProvinces').val(p.province_code);
                    set_province_code = p.province_code;
                    set_district_code = p.district_code;
                    set_ward_code = p.ward_code;
                    $('#txtAddress').val(p.address);
                }
                $('#slProvinces').trigger('change');


                if(p.get_job !='0'){
                    $('#slJobs').val(p.job_id);
                }
                if(p.get_workplace!='0'){
                    $('#slWorkplaces').val(p.workplace_id);
                }
                $('#txtPosition').val(p.position);
                $('#txtWorkingUnit').val(p.working_unit);
                $(".slDoiTuong").val(p.id_doituong).trigger('change');
                setTimeout(function() {
                    $(".slDoiTuongChiTiet").val(p.id_doituong_chitiet).trigger('change');
                }, 1000)
            }
        }
    })
}


function LoadWorkPlaces() {
    $.ajax({
        url: 'controller/workplace/list.php',
        type: 'get',
        success: function (data) {
            if (data.statusCode == 200) {
                data.content.forEach(w => {
                    $('#slWorkplaces').append(`<option value="${w.id}">${w.name}</option>`);
                })
            }
        }
    })
}

function LoadJobs() {
    $.ajax({
        url: 'controller/job/list.php',
        type: 'get',
        success: function (data) {
            data.forEach(j => {
                $('#slJobs').append(`<option value="${j.id}">${j.name}</option>`)
            })
        }
    })
}

function LoadWards(district_code) {
    if (typeof district_code != 'undefined') {
        $.ajax({
            url: 'controller/location/wards.php',
            type: 'get',
            data: { district_code },
            success: async function (data) {
                $('#slWards').empty();
                if (data.statusCode == 200) {
                    await data.content.forEach(w => {
                        $('#slWards').append(`<option value="${w.code}">${w.full_name}</option>`)
                    })

                    if (set_ward_code.trim().length > 0 && district_code.trim() == set_district_code.trim()) {
                        $('#slWards').val(set_ward_code);
                    }
                }
            }
        })
    }
}

function LoadDistricts(province_code) {
    if (typeof province_code != 'undefined') {
        $.ajax({
            url: 'controller/location/districts.php',
            type: 'get',
            data: { province_code },
            success: async function (data) {
                $('#slDistricts').empty();
                if (data.statusCode == 200) {
                    await data.content.forEach(d => {
                        $('#slDistricts').append(`<option value="${d.code}">${d.full_name}</option>`);
                    })

                    if (province_code == set_province_code) {
                        $('#slDistricts').val(set_district_code);
                    }
                    $('#slDistricts').trigger('change');
                }
            }
        })
    }
}

$(".btnImportAvatar").on("click", function (e) {
    var fileDialog = $('<input style="z-index:9999;" type="file"  accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">');
    fileDialog.click();
    fileDialog.on("change", onFileSelected);
    return false;
});

var onFileSelected = function (e) {
    if ($(this)[0].files && $(this)[0].files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.avatar .content img').attr('src', e.target.result);
        }
        avatar = $(this)[0].files[0];
        reader.readAsDataURL($(this)[0].files[0]);
    }
};



function validateEmail(email) {
    var pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return pattern.test(email);
}
function validatePhoneNumber(phoneNumber) {
    let regex = /^0\d{9}$/;
    return regex.test(phoneNumber);
}

function formatDate(date) {
    let d = date.split('/');
    return `${d[2]}-${d[1]}-${d[0]}`;
}

$(function() {
    $(".slDoiTuong").change(function() {
        loadDoiTuongChiTiet($(this).val());
    });

    loadDoiTuong();
    loadDoiTuongChiTiet($(".slDoiTuong").val())
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
                html = ' <option value="">---Chọn đối tượng ---</option>';
                list.forEach(val => {
                    html += '<option value="' + val['id'] + '"> ' + val['ten_donvi'] + ' </option>';
                })
                $(".slDoiTuong").html(html);
            }
        }
    })
}


function loadDoiTuongChiTiet(id) {
    $(".slDoiTuongChiTiet").hide();
    $(".slDoiTuongChiTiet").html("");
    if (parseInt(id) > 0) {
        $.ajax({
            url: 'controller/member/load_doituong.php',
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
