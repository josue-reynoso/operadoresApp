window.onload = function() {
    'use strict';


    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })

    document.getElementById("hastaDate").addEventListener("change", function() {
        console.log(this.value);
        document.getElementById("desdeDate").setAttribute("max", this.value);
    });

}