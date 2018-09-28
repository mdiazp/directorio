can_scroll = false;

function success_function(data,status){
    console.log("return");
    //console.log(data.filter);

    var show_data = $('#dynamic-section-data');

    if( status == 'success' ){
        if( data.status && data.op == show_data.attr('op') ){
            var items = data.data;  
            
            for (var i = 0; i < items.count && data.op == show_data.attr('op'); i++) {
                var item = items[i];

                var username = item.samaccountname ? item.samaccountname[0] : '...';
                var displayname = item.displayname ? item.displayname[0] : '...';
                var mail = item.mail ? item.mail[0] : '...';
                var physicaldeliveryofficename = item.physicaldeliveryofficename ? item.physicaldeliveryofficename[0] : '';
                var description = item.description ? item.description[0] : '';

                var html_item = 
                    '<div class="col-md-5" style="margin: 20px 40px;">' +
                        '<div class=\'media-left\'>' +
                            '<a href=\'#\'>' +
                                //'<img class=\'img-circle\' margin=\'10\' width=\'100\' src=\'http://10.2.24.131/api/approved/' + username + '\'> </img>' +
                                '<img class=\'img-circle\' width=\'120\' src=\'/img/default-avatar.jpeg\'> </img>' +
                            '</a>' +
                        '</div>' +
                        '<div class=\'media-body\'>' +
                            '<br/>' +
                            '<h4 class=\'media-heading\'>' + displayname + '</h4>' +
                            '<h6 class=\'media-heading\'>' + mail + '</h6>' + 
                            ( physicaldeliveryofficename != ''  ? '<h6 class=\'media-heading\'>' + physicaldeliveryofficename + '</h6>' : '' ) +
                            ( description != ''  ? '<h6 class=\'media-heading\'>' + description + '</h6>' : '' ) +
                        '</div>' +
                    '</div>';

                if( data.op == show_data.attr('op') ){
                    show_data.append(html_item);
                }
            }
            
            if( data.op == show_data.attr('op') ){
                $('#loading').hide();
                show_data.attr('moreresults',items.more_results);
                show_data.attr('cookiepage',items.cookie_page);
                show_data.attr('searchstate',items.search_state);
            }
        }
        else{
            //show_data.attr('cookiepage','FIN');
        }
    }
    else{
        //show_data.attr('cookiepage','FIN');
    }

    if( data.op == show_data.attr('op') ){
        can_scroll = true;
    }
}

function call_directorio(__op){
    
    $.getJSON('/search', 
        {
            ldap_consulta: {
                search_display_name: $('#search_nombre').val(),
                search_state: $('#dynamic-section-data').attr('searchstate'),
                cookie_page: $('#dynamic-section-data').attr('cookiepage'),
                op: __op
            }
        },
        success_function
    );

    console.log('call');
}

$(document).ready(function(){

    $('#search_nombre').keyup( function(e){
        if( $('#search_nombre').val() != $('#search_nombre').attr('old-text') ){
            $('#search_nombre').attr('old-text', $('#search_nombre').val() );
            $('#loading').hide();
            
            $('#dynamic-section-data').empty();
            $('#dynamic-section-data').attr('moreresults','Yes');
            $('#dynamic-section-data').attr('searchstate','');
            $('#dynamic-section-data').attr('cookiepage','');
            $('#dynamic-section-data').attr('op', parseInt( $('#dynamic-section-data').attr('op') ) + 1 );
            can_scroll = false;


            if( $('#search_nombre').val() != '' ){
                call_directorio( $('#dynamic-section-data').attr('op') );
                $('#loading').show();
            }
        }
    } );

    var win = $(window);
    win.scroll(function() {
        // End of the document reached?
        if ($(document).height() - win.height() <= win.scrollTop() 
            && $('#dynamic-section-data').attr('moreresults') == 'Yes' 
            && can_scroll) {
            can_scroll = false;
            $('#loading').show();
			call_directorio( $('#dynamic-section-data').attr('op') );
		}
    });

    $('#loading').hide();
      
});
