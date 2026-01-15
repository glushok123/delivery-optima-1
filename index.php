<?php
require "auth.php";
?>
<?php
session_start();
require_once 'connect.php';

if ($_GET['do'] == 'logout') {
    unset($_SESSION['admin']);
    session_destroy();
}

if (!$_SESSION['admin']) {
    header("Location: /index.php");
    exit;
}
?>
<!doctype html>
<html lang="ru">

<head>


    <title>Календарь доставок</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/calendar.css?v=2">
    <link rel="stylesheet" href="css/theme.css?v=2">
    <link rel="stylesheet" href="css/spinner.css?v=2">
    <link rel="stylesheet" href="css/style.css?v=2">
</head>

<body>

<div id="result"></div>
<div class="container" style="margin:0px auto">

    <!-- For Demo Purpose -->
    <header class="text-white mb-2">
        <h1 class="display-4">Календарь доставок <span>Вы вошли как <?= $_SESSION['admin'] ?> <a
                        href="/index.php?do=logout">Выход</a></span></h1>
    </header>

    <!-- Calendar -->
    <div id="calendar" style="background-color:#fafafa"></div>

    <hr>

    <div class="row justify-content-center">
        <textarea type="text" id="suggest" style='width:100%'></textarea>
        <br>
        <!--button id='button2'>Копировать</button-->
    </div>
    <hr>
    <div class='container'>
        <div id="map" style='height:600px;width:100%'></div>
    </div>
    <hr>
    <br>

</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/js/all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=5a4c2c4b-698e-4b54-ab03-8abe0b9e9268"
        type="text/javascript"></script>


