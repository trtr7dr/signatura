  function sleep(time) {
  	return new Promise((resolve) => setTimeout(resolve, time));
  }
  
  
  
  
 function handleFile(evt) {
	
	var gall = document.getElementById('artistList');
	var res = document.getElementById('result');
	
	res.innerHTML = 'Идет загрузка. Пожалуйста, подождите.';
	gall.innerHTML = ('<img src="https://artrue.ru/wp-content/themes/typecore-master/img/load.gif">');
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
			  	artistList(paletteArray);
			  	faceScan();
			  });

			
			  j++;
	        };
	        
	      })(f);
	      reader.readAsDataURL(f);
	    }
		
		

    }

  function artistList(paletteArray){
	  
	  var res = document.getElementById('artistList');
	  //res.innerHTML = ('<img src="https://artrue.ru/wp-content/themes/typecore-master/img/load.gif">');
	  
	  jQuery.ajax({
			    
				type:'post',
				url:'/wp-content/themes/typecore-master/js/glo/ajax/ajax_methods.php',
				global: false,
				data:{'flag': 'artist', 'palette': paletteArray},
				async: true,
				response:'text',
				success:function (data) {
					res.style.backgroundColor = "#ddd";
					var source = JSON.parse(data);
					//console.log(source);
					res.innerHTML = '<div class="artList">' + source['artist'] + '</div>';
					
					res.innerHTML += source['mla']
				}
	  });
	    return 0;
	  
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
				
				//res.innerHTML += data;
					var source = JSON.parse(data);
					//console.log(source);					
					res.innerHTML = get_ganre(source);
					
				}
	  });
	    return 0;
	  
  }


function get_table_color(c, step){
	var color = ["#ff0000", "#ffae00", "#ff0000", "#00ffff", "#00ae00", "#0000ff"];
	//echo(step.' '.Math.round(c*10).'<br>');
		if(step == 1){
			if(Math.round(c*10) > 2){
				return get_table_color(c, 2);
			}else{
				return color[0];
			}
				
		}
		else{
			if(Math.round(c*10) > step + 2){
				return get_table_color(c, step + 2);
			}
			else{
				return color[Math.round(step/2)];
			}
		}
	}	
		
	
	function get_ganre(v){
		
		var color = '';
		var res = '<table>';
		
		for(var i = 0; i < v.length; i++){
			res += '<tr>';
			res += '<td style="width:5%;">' + v[i]['ganre'] + '</td>';
			res += '<td style="width:5%;">' + v[i]['num'].toPrecision(3) + '</td>';
			
			color = get_table_color(v[i]['num'],1);
			cnum = Math.round(v[i]['num']*10);
			//console.log(cnum);
			for(var s = 0; s < cnum; s++){
				res += '<td style="background:' + color + '; color: '+ color +' ">.</td>';
			}
			for(var s = 0; s < 10 - cnum; s++){
				res += '<td style="color: #eee">.</td>';
			}
			
			res += '</tr>';
		}
		
		return res + '</table>';
		
	}
	
	function faceScan() {
		var res = document.getElementById('resultFace');
		res.style.backgroundColor = "black";
		var f;
	    jQuery(function ($) {
		$('#myImage0').faceDetection({
			    complete: function (faces) {
					var str = '';
					if(faces.length == 1){
						str = "Вероятно, на изображении расположен один человек";
					}
					if(faces.length > 1){
						str = "Вероятно, на изображении расположена группа людей";
					}
					
					res.innerHTML = str;	
		    	}
			});
		});
		
	}


document.getElementById('files').addEventListener('change', handleFile, false);
