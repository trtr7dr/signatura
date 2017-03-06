  function sleep(time) {
  	return new Promise((resolve) => setTimeout(resolve, time));
  }
  
  
  
  
  function handleFile(evt) {
	
	//var name = document.getElementById('name').value;
	var res = document.getElementById('result');
	res.innerHTML = ('<img src="https://artrue.ru/wp-content/themes/typecore-master/img/load.gif">');
	//console.log(res);
	
	
	
	var files = evt.target.files;
	
	    // перебор картинок
	    var flag = 0;
	     var colorThief = new ColorThief();
	     var j = 0;
	    for (var i = 0, f; f = files[i]; i++) {
	      if (!f.type.match('image.*')) {
		      continue;
	      }
		  var reader = new FileReader();

		  
	      reader.onload = (function(theFile) {
	        return function(e) {
	          var span = document.getElementById('miniRes');      
	         // var span = document.createElement('span');
	          span.innerHTML = ['<img class="thumbPicSig" id="myImage0" src="', e.target.result,
	                            '" title="', escape(theFile.name), '"/>'].join('');          
	          document.getElementById('list').insertBefore(span, null);
	          
	          
	          
	          myImage = document.getElementById('myImage0');
	          
	          
	          var x = myImage.src;
	         // console.log(e.target.result);
	          document.getElementById("miniRes").style.backgroundImage = "url('"+e.target.result+"')";
	           document.getElementById("miniRes").style.backgroundSize = "cover";
	          

			  
			  sleep(500).then(() => {
				dominantColor = colorThief.getColor(myImage);
				//console.log('img=' );
				//console.log(myImage.src);
			    paletteArray = colorThief.getPalette(myImage, 8);
			  	c = ajaxPal(dominantColor,paletteArray,res,myImage.src);
			  });

			
			  j++;
	        };
	        
	      })(f);
	      reader.readAsDataURL(f);
	    }
		

    }
  
  
  
  function ajaxPal(dominantColor,paletteArray,res,img){
	  
	  var res = document.getElementById('result');
	  	  
	  jQuery.ajax({
			    
				type:'post',
				url:'/wp-content/themes/typecore-master/js/glo/ajax/ajax_methods.php',
				global: false,
				data:{'dominant': dominantColor,
				      'palette': paletteArray,
				      'img': img,
				      'flag': 'sig'
				  
				},
				async: false,
				response:'text',
				success:function (data) {
					res.style.backgroundColor = "#eee";

					var source = JSON.parse(data);
		
					var r = '<table>';
					
						r += '<tr><td style="border: 1px black solid;"></td>';
							for(var j = 0; j < source['alg'].length; j++){
								r += '<td style="border: 1px black solid;">'+source['alg'][j]+'</td>';
							}
						r += '</tr>';
					
					
						for(var i = 0; i < source['ganre'].length; i++){
							r += '<tr><td style="border: 1px black solid;">'+source['ganre'][i]+'</td>';
							
								for(var j = 0; j < source['alg'].length; j++){
									r += '<td>'+source['res'][source['alg'][j]][i]+'</td>';
								}
							r += '</tr>';
						}
					
					
					r += '</table>';
					res.innerHTML = r;

				}
	  });
	    return 0;
	  
  }

 



document.getElementById('files').addEventListener('change', handleFile, false);
