$(document).ready(function () {
    $('#dsp').click(function () {
        if ($(this).is(':checked')) {
            $('.confstr').show(100);
        } else {
            $('.confstr').hide(100);
        }
    });


    // .clearbookname, .clearprname, .clearname, .clearyear, .clearsplit, .cleartitle, .clearnegativ, .clearautor, .clearinvent
    // $('.clearbookname').click(function(){
    // 	$('#bookname').val('');
    // });
    // $('.clearprname').click(function(){
    // 	$('#prname').val('');
    // });
    // $('.clearname').click(function(){
    // 	$('#name').val('');
    // });
    // $('.clearyear').click(function(){
    // 	$('#year').val('');
    // });
    // $('.clearsplit').click(function(){
    // 	$('#split').val('');
    // });
    // $('.cleartitle').click(function(){
    // 	$('#title').val('');
    // });
    // $('.clearnegativ').click(function(){
    // 	$('#negativ').val('');
    // });
    $('.clearlownumber').click(function () {
        $('#lownumber').val('');
    });
    $('.cleartitle').click(function () {
        $('#title').val('');
    });
    // $('.clearall').click(function(){
    // 	$('#bookname').val('');
    // 	$('#prname').val('');
    // 	$('#name').val('');
    // 	$('#year').val('');
    // 	$('#split').val('');
    // 	$('#title').val('');
    // 	$('#title').val('');
    // 	$('#negativ').val('');
    // 	$('#autor').val('');
    // 	$('#invent').val('');
    // });


});
let auth = function () {
    var operator = $('#operator').val();
    if (operator !== "Фамилия оператора") {
        $.cookie('operator', operator, {expires: 1});
        location.reload();
    }
}
let logout = function () {
    $.removeCookie('operator');
    location.reload();
}
let save = function (id) {
    var operator = $('#operator').val();
    var filename = $('#filename').val();
    var filepath = $('#filepath').val();
    var numberofpages = $('#numberofpages').val();
    var convocation = $('#convocation').val();
    var consinventory = $('#consinventory').val();
    var consinvfile = $('#consinvfile').val();
    var delivinventory = $('#delivinventory').val();
    var delivinvfile = $('#delivinvfile').val();
    var unit = $('#unit').val();
    var title = $('#title').val();
    var lownumber = $('#lownumber').val();
    var startdate = $('#startdate').val();
    var enddate = $('#enddate').val();
    var years = $('#years').val();
    var category = $('#category').val();
    var dsp = 0;
    if ($('#dsp').is(':checked')) {
        dsp = 1;
    }

    var confidentialpages = $('#confidentialpages').val();
    var oc = $('#oc').val();

    $.ajax({
        url: "/save.php",
        type: "post",
        dataType: "text",
        data: {
            "operator": operator,
            "filename": filename,
            "filepath": filepath,
            "numberofpages": numberofpages,
            "convocation": convocation,
            "consinventory": consinventory,
            "consinvfile": consinvfile,
            "delivinventory": delivinventory,
            "delivinvfile": delivinvfile,
            "unit": unit,
            "title": title,
            "lownumber": lownumber,
            "startdate": startdate,
            "enddate": enddate,
            "years": years,
            "category": category,
            "dsp": dsp,
            "confidentialpages": confidentialpages,
            "oc": oc,
            "id": id,
        },
        success: function (data) {
            // $('.messages').html(data.result);
            console.log(data);
            alert("Запись добавлена");
            location.reload();
            // $('.addfile').addClass('addfilebg');
            // setTimeout(function() {
            // $('.addfile').removeClass('addfilebg');
            // }, 500);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus);

            alert('ошибка добавления =(');

        }
    });


};

// let nextfile = function(){

// var filename = $('#filename').val();

//     $.ajax({
//         url: "/nextfile.php",
//         type: "post",
//         dataType: "text",
//         data: {
//             "filename": filename
//         },


//         success: function(data) {
// 						location.reload();
//         },
//         error: function(jqXHR, textStatus, errorThrown){
//           console.log(textStatus);
//           alert('Ошибка открытия файла=(');

//         }
//     });


// };
// let prevfile = function(){

// // var filename = $('#filename').val();

//     $.ajax({
//         url: "/prevfile.php",
//         type: "get",


