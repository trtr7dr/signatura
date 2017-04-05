var paletteArray, nm, myImage, dominantColor;
var c = 0;

function sleep(ms) {
    ms += new Date().getTime();
    while (new Date() < ms) {
    }
}


function handleFileSelect(evt) {

    var name = document.getElementById('name').value;
    var haveName = document.getElementById('haveName').value;
    var dayTime = document.getElementById('dayTime').value;
    var res = document.getElementById('result');

    if (name == '' && haveName == 'Новый художник') {
	res.innerHTML = 'Введите имя';
    } else {

	if (haveName != 'Новый художник') {
	    name = haveName;
	}

	var files = evt.target.files;

	// перебор картинок
	var flag = 0;
	var colorThief = new ColorThief();
	var j = 0;
	for (var i = 0, f; f = files[i]; i++) {
	    if (!f.type.match('image.*')) {
		continue;
	    }
	    var reader = new FileReader();

	    reader.onload = (function (theFile) {
		return function (e) {
		    var span = document.getElementById('miniRes');
		    span.innerHTML = ['<img class="thumbPic" id="myImage0' + '" src="', e.target.result,
			'" title="', escape(theFile.name), '"/>'
		    ].join('');
		    document.getElementById('list').insertBefore(span, null);

		    myImage = document.getElementById('myImage0');

		    dominantColor = colorThief.getColor(myImage);

		    paletteArray = colorThief.getPalette(myImage, 10);
		    nm = name;

		    c = ajaxPal(dominantColor, paletteArray, nm, res, dayTime);

		    j++;
		};

	    })(f);

	    reader.readAsDataURL(f);

	}
    }
}


function ajaxPal(dominantColor, paletteArray, nm, res, dayTime) {
    jQuery.ajax({

	type: 'post',
	url: '/wp-content/themes/typecore-master/js/glo/ajax/admin.php',
	global: false,
	data: {
	    'dominant': dominantColor,
	    'palette': paletteArray,
	    'name': nm,
	    'dayTime': dayTime


	},
	async: false,
	response: 'text',
	success: function (data) {
	    res.innerHTML = data;
	}
    });
    return 0;
}

function deleteName() {
    var del = document.getElementById('delName').value;
    var tst = document.getElementById('nameTest').value;
    var res = document.getElementById('result');

    if (del != tst) {
	res.innerHTML = 'Имена не совпадают. Удаление не удалось.';
    } else {
	jQuery.ajax({

	    type: 'post',
	    url: '/wp-content/themes/typecore-master/js/glo/ajax/admin.php',
	    global: false,
	    data: {
		'type': 'deletName',
		'dname': del
	    },
	    response: 'text',
	    success: function (data) {
		res.innerHTML = data;
	    }
	});
    }

}

function deleteAll() {
    var pass = document.getElementById('passDel').value;
    var res = document.getElementById('result');
    if (pass == '123') {
	jQuery.ajax({

	    type: 'post',
	    url: '/wp-content/themes/typecore-master/js/glo/ajax/admin.php',
	    global: false,
	    data: {
		'type': 'deletAll'
	    },
	    response: 'text',
	    success: function (data) {
		res.innerHTML = data;
	    }
	});
    } else {
	res.innerHTML('Введен неверный пароль');
    }
}

function timeTeach() {
    var res = document.getElementById('result');
    //	alert("sdf");
    jQuery.ajax({

	type: 'post',
	url: '/wp-content/themes/typecore-master/js/glo/ajax/admin.php',
	global: false,
	data: {
	    'type': 'timeTeach'
	},
	response: 'text',
	success: function (data) {

	    res.innerHTML = data;
	}
    });
}


function ganreTeach() {
    var res = document.getElementById('result');
    var ganre = document.getElementById('ganre').value;
    var artist = document.getElementById('nameGanre').value;
    var newGanre = document.getElementById('ganre').value;


    if (ganre == 'Новый жанр') {
	newGanre = document.getElementById('newGanre').value;
    }

    if (newGanre == '' || artist == '') {
	res.innerHTML = 'Введите все данные';
    } else {
	ganre = newGanre;
	jQuery.ajax({

	    type: 'post',
	    url: '/wp-content/themes/typecore-master/js/glo/ajax/admin.php',
	    global: false,
	    data: {
		'type': 'ganre',
		'artist': artist,
		'ganre': ganre,

	    },
	    response: 'text',
	    success: function (data) {

		res.innerHTML = data;
	    }
	});
    }
}


function vectorTeach() {
    var res = document.getElementById('result');
    jQuery.ajax({

	type: 'post',
	url: '/wp-content/themes/typecore-master/js/glo/ajax/admin.php',
	global: false,
	data: {
	    'type': 'vector'
	},
	response: 'text',
	success: function (data) {

	    res.innerHTML = data;
	}
    });
}


