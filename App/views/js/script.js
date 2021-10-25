
jQuery(document).ready(() => {
    loadTableInformation();
})

function loadTableInformation(page = 1) {

    jQuery(".table-body").html("");
    var settings = {
        url: "http://localhost/api/list-cars/?page=" + page,
        method: "GET",
    }
    jQuery.ajax(settings).done(function (response) {
        var html = "";
        response = JSON.parse(response);
        response.cars.map((res) => {
            html += `<tr><td>${res.id}</td><td>${res.owner_name}</td><td>${res.color_name}</td><td>${res.model}</td><td>${res.plate}</td><td>${res.inserted_at}</td><td>${res.updated_at}</td><td><td><button class="btn btn-light" onClick="editCars(${res.id})" value="${res.id}"  data-toggle="modal" data-target="#modal">Editar</button></td><td><button class="btn btn-danger" onClick="deletCars(${res.id})" value="${res.id}">Deletar</button></td></tr>`
        })
        if (response.total_page >= 1) {

            generatePagination(response.total_page, page)
        }
        jQuery(".table-body").append(html);
    }).fail(function (response) {
        (response)
    });

}

function generatePagination(pages, page = 1) {
    var html = "";

    for (i = 1; i <= pages; i++) {
        if (i == page) {
            html += `<li class="page-item active"><button class="page-link" onClick="loadTableInformation(${i})">${i}</button></li>`
        } else {
            html += `<li class="page-item"><button class="page-link" onClick="loadTableInformation(${i})">${i}</button></li>`
        }

    }

    (html)
    jQuery('.pagination').html("");
    jQuery('.pagination').append(html);
}

function deletCars(id) {
    var settings = {
        url: "http://localhost/api/delete-cars",
        method: "DELETE",
        data: JSON.stringify({
            "id": id
        })
    };
    jQuery.ajax(settings).done(function (response) {
        window.location.reload();
    }).fail(function (response) {
        (response)
    });


}
function editCars(id) {
    (id);
    jQuery("#update").show();
    jQuery("#create").hide();

    jQuery("#modalLable").val("Editar Veículo")
    jQuery('#owner-input').val('')

    jQuery('#vehicles-input').val('')

    jQuery('#plate-input').val('')

    jQuery(".group-colors").html("");


    let settings = {
        url: "http://localhost/api/get-car-by-id/?id=" + id,
        method: "GET",
    }
    jQuery.ajax(settings).done(function (response) {
        response = JSON.parse(response);


        jQuery('#owner-input').val(response.owner_name)

        jQuery('#vehicles-input').val(response.model)

        jQuery('#plate-input').val(response.plate)
        jQuery('#color-select').val(response.color_id);

    }).fail(function (response) {
        (response)
    });

    setTimeout(() => {
        settings = {
            url: "http://localhost/api/list-colors",
            method: "GET",
        }
        jQuery.ajax(settings).done(function (response) {
            response = JSON.parse(response);
            // (response);


            var html = "";
            //(jQuery('#color-select').val())
            response.colors.map(res => {
                if (res.id == jQuery('#color-select').val()) {
                    html += `<option value = "${res.id}" selected >${res.color_name}</option>`
                } else {
                    html += `<option value = "${res.id}" >${res.color_name}</option>`
                }


            });
            jQuery(".group-colors").append(html);


        }).fail(function (response) {
            (response)
        });

    }, 800)

    jQuery('#update').on('click', function () {

        settings = {
            url: "http://localhost/api/update-cars",
            method: "PUT",
            data: JSON.stringify({
                "id": parseInt(id),
                "owner": jQuery('#owner-input').val(),
                "color_id": parseInt(jQuery('select[name=group-colors] option').filter(':selected').val()),
                "model": jQuery('#vehicles-input').val(),
                "plate": jQuery('#plate-input').val()
            })
        };
        jQuery.ajax(settings).done(function (response) {
            window.location.reload();
        }).fail(function (response) {
            (response)
        });
    })

}

jQuery('#create-button').on('click', () => {
    jQuery("#update").hide();
    jQuery("#create").show();
    jQuery("#modalLable").html("Criar Veículo")
    jQuery('#owner-input').val('')

    jQuery('#vehicles-input').val('')

    jQuery('#plate-input').val('')

    jQuery(".group-colors").html("");

    let settings = {
        url: "http://localhost/api/list-colors",
        method: "GET",
    }
    jQuery.ajax(settings).done(function (response) {
        response = JSON.parse(response);

        var html = "";
        //(jQuery('#color-select').val())
        response.colors.map(res => {

            html += `<option value = "${res.id}" >${res.color_name}</option>`

        });
        jQuery(".group-colors").append(html);


    }).fail(function (response) {
        (response)
    });


    jQuery('#create').on('click', function () {


        settings = {
            url: "http://localhost/api/create-cars",
            method: "POST",
            data: JSON.stringify({
                "owner": jQuery('#owner-input').val(),
                "color_id": parseInt(jQuery('select[name=group-colors] option').filter(':selected').val()),
                "model": jQuery('#vehicles-input').val(),
                "plate": jQuery('#plate-input').val()
            })
        }
        jQuery.ajax(settings).done(function (response) {
            response = JSON.parse(response);
            if (response.error == undefined) {
                if (jQuery("#msg").hasClass("msg-text-error") == true) {

                    jQuery("#msg").toggleClass('msg-text-error msg-text-success');
                }
                jQuery("#msg").html(response.success)
                window.location.reload();
            } else {
                if (jQuery("#msg").hasClass("msg-text-success") == true) {

                    jQuery("#msg").toggleClass('msg-text-success msg-text-error');
                }
                jQuery("#msg").html(response.error)
            }
        })

    })
})

jQuery("#export-button").on("click", () => {

    var settings = {
        url: "http://localhost/api/get-all-cars/",
        method: "GET",
    }
    jQuery.ajax(settings).done(function (response) {

        ConvertToCSV(response).then(res => generateCsv(res));

        ;
        /* response.cars.map((res) => {
             
         })*/

    })
})

function ConvertToCSV(objArray) {
    return new Promise((resolve, reject) => {


        var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
        var str = '';

        str += "Id;Dono;Id Da Cor;Cor;Carro;Placa;Criado em;Atualizado em;\r\n";
        for (var i = 0; i < array['cars'].length; i++) {

            var line = '';
            for (var index in array["cars"][i]) {
                if (line != '') line += ';'


                line += array["cars"][i][index];
            }

            str += line + '\r\n';
        }
        resolve(str)

    })

}

function generateCsv(csv) {
    var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    if (navigator.msSaveBlob) { // IE 10+
        navigator.msSaveBlob(blob, "teste");
    } else {
        var link = document.createElement("a");
        if (link.download !== undefined) { // feature detection
            // Browsers that support HTML5 download attribute
            var url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", "export-data.csv");
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

}