(function($) {
    
 
$('#edit-submit').on('click',doAjax);
$('#edit-reset').on('click',function(){
    $('#edit-principal').val('');
    $('#edit-addition').val('');
    $('#edit-num-years').val('');
});


function doAjax(){
    
    var suma = $('#compound-interest-calc #edit-principal').val();
    var contributia = $('#compound-interest-calc #edit-addition').val();
    var perioada = $('#compound-interest-calc #edit-num-years').val();

    data = {
        action: 'interest',
        suma: suma,
        contributia: contributia,
        perioada: perioada,
    }
    // console.log(data);
    $.ajax({  
        url: WPR.ajax_url, 
        type: 'GET', 
        data: data,
        success: function(response){
            
            if (response) {
                
                $('#results_container').empty();
                var html = `
                    <div class="results-container__inner">
                    <h2> Rezultatul calculului:</h2>
                    <h3 class="calculator__results-amount">In <span class="amount">${response['perioada']}</span> ani, o sa ai suma de <span class="amount">${response['total'].toFixed(2)} RON</span>
                    </h3>
                    </div>
                `;
                 $('#results_container').append(html);
                
             }
        }
    })  
}
} ) (jQuery); 