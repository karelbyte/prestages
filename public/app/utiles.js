function alertas(selector, response, retorno) {
    var mjsdom = $(selector);
    if (response.codigo !== 500) mjsdom.removeClass('errortext').addClass('alert-success_fix'); else mjsdom.removeClass('alert-success_fix').addClass('errortext');
    mjsdom.html(response.msj);
    mjsdom.fadeIn().delay(2000).fadeOut();
    if (retorno !== null) $(retorno).select();
}


function rangoutil(totalpage, currentpage){
    var star,  end, total;
    total= (totalpage !== null )? parseInt(totalpage) : 0;
    if (total <= 5)
    {
        star = 1;
        end = total+1;
    } else{
        if ( currentpage <= 2) {
            star = 1;
            end = 6;
        } else if (currentpage + 2 >=  total) {
            star =  total - 5;
            end = total + 1;
        } else {
            star=  currentpage - 2;
            end =  currentpage + 3;
        }

    }
    return  _.range(star, end);
}


function setorders(field, idfs, scope) {
    if(scope.order.field !== field) {
        $('#'+scope.order.idfs).removeClass('fa-sort-down fa-sort-up').addClass('fa-sort');
        scope.order.idfs =  idfs;
        scope.order.field = field;
        scope.order.type = scope.order.type == 'desc' ? 'acs': 'desc';
        $('#'+idfs).removeClass('fa-sort-down fa-sort-up').addClass(scope.order.type == 'desc' ? 'fa-sort-down': 'fa-sort-up');

    } else {
        if (scope.order.type == 'desc'){
            $('#'+idfs).removeClass('fa-sort-down fa-sort-up').addClass('fa-sort-up');
            scope.order.type = 'asc';
        } else {
            $('#'+idfs).removeClass('fa-sort-down fa-sort-up').addClass('fa-sort-down');
            scope.order.type = 'desc';
        }
    }
}
