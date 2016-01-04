/**
 * Archivo de funciones comunes utilizadas en los formularios de la aplicacion
 */
$('.upper-case').keyup(function () {
    var text = $(this).val();
    text = text.toUpperCase();
    $(this).val(text);
});

$('.lower-case').keyup(function () {
    var text = $(this).val();
    text = text.toLowerCase();
    $(this).val(text);
});

$('.title-case').keyup(function () {
    var text = $(this).val();
    text = text.replace(/\w\S*/g, function (txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1);
    });
    $(this).val(text);
});

/*
 * Clase para validar solo numeros en el evento key down
 */
$(document).on("keydown", '.only_numbers', function (e)
{
    var key;
    if (window.event) {
        key = window.event.keyCode;   /*IE*/
    } else {
        key = e.which;                /*firefox*/
    }
    if (!((key >= 48 && key <= 57) || (key >= 96 && key <= 105) || key == 8 
            || key == 9 || key == 0 || key == 46 || key == 59 || key == 39 || key == 37)) {
        return false;
    }
    else {
        return true;
    }
});

/**
 * Comas para todos los campos numericos
 */
$(document).on("keyup", '.input_number', function ()
{
    var val = $(this).val();
    val = val.replace(/[\D]+/g, "");
    val = val.replace(/$0+/g, "");
    if (val.length > 0) {
        $(this).val($.number(val));
    }
});