//         success: function(data) {
// 						location.reload();
// 						// console.log(data);
// 						// alert('ok');
//         },
//         error: function(jqXHR, textStatus, errorThrown){
//           // console.log(textStatus);
//           alert('Ошибка открытия файла=(');

//         }
//     });


// };
function isValid() {
    var valuestart = $('#startdate').val();
    var valueend = $('#enddate').val();
    var years = [];
    var valid = new RegExp(/^[0-3]?[0-9]?\.?\s?([а-яА-Я0-9]+)?\.?\s?(\d{4})$/);
    if (valid.test(valuestart) && valid.test(valueend)) {
        var yearstart = valuestart.substr(valuestart.length - 4);
        var yearend = valueend.substr(valueend.length - 4);
        for (var i = Number(yearstart); i <= yearend; i++) {
            years.push(i);

        }
        years = years.join(' ');
        $('#years').val(years)

    } else {
        alert('Формат даты введен не верно. Дата должна быть в формате ДД.ММ.ГГГГ или ГГГГ или «месяц» ГГГГ');
    }
//     var mounth = {
//    'январь':'01',
//    'февраль':'02',
//    'март':'03',
//    'апрель':'04',
//    'май':'05',
//    'июнь':'06',
//    'июль':'07',
//    'август':'08',
//    'сентябрь':'09',
//    'октябрь':'10',
//    'ноябрь':'11',
//    'декабрь':'12'
// };
    // alert(mounth);
// alert(mounth['Июль']);

    // alert(value);
    // var pattern =  new RegExp(/[а-яА-Я]+/);
    // if (pattern.test(value)) {
    // 	value = value.split(" ");

    // 	// console.log(value);
    // 	if (value.length == 3) {
    // 		alert(value[1]);
    // 		alert(mounth[value[1]]);


    // 	}
    //     // alert("ok");
    // }
    // else {
    //     alert("not ok");
    // }
}

// $('.data').click(function(){
// 	data = $('.startdate');
// $pattern =  new RegExp("^"+pat+"","i");
// $('.startdate').test()
// });
let takeeditfile = function (id) {
    $('#filename').val('');
    $('#filepath').val('');
    $('#numberofpages').val('');
    $('#convocation').val('');
    $('#consinventory').val('');
    $('#consinvfile').val('');
    $('#delivinventory').val('');
    $('#delivinvfile').val('');
    $('#unit').val('');
    $('#title').val('');
    $('#lownumber').val('');
    $('#startdate').val('');
    $('#enddate').val('');
    $('#years').val('');
    $("#dsp").attr('checked', false);
    $('.confstr').hide(0);

    $.ajax({
        url: "/takeeditfile.php",
        type: "post",
        dataType: "text",
        data: {
            "id": id,


        },
        success: function (data) {
            console.log(data);
            // $('.messages').html(data.result);
            // var el = JSON.parse(data);
            // console.log(el);
            // alert(el.years);

            // $('#operator').val();
            $('#filename').val(el.filename);
            $('#filepath').val(el.filepath);
            $('#numberofpages').val(el.numberofpages);
            $('#convocation').val(el.convocation);
            $('#consinventory').val(el.consinventory);
            $('#consinvfile').val(el.consinvfile);
            $('#delivinventory').val(el.delivinventory);
            $('#delivinvfile').val(el.delivinvfile);
            $('#unit').val(el.unit);
            $('#title').val(el.title);
            $('#lownumber').val(el.lownumber);
            $('#startdate').val(el.startdate);
            $('#enddate').val(el.enddate);
            $('#years').val(el.years);
            if (el.dsp == 1) {
                $("#dsp").attr('checked', true);
                $('.confstr').show(100);
            }
            $('#confidentialpages').val(el.confidentialpages);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
            // alert("Ошибка удаления");

        }
    });
}

let rmcard = function (rmid, rmfiles) {
    $.ajax({
        url: "/rmcard.php",
        type: "post",
        dataType: "text",
        data: {
            "rmid": rmid,
            "rmfiles": rmfiles,


        },
        success: function (data) {
            // $('.messages').html(data.result);
            console.log(data);
            // let res = document.getElementById("result");
            // res.insertAdjacentHTML("beforeEnd","<style>div[time=\""+data+"\"]{display:none}</style>");
            alert("запись удалена");
            location.reload();

        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
            alert("Ошибка удаления");

        }
    });
}