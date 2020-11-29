$( function(){

    $(document).on('change', '#record_type', function(){
        let value = $(this).val(),
            form = $(this).parents('form');

            $.ajax({
                url         : `${App.basePath}${App.controller}/changeType`,
                method      : 'POST',
                data        : {
                    type : value,
                    service_id : App.id
                },
                complete    : ( response ) => {
                    
                    result = response.responseText;
                    
                    let formcontent_html = $(result).find('form').html()

                    form.html( formcontent_html );
                }
            })

    });
    
    $(document).on('submit', 'form.ajax', function( event ){
        event.preventDefault();

        let form = $(this);

        $.ajax({
            url         : form.attr('action'),
            method      : form.attr('method'),
            data        : form.serialize() + `&service_id=${App.id}`,
            complete    : ( response ) => {
                let result = null;

                if ( result = response.responseText )
                {
                }
                if ( result = response.responseJSON )
                {
                    if ( result.type )
                    {
                        if( result.type === 'success' )
                        {
                            form.find('input').val('');
                            form.find('select[selected]').attr('selected',false);
                        }
                        alert( result.message );
                    }
                    else
                    {
                        if ( result.content )
                            form.find('[name="content"]').parent().append(`<small class="text-danger">${result.content[0]}</small>`);
                        if ( result.prio )
                            form.find('[name="prio"]').parent().append(`<small class="text-danger">${result.prio[0]}</small>`);
                        if ( result.name )
                            form.find('[name="name"]').parent().append(`<small class="text-danger">${result.name[0]}</small>`);
                        if ( result.ttl )
                            form.find('[name="ttl"]').parent().append(`<small class="text-danger">${result.ttl[0]}</small>`);
                        if ( result.port )
                            form.find('[name="port"]').parent().append(`<small class="text-danger">${result.port[0]}</small>`);
                        if ( result.weight )
                            form.find('[name="weight"]').parent().append(`<small class="text-danger">${result.weight[0]}</small>`);
                    }
                }

            }
        })
    });

    $( document ).on('click', '.delete', function( event ){
        event.preventDefault();

        let btn = $(this),
            record_id = btn.data('record-id');

        $.ajax({
            url: `${App.basePath}${App.controller}/delete/`,
            method: 'POST',
            data:{
                service_id : App.id,
                record_id : record_id,

            },
            complete : ( response ) => {

                let result = null;
                
                if ( result = response.responseJSON )
                    alert( result.message );

                if ( result = response.responseText )
                    $( '.table' ).html( $(result).find('.table').html() );
                    
            }
        })
    })
})