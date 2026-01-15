const StopEventPropagation = (e) => {
    if (!e) return;
    e.cancelBubble = true;
    if (e.stopPropagation) e.stopPropagation();
};

export const Calendar = (id) => ({
    id: id,
    data: [],
    el: undefined,
    y: undefined,
    m: undefined,
    onDateClick(e) {
        StopEventPropagation(e);
        const el = e.srcElement;
        // console.log('click'); 
        // console.log(el);
    },
    onEventClick(e) {
        StopEventPropagation(e);
        const el = e.srcElement;
        console.log('click');
        let id = el.id;
        // alert(id);
        console.log(e);

        $.ajax({
            url: "/getevent.php",
            type: "post",
            dataType: "text",
            data: {
                "id": id
            },
            success: function (data) {
                // $('.messages').html(data.result);
                // console.log(data);
                let eventArr = JSON.parse(data);
                if (eventArr.is_owner) {
                    var buttons = "<div class='editblbut'><input onclick='return editdelivery(this);' class='adddelivery' id='editdelivery' type='submit' value='Редактировать'><input onclick='rmdelivery(" + eventArr.id + "); return rmadd(this)' class='canceladd' type='submit' value='× Удалить'></div><div class='saveblbut'><input onclick='saveeditdelivery(\"" + eventArr.adddate + "\",\"" + eventArr.addtime + "\",\"" + eventArr.id + "\");' class='saveelivery' id='saveelivery' type='submit' value='Сохранить'><input onclick='return canceledit(this);' class='canceledit' id='canceledit' type='submit' value='Отменить'>";
                } else {
                    var buttons = "Нет прав на изменение"
                }
                // alert(eventArr.addtime);
                var dispdate = new Date(eventArr.adddate);
                var dd = String(dispdate.getDate()).padStart(2, '0');
                var mm = String(dispdate.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = dispdate.getFullYear();
                dispdate = dd + '.' + mm + '.' + yyyy;
                // console.log(dispdate);

                el.insertAdjacentHTML("afterEnd", "<div class='adddeliveryform'><div onclick='return rmadd(this)' class='closeadddelivery'></div><input readonly id='adddate'" + eventArr.adddate + "' value='Доставка на " + dispdate + "'><input readonly name='addtime' id='addtime" + eventArr.adddate + "' placeholder='Время доставки' value='" + eventArr.addtime + "'><input readonly name='addphone' id='addphone" + eventArr.adddate + "' placeholder='Телефон' value='" + eventArr.phone + "'><a href='tel:" + eventArr.phone + "'>Позвонить</a><input readonly name='adress' id='adress" + eventArr.adddate + "' placeholder='Адрес доставки' value='" + eventArr.adress + "'><br style='clear:both'><a class='yanavi' href='yandexnavi:\/\/map_search?text=" + eventArr.adress + "'>Открыть Яндекс.Навигатор</a><textarea readonly name='addcomment' id='addcomment" + eventArr.adddate + "' rows='3' placeholder='Комментарий'>" + eventArr.comment + "</textarea>" + buttons + "</div></div>");
                // console.log(eventArr);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);

            }
        });

    },

    bindData(events) {
        // console.log(events);
        this.data = events.sort((a, b) => {

            if (a.time < b.time) return -1;
            if (a.time > b.time) return 1;
            return 0;
        });
    },
    renderEvents() {
        if (!this.data || this.data.length <= 0) return;
        const lis = this.el.querySelectorAll(`.${this.id} .days .inside`);
        let y = this.el.querySelector('.month-year .year').innerText;
        let m = lis[0].querySelector('.date').getAttribute('month');
        let eventObj = this;
        $.ajax({
            url: "/is_courier.php",
            type: "get",
            dataType: "text",
            success: function (answ) {
                // $('.messages').html(data.result);


                let is_courier = answ;


                lis.forEach((li) => {
                    let d = li.innerText;
                    let divEvents = li.querySelector('.events');
                    li.onclick = this.onDateClick;
                    // alert(this.data);
                    eventObj.data.forEach((ev) => {
                        let evTime = moment(ev.time);

                        if (evTime.year() == y && evTime.month() == m && evTime.date() == d) {
                            let frgEvent = document.createRange().createContextualFragment(`
                        <div id="el${ev.id}" time="${ev.time}" class="event ${ev.cls}">${evTime.format('HH:mm')} ${ev.desc}</div>
                    `);
                            divEvents.appendChild(frgEvent);
                            let divEvent = divEvents.querySelector(`.event[id='el${ev.id}']`);
                            divEvent.onclick = eventObj.onEventClick;
                        }


                    });
                    // let dateEv = moment().year(this.y).month(this.m).date(this.d);
                    // console.log(dateEv);

                    // console.log(this.y+"-"+this.m+"-"+dateEv);

                    let customY = y;
                    let customM = m;
                    let customD = d;
                    customM++;
                    if (customM < 10) {
                        customM = '0' + customM;
                    }
                    if (customD < 10) {
                        customD = '0' + customD;
                    }
                    let dateEv = customY + "-" + customM + "-" + customD;
                    let todayday = new Date();
                    let dateis = Date.parse(dateEv);//текущий день на календаре
                    // console.log(dateis);


                    var today = new Date();
                    var dd = String(today.getDate()).padStart(2, '0');
                    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = today.getFullYear();
                    today = yyyy + '-' + mm + '-' + dd;
                    var todayis = Date.parse(today);//сегодняшняя дата
                    // console.log(todayis);


                    let plusEv = document.createRange().createContextualFragment(`<span onclick='openaddwindow("${dateEv}")' class='addevent'>+</span>`);

                    if (is_courier != 1 && dateis >= todayis) {
                        divEvents.appendChild(plusEv);
                    }

                    // console.log(d);
                });
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);

            }
        });

    },

    render(y, m) {
        //-------------------------------------------------------------------------------------------
        //first time when you call render() without params, it is going to default to current date.
        //this logic here is to make sure if you re-render by calling render() without any param again,
        //if the calendar is already looking at some other month, then it will get the updated data, but
        //the calendar will not jump back to current month and stay at the previous month you are looking at.
        //this is useful when server side has updated events, calendar can re-bindData() and re-render() 
        //itself correctly to reflect any changes.
        if (isNaN(y) && isNaN(this.y)) {
            this.y = moment().year();
        } else if ((!isNaN(y) && isNaN(this.y)) || (!isNaN(y) && !isNaN(this.y))) {
            this.y = y > 1600 ? y : moment().year(); //calendar doesn't exist before 1600! :)
        }
        if (isNaN(m) && isNaN(this.m)) {
            this.m = moment().month();
        } else if ((!isNaN(m) && isNaN(this.m)) || (!isNaN(m) && !isNaN(this.m))) {
            this.m = m >= 0 ? m : moment().month(); //momentjs month starts from 0-11
        }
        //------------------------------------------------------------------------------------------

        const d = moment().year(this.y).month(this.m).date(1); //first date of month
        const now = moment();
        const frgCal = document.createRange().createContextualFragment(`
        <div class="calendar noselect p-5">
            <div class="month-year-btn d-flex justify-content-center align-items-center mb-2">
                <a class="prev-month"><i class="fas fa-caret-left fa-lg m-3"></i></a>
                <div class="month-year d-flex justify-content-center align-items-center">
                    <div class="month mb-2 mr-2">${moment().month(this.m).format('MMMM')}</div>
                    <div class="year mb-2">${this.y}</div>
                </div>
                <a class="next-month"><i class="fas fa-caret-right fa-lg m-3" aria-hidden="true"></i></a>
            </div>
            <ol class="day-names list-unstyled">
                <li><h6 class="initials">Пн</h6></li>
                <li><h6 class="initials">Вт</h6></li>
                <li><h6 class="initials">Ср</h6></li>
                <li><h6 class="initials">Чт</h6></li>
                <li><h6 class="initials">Пт</h6></li>
                <li><h6 class="initials">Сб</h6></li>
                <li><h6 class="initials">Вс</h6></li>
            </ol>
        </div>
        `);
        const isSameDate = (d1, d2) => d1.format('YYYY-MM-DD') == d2.format('YYYY-MM-DD');
        let frgWeek;
        d.day(0); //move date to the oldest Sunday, so that it lines up with the calendar layout
        for (let i = 0; i < 6; i++) { //loop thru 35 boxes on the calendar month
            frgWeek = document.createRange().createContextualFragment(`
            <ol class="days list-unstyled" week="${d.week()}">
                <li class="${d.add(1, 'd'), this.m != d.month() ? ' outside' : 'inside'}${isSameDate(d, now) ? ' today' : ''}" id="${this.y}-${d.format('MM')}-${d.format('DD')}"><div month="${d.month()}" class="date">${d.format('D')}</div><div class="events"></div></li>
                <li class="${d.add(1, 'd'), this.m != d.month() ? ' outside' : 'inside'}${isSameDate(d, now) ? ' today' : ''}" id="${this.y}-${d.format('MM')}-${d.format('DD')}"><div month="${d.month()}" class="date">${d.format('D')}</div><div class="events"></div></li>
                <li class="${d.add(1, 'd'), this.m != d.month() ? ' outside' : 'inside'}${isSameDate(d, now) ? ' today' : ''}" id="${this.y}-${d.format('MM')}-${d.format('DD')}"><div month="${d.month()}" class="date">${d.format('D')}</div><div class="events"></div></li>
                <li class="${d.add(1, 'd'), this.m != d.month() ? ' outside' : 'inside'}${isSameDate(d, now) ? ' today' : ''}" id="${this.y}-${d.format('MM')}-${d.format('DD')}"><div month="${d.month()}" class="date">${d.format('D')}</div><div class="events"></div></li>
                <li class="${d.add(1, 'd'), this.m != d.month() ? ' outside' : 'inside'}${isSameDate(d, now) ? ' today' : ''}" id="${this.y}-${d.format('MM')}-${d.format('DD')}"><div month="${d.month()}" class="date">${d.format('D')}</div><div class="events"></div></li>
                <li class="${d.add(1, 'd'), this.m != d.month() ? ' outside' : 'inside'}${isSameDate(d, now) ? ' today' : ''}" id="${this.y}-${d.format('MM')}-${d.format('DD')}"><div month="${d.month()}" class="date">${d.format('D')}</div><div class="events"></div></li>
                <li class="${d.add(1, 'd'), this.m != d.month() ? ' outside' : 'inside'}${isSameDate(d, now) ? ' today' : ''}" id="${this.y}-${d.format('MM')}-${d.format('DD')}"><div month="${d.month()}" class="date">${d.format('D')}</div><div class="events"></div></li>

            </ol>
            `);
            frgCal.querySelector('.calendar').appendChild(frgWeek);
        }

        frgCal.querySelector('.prev-month').onclick = () => {
            const dp = moment().year(this.y).month(this.m).date(1).subtract(1, 'month');
            this.render(dp.year(), dp.month());
        };
        frgCal.querySelector('.next-month').onclick = () => {
            const dn = moment().year(this.y).month(this.m).date(1).add(1, 'month');
            this.render(dn.year(), dn.month());
        };
        this.el = document.getElementById(this.id);
        this.el.innerHTML = ''; //replacing
        this.el.appendChild(frgCal);
        this.renderEvents();
    }
});
