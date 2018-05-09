/*
// Ajoute une ligne de formulaire
$(function(){
	var indice = 0;
	$("#maj-button").click(function(){ 
		indice++;
		var message = '<div class="row"><table class="table">';
		message += '<tr><td> <input type="text" class="form-control" id="modifchamp-'+indice+'" placeholder="Entrer le champ à modifier">	</td>';
		message += '<td><div id="distinct">';
		message += '<input type="text" list="distinct_list-'+indice+'" name="" class="form-control">';
		message += '<datalist id="distinct_list-'+indice+'"></datalist>';
		message += '</div></td>';
		message += '<td class="text-center" style="padding-top:15px"><p> Doit être égal à </p></td>';
		message += '<td> <input type="text" id="field-to-modify-'+indice+'" class="form-control" placeholder=""> </td>';
		message += '</tr></table></div>';
		$("#table-0").append( message );
		
		var search;
		$("#modifchamp-"+indice).keyup(function(){
			search = $(this).val();		
			
				$.ajax({
			    url : 'maj.php',
			    type : 'GET',
			    data : 'champ=' + search,
			    success : function(response, statut) { 
			    	var json = JSON.parse(response);
			    	console.log(json);
			    	//$("#debug").append(response);
			    	for (i=0;i<json.length;i++) { 
			       	$("datalist#distinct_list-"+indice).append("<option value='"+json[i]+"''></option>");
			      }
			   	},
			   	error : function(resultat, statut, erreur){
			   		$("#debug").append(resultat);
			   	}
			  });
			
		});

	});
});*/

// Rempli la liste de références
$(function(){
	var search;
	$("#modifchamp-0").change(function(){
		search = $(this).val();		
		//console.log(search);	
		
			$.ajax({
		    url : 'maj.php',
		    type : 'GET',
		    data : 'champ=' + search,
		    success : function(response, statut) { 
		    	var json = JSON.parse(response);
		    	console.log(response);
		    	for (i=0;i<json.length;i++) { 
		       	$("datalist#distinct_list-0").append("<option value='"+json[i]+"''></option>");
		      }
		   	},
		   	error : function(resultat, statut, erreur){
		   		$("#debug").append(resultat);
		   	}
		  });
		
	});

	$("#maj").click(function(){

			var field, valtomodify, newval;  
			
			field 			= 	$("#modifchamp-0").val();
			valtomodify = 	$("#distinct-0").val();
			newval		  = 	$("#newvalue-0").val();
			
			if(field != "" && valtomodify != "" && newval != ""){
			console.log(field, valtomodify, newval);
				$.ajax({
			    url : 'maj.php',
			    type : 'GET',
			    data : 'field=' + field+'&valtomodify='+valtomodify+'&newval='+newval,
			    success : function(response, statut) { 
			    	console.log(response);
			    	if(response == "<mark>updated with success ! </mark>" ){
			    		$("#debug").append(response);
			    		$("#modifchamp-0").val("");
							$("#distinct-0").val("");
							$("#newvalue-0").val("");
							$("#debug").val("");
							$("#distinct_list-0 option").each( function () {
								$(this).val("");
							})
			    	}
			   	},
			   	error : function(resultat, statut, erreur){
			   		$("#debug").append(resultat);
			   	}
			  });
			}
			else alert( "Merci d'entrer des valeurs dans chaque champs"	);
	});

	$("#vider").click(function(){
		$("#debug").html("");
		$("#modifchamp-0").val("");
		$("#distinct-0").val("");
		$("#newvalue-0").val("");
		$("#distinct_list-0 option").each(function (){
			$(this).val("");
		})
		
	})

});
