function initStall(){
    if(_section == 'strattasmaster'){
        $('strattasmaster_extensions').update($('strattasmaster_extensions_table').innerHTML)
    }
    if(_section == 'ststore'){
       $('ststore_extensions').update($('strattasmaster_store_response').innerHTML)
    }
}
Event.observe(window, 'load', function() {
   initStall();
});
;
