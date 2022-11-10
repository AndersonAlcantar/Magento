function weeklyCalendar(invalidDates, options) {
	var that = this,
		_date = new Date(),   // Fecha
		currentDay = _date.getDay(),  // Dia actual
		weekNum = $(".js-calendar-header").attr("ids");
	datSelected = 0


	/* 
	 * Fecha formateada y remplazad con 0
	 */
	var format = function (num) {
		var _f = num < 10 ? '0' + num : num;
		return _f
	}

	/* 
	 * Crear los dias de la semana
	 */
	var createWeek = function () {
		var lis = '';
		var weeks_ch = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
		for (var i = 0, len = weeks_ch.length; i < len; i++) {
			lis += '<li class="f-c-calendar__header_day">' + weeks_ch[i] + '</li>';
		};
		$(".js-calendar-weekList").html(lis);
	}

	/* 
	 * Pinta los dias del calendario
	 */
	var countTime = function (n) {
		var getTime = _date.getTime() + (24 * 60 * 60 * 1000) * n;
		var needDate = new Date(getTime);
		var getYear = needDate.getFullYear();
		var getMonth = needDate.getMonth() + 1;
		var getDate = needDate.getDate();

		// if (getDate == 32 || getMonth == 4 && getDate == 31 || getMonth == 6 && getDate == 31 || getMonth == 9 && getDate == 31 || getMonth == 11 && getDate == 31) {
		// 	getDate = 1;
		// }

		var obj = {
			'year': getYear,
			'month': getMonth,
			'date': getDate
		};
		return obj
	}


	// Agrega clase .is-invalid si la fecha se encuentra en el listado de fechas invalidas
	var validateDateInvalid = function () {
		const calendarDays = document.querySelectorAll(".js-calendar-dayWeek")

		calendarDays.forEach(day => {
			invalidDates.forEach(invalidDate => {
				const completeCalendarDate = `${day.dataset.year}-${day.dataset.month}-${day.dataset.date}`;

				if (invalidDate == completeCalendarDate) {
					day.classList.add("is-invalid");
				}
			});
		});

	}

	// Renderiza el calendario completo
	var createDate = function (cDay) {
		var _cDay = cDay;
		var dateHtml = '';
		var currY = format(_date.getFullYear()),
			currM = format(_date.getMonth() + 1),
			currD = format(_date.getDate());

		for (var i = _cDay; i < _cDay + 7; i++) {

			if (currY == countTime(i).year && currM == countTime(i).month && currD == countTime(i).date) {
				dateHtml += '<li class="f-c-calendar__grid_day is-current js-calendar-dayWeek" data-year=' + countTime(i).year + ' data-month=' + countTime(i).month + ' data-date=' + countTime(i).date + '>'
					+ '<span>' + (format(countTime(i).date)) + '</span>'
					+ '</li>'
			} else {
				dateHtml += '<li class="f-c-calendar__grid_day js-calendar-dayWeek" data-year=' + countTime(i).year + ' data-month=' + countTime(i).month + ' data-date=' + countTime(i).date + '>'
					+ '<span>' + format(countTime(i).date) + '</span>'
					+ '</li>'
			}
		}

		$(".js-calendar-boxContent").html(dateHtml);
	}


	/* 
	 * Cambia entre la semana anterior y la siguiente
	 */
	var changeWeek = function (weekNum) {
		createDate(- currentDay + (7 * weekNum));
		$(".js-calendar-header").attr("ids", weekNum);
		titleTime();
		validateDateInvalid();
		addClassCurrentToDate();
	}

	/* 
	 * Obtiene la semana anterior
	 */
	$(".js-calendar-btn-prevWeek").on("click", function () {
		weekNum--;
		changeWeek(weekNum);
	})

	/* 
	 * Obtiene la semana siguiente
	 */
	$(".js-calendar-btn-nextWeek").on("click", function () {
		weekNum++;
		changeWeek(weekNum);
	})


	/* 
	 * Renderiza el titulo del calendario 
	 */
	var titleTime = function () {
		var dayWeek = $(".js-calendar-boxContent").find("li");
		var titleHtml = '';
		titleHtml += getMonthLong($(dayWeek[0]).attr('data-month')) + ' ' + format($(dayWeek[0]).attr('data-year'));
		$(".js-calendar-title").html(titleHtml);
	}


	// Combierte de número a letras los meses del año
	var getMonthLong = function (number) {
		let monthLong = ""

		switch (number) {
			case "1":
				monthLong = "Enero";
				break;
			case "2":
				monthLong = "Febrero";
				break;
			case "3":
				monthLong = "Marzo";
				break;
			case "4":
				monthLong = "Abril";
				break;
			case "5":
				monthLong = "Mayo";
				break;
			case "6":
				monthLong = "Junio"
				break;
			case "7":
				monthLong = "Julio"
				break;
			case "8":
				monthLong = "Agosto"
				break;
			case "9":
				monthLong = "Septiembre"
				break;
			case "10":
				monthLong = "Octubre"
				break;
			case "11":
				monthLong = "Noviembre"
				break;
			case "12":
				monthLong = "Diciembre"
				break;
		}

		return monthLong
	}


	/* 
	 * Obtiene la fecha del evento 
	 */
	$(".js-calendar-boxContent").on("click", ".js-calendar-dayWeek", function () {
		var textDate = {
			day: $(this).attr("data-date"),
			month: $(this).attr("data-month"),
			year: $(this).attr("data-year")
		};

		$(this).addClass("is-select");
		$(this).siblings().removeClass("is-select");

		options['clickDate'](textDate);
		daySelected = textDate
	})

	var addClassCurrentToDate = (e) => {
		const listOfDays = document.querySelectorAll(".js-calendar-dayWeek");

		listOfDays.forEach(element => {
			if (element.dataset.date == daySelected.day && element.dataset.month == daySelected.month && element.dataset.year == daySelected.year) {
				element.classList.add("is-select")
			}
		});
	}

	/* 
	 * Inicializar el calendario
	 */
	var initWeeklyCalendar = function () {
		createWeek();
		createDate(- currentDay);
		validateDateInvalid();
		titleTime();
	}()

}
