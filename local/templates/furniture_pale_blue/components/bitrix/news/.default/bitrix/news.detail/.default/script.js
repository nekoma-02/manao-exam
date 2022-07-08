$(document).ready(function () {
    $('#btn-send-claim').on('click', function () {
   
        var id = $("#news-id").val();
        console.log(id);

        BX.ajax.runComponentAction('bitrix:news.detail',
							'sendClaim', { // Вызывается без постфикса Action
								mode: 'ajax',
								data: {
									idNews: id
								}, // ключи объекта data соответствуют параметрам метода
							})
						.then(function(response) {
							if (response.status === 'success') {
								var textElem = document.getElementById("ajax-report-text");
                                textElem.innerText = "Ваше мнение учтено!";
                                console.log(response);
							}
						});
    });

});