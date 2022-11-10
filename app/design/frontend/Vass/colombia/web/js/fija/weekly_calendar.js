/* 
* Listado de fechas invalidas en el calendario
*/
var invalidDates = [
    '2022-6-21',
    '2022-6-22',
    // '2022-6-20',
    // '2022-6-25',
    // '2022-7-5',
]

weeklyCalendar(invalidDates, {

    // Devuelve el dia al que se le hizo clic
    clickDate: function (date) {
        console.log(date.year)
        console.log(date.month)
        console.log(date.day)
    },

});