</body>
<script type="module" src="js/main.js?v=1"></script>
<script type="text/javascript">

    let openaddwindow = function (date) {
        let addform = document.getElementById(date);
        var d = new Date(date);
        let mou = d.getMonth();
        let day = d.getDate();
        mou++;
        if (mou < 10) {
            mou = '0' + mou;
        }
        if (day < 10) {
            day = '0' + day;
        }
        addform.insertAdjacentHTML("beforeEnd", "<div id='adddeliveryform" + date + "' class='adddeliveryform'><div onclick='return rmadd(this)' class='closeadddelivery'></div><input readonly id='adddate" + date + "' value='Доставка на " + day + "." + mou + "'><select name='addtime' id='addtime" + date + "' placeholder='Время доставки' required><option disabled selected >Время доставки</option><option value='10:00:00'>10.00</option><option value='11:00:00'>11.00</option><option value='12:00:00'>12.00</option><option value='13:00:00'>13.00</option><option value='14:00:00'>14.00</option><option value='15:00:00'>15.00</option><option value='16:00:00'>16.00</option><option value='17:00:00'>17.00</option><option value='18:00:00'>18.00</option><option value='19:00:00'>19.00</option></select><input name='addtime' id='addphone" + date + "' placeholder='Телефон' required><input name='addadress' id='addadress" + date + "' placeholder='Адрес доставки' required><textarea name='addcomment' id='addcomment" + date + "' rows='3' placeholder='Комментарий'></textarea><input onclick='adddelivery(\"" + date + "\"); return rmadd(this);' class='adddelivery' id='adddelivery' type='submit' value='Добавить'><input class='canceladd' type='submit' value='Отменить' onclick='return rmadd(this)'></div>");
        // alert(d);
    }

    let rmadd = function (el) {
        el.parentElement.remove();


    }
    let rmdelivery = function (rmid) {
        $.ajax({
            url: "/rmdelivery.php",
            type: "post",
            dataType: "text",
            data: {
                "rmid": rmid
            },
            success: function (data) {
                // $('.messages').html(data.result);
                console.log(data);
                let res = document.getElementById("result");
                res.insertAdjacentHTML("beforeEnd", "<style>div[time=\"" + data + "\"]{display:none}</style>");
                alert("Доставка удалена");
                location.reload();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                alert("Ошибка удаления: телефон для справок 89771384058");

            }
        });
    }
    let adddelivery = function (date) {


        var whoadd = $('#whoadd' + date).val();
        var time = $('#addtime' + date).val();
        var phone = $('#addphone' + date).val();
        var adress = $('#addadress' + date).val();
        var commment = $('#addcomment' + date).val();
        $.ajax({
            url: "/addevent.php",
            type: "post",
            dataType: "text",
            data: {
                "date": date,
                "time": time,
                "phone": phone,
                "adress": adress,
                "commment": commment
            },
            success: function (data) {
                // $('.messages').html(data.result);
                // console.log(data);
                alert("Доставка добавлена");
                location.reload();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // console.log(textStatus);

            }
        });


    };
    let editdelivery = function (el) {
        el.parentElement.parentElement.querySelector('[name="addtime"]').removeAttribute('readonly');
        el.parentElement.parentElement.querySelector('[name="addphone"]').removeAttribute('readonly');
        el.parentElement.parentElement.querySelector('[name="adress"]').removeAttribute('readonly');
        el.parentElement.parentElement.querySelector('[name="addcomment"]').removeAttribute('readonly');
        // el.parentElement.parentElement.querySelector('a').remove();
        el.parentElement.style.display = "none";
        el.parentElement.parentElement.querySelector('.saveblbut').style.display = "block";
        // el.parentElement.parentElement.getElementsByClassname('')
    };
    let canceledit = function (el) {
        el.parentElement.parentElement.querySelector('[name="addtime"]').setAttribute('readonly', 'readonly');
        el.parentElement.parentElement.querySelector('[name="addphone"]').setAttribute('readonly', 'readonly');
        el.parentElement.parentElement.querySelector('[name="adress"]').setAttribute('readonly', 'readonly');
        el.parentElement.parentElement.querySelector('[name="addcomment"]').setAttribute('readonly', 'readonly');

        el.parentElement.parentElement.querySelector('.editblbut').style.display = "block";
        el.parentElement.style.display = "none";
    }
    let saveeditdelivery = function (date, oldtime, id) {
        {
            var whoadd = $('#whoadd' + date).val();
            var time = $('#addtime' + date).val();
            var phone = $('#addphone' + date).val();
            var adress = $('#adress' + date).val();
            var commment = $('#addcomment' + date).val();
            // var id = $('#id'+date).val();
            $.ajax({
                url: "/editevent.php",
                type: "post",
                dataType: "text",
                data: {
                    "id": id,
                    "oldtime": oldtime,
                    "date": date,
                    "time": time,
                    "phone": phone,
                    "adress": adress,
                    "commment": commment
                },
                success: function (data) {
                    // $('.messages').html(data.result);
                    console.log(data);
                    alert("Доставка изменена");
                    location.reload();
                    // location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);

                }
            });
        }
    }
</script>

<?php
$today = date("Y-m-d");
$link = mysqli_connect($host, $user, $password, $database);
$sql = 'SELECT * FROM events WHERE `adddate` = "' . $today . '"';
$result = mysqli_query($link, $sql);
?>

