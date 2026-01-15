import {Spinner} from './spinner.js';
import {Calendar} from './calendar.js';


document.addEventListener("DOMContentLoaded", async () => {
    const cal = Calendar('calendar');
    const spr = Spinner('calendar');

    await spr.renderSpinner().delay(0);
    $.ajax({
        url: "/loadevent.php",
        type: "get",


        success: function (data) {
            // console.log(data);
            const mockData = JSON.parse(data);


            console.log(mockData);
            cal.bindData(mockData);
            cal.render();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus);

        }
    });

});