document.getElementById('files').addEventListener('change', handleFileSelect, false);
var paletteArray, nm, myImage, dominantColor;
var c = 0;

function sleep(ms) {
    ms += new Date().getTime();
    while (new Date() < ms) {
    }
}


function handleFileSelect(evt) {

    var name = document.getElementById('name').value;
    var haveName = document.getElementById('haveName').value;
    var dayTime = document.getElementById('dayTime').value;
    //alert(name);
    var res = document.getElementById('result');

    if (name == '' && haveName == 'Новый художник') {
	res.innerHTML = 'Введите имя';
    } else {

	if (haveName != 'Новый художник') {
	    name = haveName;
	}

	var files = evt.target.files;

	// перебор картинок
	var flag = 0;
	var colorThief = new ColorThief();
	var j = 0;
	for (var i = 0, f; f = files[i]; i++) {
	    if (!f.type.match('image.*')) {
		continue;
	    }
	    var reader = new FileReader();

	    reader.onload = (function (theFile) {
		return function (e) {
		    var span = document.getElementById('miniRes');
		    span.innerHTML = ['<img class="thumbPic" id="myImage0' + '" src="', e.target.result,
			'" title="', escape(theFile.name), '"/>'
		    ].join('');
		    document.getElementById('list').insertBefore(span, null);

		    myImage = document.getElementById('myImage0');

		    dominantColor = colorThief.getColor(myImage);

		    paletteArray = colorThief.getPalette(myImage, 10);
		    nm = name;

		    c = ajaxPal(dominantColor, paletteArray, nm, res, dayTime);

		    j++;
		};

	    })(f);

	    reader.readAsDataURL(f);

	}

    }

}


function ajaxPal(dominantColor, paletteArray, nm, res, dayTime) {
    jQuery.ajax({

	type: 'post',
	url: '/wp-content/themes/typecore-master/js/glo/ajax/admin.php',
	global: false,
	data: {
	    'dominant': dominantColor,
	    'palette': paletteArray,
	    'name': nm,
	    'dayTime': dayTime


	},
	async: false,
	response: 'text',
	success: function (data) {
	    res.innerHTML = data;
	}
    });
    return 0;

}

function deleteName() {
    var del = document.getElementById('delName').value;
    var tst = document.getElementById('nameTest').value;
    var res = document.getElementById('result');

    if (del != tst) {
	res.innerHTML = 'Имена не совпадают. Удаление не удалось.';
    } else {
	jQuery.ajax({

	    type: 'post',
	    url: '/wp-content/themes/typecore-master/js/glo/ajax/admin.php',
	    global: false,
	    data: {
		'type': 'deletName',
		'dname': del
	    },
	    response: 'text',
	    success: function (data) {
		res.innerHTML = data;
	    }
	});
    }

}

function deleteAll() {
    var pass = document.getElementById('passDel').value;
    var res = document.getElementById('result');
    if (pass == '123') {
	jQuery.ajax({

	    type: 'post',
	    url: '/wp-content/themes/typecore-master/js/glo/ajax/admin.php',
	    global: false,
	    data: {
		'type': 'deletAll'
	    },
	    response: 'text',
	    success: function (data) {
		res.innerHTML = data;
	    }
	});
    } else {
	res.innerHTML('Введен неверный пароль');
    }
}

function timeTeach() {
    var res = document.getElementById('result');
    jQuery.ajax({

	type: 'post',
	url: '/wp-content/themes/typecore-master/js/glo/ajax/admin.php',
	global: false,
	data: {
	    'type': 'timeTeach'
	},
	response: 'text',
	success: function (data) {

	    res.innerHTML = data;
	}
    });
}


function ganreTeach() {
    var res = document.getElementById('result');
    var ganre = document.getElementById('ganre').value;
    var artist = document.getElementById('nameGanre').value;
    var newGanre = document.getElementById('ganre').value;


    if (ganre == 'Новый жанр') {
	newGanre = document.getElementById('newGanre').value;
    }

    if (newGanre == '' || artist == '') {
	res.innerHTML = 'Введите все данные';
    } else {
	ganre = newGanre;
	jQuery.ajax({

	    type: 'post',
	    url: '/wp-content/themes/typecore-master/js/glo/ajax/admin.php',
	    global: false,
	    data: {
		'type': 'ganre',
		'artist': artist,
		'ganre': ganre,

	    },
	    response: 'text',
	    success: function (data) {

		res.innerHTML = data;
	    }
	});
    }
}


function vectorTeach() {
    var res = document.getElementById('result');
    jQuery.ajax({

	type: 'post',
	url: '/wp-content/themes/typecore-master/js/glo/ajax/admin.php',
	global: false,
	data: {
	    'type': 'vector'
	},
	response: 'text',
	success: function (data) {

	    res.innerHTML = data;
	}
    });
}


document.getElementById('files').addEventListener('change', handleFileSelect, false);