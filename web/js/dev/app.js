$( document ).ready(function() {
    $(".js-attend-toggle").click(function(e) {
        e.preventDefault();
       // $('.js-attend-toggle').unbind('click');

        var $anchor = $(this);
        var url = $(this).attr('href')+'.json';
        $.post(url, function(data, nul){

                if(data.attending == true) {
                    var message = 'See you there';
                }
                else {
                    var message = 'We\'ll miss you';
                }
            $anchor.after('<span class="label label-default">'+message+'</span>');
            $anchor.hide();

        }).fail(function() {
                console.log( "error" );
            });
      //  $('.js-attend-toggle').bind('click');
    });
});