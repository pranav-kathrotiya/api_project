$(document).ready(function () {
    $('#myTextbox').on('input', function () {
        var sanitizedValue = $(this).val().replace(/[^a-zA-Z]/g, '');
        $(this).val(sanitizedValue);
    });
});

$(document).ready(function () {
    $('#myTextbox1').on('input', function () {
        var sanitizedValue = $(this).val().replace(/[^a-zA-Z]/g, '');
        $(this).val(sanitizedValue);
    });
});

$(document).ready(function () {
    $('#myTextboxNo').on('input', function () {
        var sanitizedValue = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(sanitizedValue);
    });
});

$(document).ready(function () {
    $('#myTextboxNo1').on('input', function () {
        var sanitizedValue = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(sanitizedValue);
    });
});

$(document).ready(function () {
    $('#myTextboxNo2').on('input', function () {
        var sanitizedValue = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(sanitizedValue);
    });
});

$(document).ready(function () {
    $('#myTextbox2').on('input', function () {
        var sanitizedValue = $(this).val().replace(/[^a-zA-Z]/g, '');
        $(this).val(sanitizedValue);
    });
})

$(document).ready(function () {
    $('#myTextboxPinCode').on('input', function () {
        var sanitizedValue = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(sanitizedValue);
    });
});

$(document).ready(function () {
    $('#myTextboxAccountNo').on('input', function () {
        var sanitizedValue = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(sanitizedValue);
    });
});

$(document).ready(function () {
    $('#myTextboxEmail').on('input', function () {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var input = $(this).val();

        if (!emailRegex.test(input)) {
            // Invalid email format
            $(this).addClass('invalid');
        } else {
            // Valid email format
            $(this).removeClass('invalid');
        }
    });
});

