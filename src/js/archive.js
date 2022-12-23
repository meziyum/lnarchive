
var site_url = window.location.origin;
var json_request_url = site_url.concat("/wordpress/wp-json/wp/v2/");

document.getElementById("filter-apply").addEventListener('click', function() {

    var tax_list = [ 'novel_status', 'language', 'publisher', 'writer', 'illustrator', ];

    var query="";

    for( var i=0; i<tax_list.length; i++ ){

        var tax_name = tax_list[i];

        if(document.getElementById(tax_name+'_filter_input').value){
            query+=tax_name+"="+document.getElementById('option_'+document.getElementById(tax_name+'_filter_input').value).text+"&"
        }
    }

    query=query.substring(0,query.length-1);
    console.log(query)

    fetch( json_request_url+'novels?_embed&'+query+'&_fields=id,link,_links') //Fetch the JSON data
            .then( res => res.json()) //The fetch API Response
            .then( data => { //The fetch api data
                console.log(json_request_url+'novels'+query+'&_fields=id,link,_links');
                console.log(data)

                for( var j=0; j<data.length; j++){
                    console.log(data[j])
  
                }
            })
});

function Novel( props ){

}