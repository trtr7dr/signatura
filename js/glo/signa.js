  function sleep(time) {
  	return new Promise((resolve) => setTimeout(resolve, time));
  }
  
  
  
  
  function handleFile(evt) {
	
	//var name = document.getElementById('name').value;
	var res = document.getElementById('result');
	res.innerHTML = ('<img src="https://artrue.ru/wp-content/themes/typecore-master/img/load.gif">');
	//console.log(res);
	var faceLoad = document.getElementById('resultFace');
	faceLoad.style.background = "white";
	faceLoad.innerHTML = ('<img src="https://artrue.ru/wp-content/themes/typecore-master/img/load.gif">');
	
	
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
			  
			  
			  
			  sleep(500).then(() => {
		      //alert('faceScan');
		      	faceScan();
			});
			
			  j++;
	        };
	        
	      })(f);
	      reader.readAsDataURL(f);
	    }
		

    }
  
  
  function faceScan(){
			  jQuery(function($) {
				  $('#myImage0').faceDetection({
				  complete: function (faces) {
				    var f = faces;
	
				    
				     var height = $(this).height();
					 var width = $(this).width();
					
	
				   // console.log(f);
				    face = faceSend(f,height,width);
				    
				  }
				  });
				        
			  });
		  }
  
  
  function faceSend(f,h,w){
	  var res = document.getElementById('resultFace');
	  //console.log('<-'+f+'->');
	  //res.innerHTML = ('<img src="https://artrue.ru/wp-content/themes/typecore-master/img/load.gif">');
	  jQuery.ajax({
			    
				type:'post',
				url:'/wp-content/themes/typecore-master/js/glo/ajax/ajax.php',
				data:{'face': f,
					  'height': h,
					  'width': w,
				      'flag': 'face'
				},
				response:'text',
				success:function (data) {
					res.style.background = "black";
					res.innerHTML = data;
				}
	  });

  }
  
  
  function ajaxPal(dominantColor,paletteArray,res,img){
	  
	  var res = document.getElementById('result');
	  	  
	  jQuery.ajax({
			    
				type:'post',
				url:'/wp-content/themes/typecore-master/js/glo/ajax/ajax.php',
				global: false,
				data:{'dominant': dominantColor,
				      'palette': paletteArray,
				      'img': img,
				      'flag': 'sig'
				  
				},
				async: false,
				response:'text',
				success:function (data) {
					var source = JSON.parse(data); 
					res.style.backgroundColor = "#eee";
					
					var table = '<table>' + source['table'].substr(5,source['table'].length);
					
					res.innerHTML = table;
					res.innerHTML += source['artist'];

				}
	  });
	    return 0;
	  
  }

 
 
function test(){
	  var res = document.getElementById('result');
	  jQuery.ajax({
			    
			type:'post',
			url:'/wp-content/themes/typecore-master/js/glo/ajax/ajax.php',
			global: false,
			data:{'type': 'test'},
			response:'text',
			success:function (data) {

				res.innerHTML = data;
	        }
	    });
  }
  




document.getElementById('files').addEventListener('change', handleFile, false);
