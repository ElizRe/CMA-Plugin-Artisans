( function( $ ){

  $('#s2').change( function(){

    var family_id = this.value;
    $('#ss2').html('<p><em>Chargement en cours</em></p>');

    $.ajax({
      type : 'post',
      dataType : 'html',
      url : artisanjs_globals.ajax_url,
      data : {
        action: 'artisan_form_ajax_activity',
        _ajax_nonce: artisanjs_globals.nonce,
        family: family_id
      },
      success: function( response ) {
         $('#ss2').html(response);
      },
    })

  });

  $('#s5').change( function(){

    var district_id = this.value;
    $('#ss5').html('<p><em>Chargement en cours</em></p>');

    $.ajax({
      type : 'post',
      dataType : 'html',
      url : artisanjs_globals.ajax_url,
      data : {
        action: 'artisan_form_ajax_cantons',
        _ajax_nonce: artisanjs_globals.nonce,
        district: district_id
      },
      success: function( response ) {
         $('#ss5').html(response);
      },
    })

  });

} )( jQuery );
