const debounce = (func, wait) => {
    let timeout;

    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };

        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
};


let waitResponse = (field, urlCode, id) => debounce(function() {
    let url = urlCode + "/" + document.getElementById(field).value + "/" + id;

    fetch(url, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }).then((response) =>
        response.json()
    ).then(function(data) {
        if (data.success) {
            switch (field) {
                case "TI_ID":
                    $("#error_code").css("display", "none");
                    break;
                case "IN_ID":
                    $("#error_code").css("display", "none");
                    break;
                case "IN_LinTel":
                    $("#error_phone").css("display", "none");
                    break;
                case "IN_IMEI":
                    $("#error_imei").css("display", "none");
                    break;
            }
            // $("#guardar").attr("disabled", false);

        } else {
            switch (field) {
                case "TI_ID":
                    $("#error_code").css("display", "block");
                    break;
                case "IN_ID":
                    $("#error_code").css("display", "block");
                    break;
                case "IN_LinTel":
                    $("#error_phone").css("display", "block");
                    break;
                case "IN_IMEI":
                    $("#error_imei").css("display", "block");
                    break;
            }

            // $("#guardar").attr("disabled", true);
        }
        validateForm();
    });
}, 250);