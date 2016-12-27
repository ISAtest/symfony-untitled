$(document).ready(function(){

   /*
     change currencies
     */


    $(".currency").on('click',function() {
        var curr= $(this).attr('id').toUpperCase();
        var org=x.organizations;
        org.forEach (function(item, index) {
            if (typeof item.currencies[curr] == "undefined"){
                item.currencies[curr]={"ask":"empty", "bid":"empty"};
            }

            $("#"+index).children("td.ask").css({"color":"red"}).html( '<td class="ask">'+item.currencies[curr].ask+'</td>');
            $("#"+index).children("td.bid").css({"color":"orange"}).html( '<td class="bid">'+item.currencies[curr].bid+'</td>');
$("h1").html("<h1>"+curr+"<h1>");
        });

    });
});