<script>
    function init() {
        var suggestView1 = new ymaps.SuggestView('suggest');

        $('#button2').bind('click', function (e) {
            geocode();
        });

        function geocode() {
            // Забираем запрос из поля ввода.
            var request = $('#suggest').val();
            // Геокодируем введённые данные.
            ymaps.geocode(request).then(function (res) {
                var obj = res.geoObjects.get(0),
                    error, hint;

                if (obj) {
                    // Об оценке точности ответа геокодера можно прочитать тут: https://tech.yandex.ru/maps/doc/geocoder/desc/reference/precision-docpage/
                    switch (obj.properties.get('metaDataProperty.GeocoderMetaData.precision')) {
                        case 'exact':
                            break;
                        case 'number':
                        case 'near':
                        case 'range':
                            error = 'Неточный адрес, требуется уточнение';
                            hint = 'Уточните номер дома';
                            break;
                        case 'street':
                            error = 'Неполный адрес, требуется уточнение';
                            hint = 'Уточните номер дома';
                            break;
                        case 'other':
                        default:
                            error = 'Неточный адрес, требуется уточнение';
                            hint = 'Уточните адрес';
                    }
                } else {
                    error = 'Адрес не найден';
                    hint = 'Уточните адрес';
                }

                // Если геокодер возвращает пустой массив или неточный результат, то показываем ошибку.
                //if (error) {
                //   showError(error);
                //    showMessage(hint);
                //} else {
                showResult(obj);
                //}
            }, function (e) {
                console.log(e)
            })

        }

        function showResult(obj) {
            // Удаляем сообщение об ошибке, если найденный адрес совпадает с поисковым запросом.
            $('#suggest').removeClass('input_error');
            $('#notice').css('display', 'none');

            // Сохраняем полный адрес для сообщения под картой.
            address = [obj.getCountry(), obj.getAddressLine()].join(', ');
            // Сохраняем укороченный адрес для подписи метки.
            shortAddress = [obj.getThoroughfare(), obj.getPremiseNumber(), obj.getPremise()].join(' ');
            // Убираем контролы с карты.

            alert(shortAddress)
            console.log(address)
        }

        var myMap = new ymaps.Map('map', {
            center: [55.74, 37.58],
            zoom: 10,
            controls: []
        });

        // Создаем геообъект с типом геометрии "Точка".
        myGeoObject = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [55.8, 37.8]
            },
            // Свойства.
            properties: {
                // Контент метки.
                iconContent: 'Я тащусь',
                hintContent: 'Ну давай уже тащи'
            }
        }, {
            // Опции.
            // Иконка метки будет растягиваться под размер ее содержимого.
            preset: 'islands#blackStretchyIcon',
            // Метку можно перемещать.
            draggable: true
        }),
            myPieChart = new ymaps.Placemark([
                55.847, 37.6
            ], {
                // Данные для построения диаграммы.
                data: [
                    {weight: 8, color: '#0E4779'},
                    {weight: 6, color: '#1E98FF'},
                    {weight: 4, color: '#82CDFF'}
                ],
                iconCaption: "Диаграмма"
            }, {
                // Зададим произвольный макет метки.
                iconLayout: 'default#pieChart',
                // Радиус диаграммы в пикселях.
                iconPieChartRadius: 30,
                // Радиус центральной части макета.
                iconPieChartCoreRadius: 10,
                // Стиль заливки центральной части.
                iconPieChartCoreFillStyle: '#ffffff',
                // Cтиль линий-разделителей секторов и внешней обводки диаграммы.
                iconPieChartStrokeStyle: '#ffffff',
                // Ширина линий-разделителей секторов и внешней обводки диаграммы.
                iconPieChartStrokeWidth: 3,
                // Максимальная ширина подписи метки.
                iconPieChartCaptionMaxWidth: 200
            });


        myMap.geoObjects
        <?php
        foreach ($result as $value) {
            $address = $value['adress'];

            if ($address) {
                $url = 'https://geocode-maps.yandex.ru/1.x/?apikey=5a4c2c4b-698e-4b54-ab03-8abe0b9e9268&format=json&geocode=' . urlencode($address);

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_REFERER, 'https://optima-1.ru/'); // <-- важно
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');          // иногда помогает

                $raw = curl_exec($ch);
                $curlErr = curl_error($ch);
                curl_close($ch);

                // echo "\n\n===== ADDRESS: $address =====\n";
                // echo "URL: $url\n";
                // echo "CURL_ERR: $curlErr\n";
                // echo "RAW RESPONSE:\n";
                // echo $raw;
                // echo "\n\n";

                $res = json_decode($raw, true);
                $coordinates = $res['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
                $coordinates = explode(' ', $coordinates);

                echo('.add(new ymaps.Placemark([" ' . $coordinates[1] . ' ", " ' . $coordinates[0] . ' "], {
            balloonContent: "цвет <strong>' . $value['adress'] . '</strong>"
        }, {
            preset: "islands#icon",
            iconColor: "#0095b6"
        }))');
            }

        }
        ?>;

        // Создадим экземпляр элемента управления «поиск по карте»
        // с установленной опцией провайдера данных для поиска по организациям.
        var searchControl = new ymaps.control.SearchControl({
            options: {
                provider: 'yandex#map'
            }
        });

        myMap.controls.add(searchControl);

        // Программно выполним поиск определённых кафе в текущей
        // прямоугольной области карты.
        //searchControl.search('Шоколадница');
    }

    ymaps.ready(init);

</script>

</html>
