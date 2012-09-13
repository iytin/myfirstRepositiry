<!DOCTYPE html>
<html>
<head>
    <? require_once 'cvconectdb.php';?>
    
    <meta charset="utf-8" />
    <title>Координаты</title>
    
    <link rel="stylesheet" href="css/main.css" />

	<script src="http://code.jquery.com/jquery-1.7.2.js" type="text/javascript"></script>
    <script type="text/javascript" src="jquery.autocomplete.js"></script>
<!-- Подключаем API  карт 2.x  -->
<script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
<script type="text/javascript">
	var cur = null; var myMap;
	function hook(e) {
		e = e || window.event;
		var el = ( e.srcElement || e.target ).parentNode;
		cur = { 'el': el, 'y': e.clientY - el.offsetHeight }
	}
	function move(e) {
		if( !cur )
			return;
		e = e || window.event;
		with( cur )
		{
			var ny = e.clientY - y;
			if( ny < 260 ) ny = 260;
			if( ny > 650 ) ny = 650;
	
			el.style.height = ny  + 'px';
		}
		(e.preventDefault) ? e.preventDefault() : e.returnValue = false;
	}
	document.onmousemove = move;
	document.ondragstart = function()
	{
		return false;
	}
        // Как только будет загружен API и готов DOM, выполняем инициализацию 
        ymaps.ready(init);
      
        function init () {
            var heights=$(window).height();
            $('.close').live('click',function(){
                $('#balun').css('display', 'none');
            })
           var mySerPlacemark = new Array(); 
              var myPlacemark = new Array(); 
            var myMap;var l='';
            $('.resize').mousemove(function(){
               myMap.container.fitToViewport();
            })
  ////*****************
$('#map').mousedown(function(){
    $('#help-visibility').click();
});
            $('body').mouseup(function(){
                if( cur )
                cur = null;
             myMap.container.fitToViewport();
           
                
          
            })
            $('.ccategory').attr('checked',true);
          var mySerCollection=[];
          var mas=[];
            var myCollection=[],active=null;
             var BalloonLayout = ymaps.templateLayoutFactory.createClass("<div>$[properties.body]fff<p><div>33$[properties.metro]</div></div>", {
                     build: function () {
                        var parent = this.getParentElement(),
                            content = "",
                            geoObjects = this.getData().properties.get('geoObjects');
                        for (var i = 0, l = geoObjects.length; i < l; i++) {
                         mas.push(geoObjects[i].properties.get('myid'));
                        }
//                        mas.forEach(function (index,id) { 
//                           alert(index); 
//                        })
                        parent.innerHTML = 'Загрузка...';
                        $.post("obrabotka.php",mas,function(json){
                           parent.innerHTML = 'Загрузили...'; 
                        })
                        
                    }
                });
                cluster = new ymaps.Clusterer({clusterBalloonContentBodyLayout: BalloonLayout});
           
            var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
                    '<div>$[properties.body]<p><div>$[properties.metro]</div></div>'
                );
                     cluster.options.set({
                            gridSize:50
                        });
            $('#map-visibility').bind(
                'click', function vasia () {
				
					if ($(".open").css('height')=="0px"){
					$(".open").animate({
						"height": "+=550"
					  }, 1000,function() {                
                                             if(l==''){
                                                  l=getdata('all');
                                             }
                                             myMap.container.fitToViewport();
                                               $("#map-visibility").text('Скрыть карту');
                                               
                                        });					 
					}
                                        if (!active) {
                                  active=true;
                                  
                    if (!myMap) {                        
                         // Создание экземпляра карты и его привязка к контейнеру с
                           // заданным id ("map")
                            myMap = new ymaps.Map('map', {
                            // При инициализации карты, обязательно нужно указать
                            // ее центр и коэффициент масштабирования
                           center: [55.753559,37.609218], // центр карты установка
                            zoom: 12
                    }); // элемента управления и его параметры.
                            myMap.controls
                            // Кнопка изменения масштаба
                            .add('zoomControl')
                            // Список типов карты
                            .add('typeSelector', { top : 5, left : 128 })
                        // Стандартный набор кнопок
                            .add('mapTools');
                            razvert();
                            myMap.zoomRange.get(myMap.getCenter()).then(function (range) {//функция для поиска существующего зума
                                if (myMap.getZoom() > range[1] ) {
                                    myMap.setZoom(range[1]); 
                                } 
                            }); 
                            

myMap.events.add('click', function(){
    $('#help-visibility').click();
})

///************** изминения зума при смене типа карты
                        myMap.events.add("typechange", function (e) { 
                            myMap.zoomRange.get(myMap.getCenter()).then(function (range) {
                                if (myMap.getZoom() > range[1] ) {
                                myMap.setZoom(range[1]); 
                                } 
                            }); 
                        });
                    }                    
                 }else {
                            $(".open").animate({
                            "height": "0px"},
                        function() {
                            $("#map-visibility").text('Показать карту снова');                       
                            active=null;
                        });
                    }
                }
            );
			
			/*---------------------------------------------------------------------------*/
			$('#help-visibility').live('click',function(){
				$(".block_help").children('.content').animate({"height": "0","opacity": "0"},500)		
				$(".block_help").animate({"height": "0", "opacity": "0"},1500,
                                 function() {
                                     $(".block_help").css('display','none') 
                                 })
				.css('overflow','visible');
				$("#help-visibility").text('Показать подсказки');
				$("#help-visibility").attr('id','help-openlity');
			});
			$('#help-openlity').live('click',function(){
                                $(".block_help").css('display', 'block');
				$(".block_help").children('.content').animate({"opacity": "1"},2000)	
				$(".h_one").animate({"height": "61px","opacity": "1"},1000)
				.css('overflow','visible');
				$(".h_two").animate({"height": "45px","opacity": "1"},1000)
				.css('overflow','visible');
				$(".h_three").animate({"height": "93px","opacity": "1"},1000)
				.css('overflow','visible');
				$(".h_four").animate({"height": "110px","opacity": "1"},1000)
				.css('overflow','visible');
				$(".h_five").animate({"height": "76px","opacity": "1"},1000)
				.css('overflow','visible');	
				$("#help-openlity").text('Скрыть подсказки');
				$("#help-openlity").attr('id','help-visibility');
			});
			/*---------------------------------------------------------------------------*/	
			
function razvert(){
   // Создание кнопки определения места.
                button = new ymaps.control.Button({
                    data : {
                        content : '<img src="img/0.png" style="margin-top:6px;"/>',
                        title : 'Поиск местонахождения'
                    }
                });

            // Если кнопка активна, то карта разворачивается во весь экран, иначе - 600x400.
            button.events
         .add('click', function (e) {
            if(button.isSelected()) {                      
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                    function (position) {
                        createGeoI(position.coords.latitude,position.coords.longitude); 
                         myMap.zoomRange.get(position.coords.latitude,position.coords.longitude).then(function (range) {//функция для поиска существующего зума
                                if (myMap.getZoom() > range[1] ) {
                                    myMap.setZoom(range[1]); 
                                } 
                            });
                    });
                }else {
                    alert("Geolocation не поддерживается данным браузером");
                    }
            }
             else {//если кнопка отжата
                        myMap.geoObjects.remove(myPl);
                  }
        });
            // Добавление панели инструментов на карту
            myMap.controls.add(button, { top : 5, left : 98 });
   }
   function createGeoI(lat,longs){
         myPl= new ymaps.Placemark([lat,longs], 
                {
                  hintContent:'Вы находитесь тут!' 
                }, 
                {
                   iconImageHref: 'img/iametka.png', // картинка иконки
                   iconImageSize: [19, 23] // размеры картинки
                });
		// Добавление метки на карту
		myMap.geoObjects.add(myPl);  
                myMap.zoomRange.get([lat,longs]).then(function (range) {//функция для поиска существующего зума
                                range[1]=15;
                                if(range[1]>=15){
                                   myMap.setCenter([lat,longs],15,{}); 
                                }
                                else{
                                    myMap.setCenter([lat,longs],range[1],{});
                                }
                            
                            });
                myPl.geometry.setCoordinates([lat,longs]);//перемещаем метку в заданые координаты
   }
   function getdata(datas){
       
       var json;
       $.post("obrabotka.php",{query:datas},function(json){
   for (i = 0; i < json.markers.length; i++) {
        createMetka(json.markers[i].id,Number(json.markers[i].x),Number(json.markers[i].y),json.markers[i].id_s);
   } 
   for(var j=1; j<=15; j++){
      cluster.add(myCollection[j]);
   } 
   
     myMap.geoObjects.add(cluster);
     $('.load_block').css('display','none');

                },'json')
     
   }//end getdata
   function setStyle(id_s){
 var stylek=['twirl#blueIcon' ,'twirl#orangeIcon','twirl#darkblueIcon','twirl#pinkIcon',
'twirl#darkgreenIcon','twirl#redIcon',
'twirl#darkorangeIcon' ,'twirl#violetIcon',
'twirl#greenIcon','twirl#whiteIcon',
'twirl#greyIcon' ,'twirl#yellowIcon',
'twirl#lightblueIcon' ,'twirl#brownIcon',
'twirl#nightIcon' ,'twirl#blackIcon']  
return stylek[id_s];
   }
   function createMetka(id,x,y,id_s){
       var coord=new Array();      
       coord.push(x);
       coord.push(y);
       if(myCollection[id_s]==null){
         myCollection[id_s] =[];  
       }
        myPlacemark[id] = new ymaps.Placemark(coord,
        {
            myid:id,
           body : 'Загрузка...'
        }, 
        {
            balloonContentBodyLayout :myBalloonContentBodyLayout,
            balloonAutoPan:true,
            preset: setStyle(id_s-1)
        }); 
       myCollection[id_s].push(myPlacemark[id]);       
       if(id=='<?=$_GET[firm_id]?>'){
           myPlacemark['<?=$_GET[firm_id]?>'].balloon.open();
           myMap.panTo(coord,{flying:true});
           myMap.zoomRange.get(coord).then(function (range) {//функция для поиска существующего зума
            if (myMap.getZoom() > range[1] ) {
                myMap.setZoom(range[1]); 
            } 
          });
       } 
       //myPlacemark[id].events.add('click', onClick);
       myPlacemark[id].events.add('balloonopen', function(e) {
          var balloon = e.get('balloon'),
          $balloonContainer = $(balloon.getOverlay().getBalloonElement());
          $balloonContainer.appendTo($(balloon.getMap().panes.get('outers')));
        })
  }
   //***************************
   $('#region').autocomplete('http://www.svadbagolik.ru/utility/suggestionregion/', {
		delay: 10,
		minChars: 1,
		matchSubset: 1,
		autoFill: true,
		maxItemsToShow: 10
	});

  $("#region").live("keypress", function(e)
{
     if(e.keyCode==13)
     {
      searchRegion();
     }
}); 
  $("#search").live("keypress", function(e)
{
     if(e.keyCode==13)
     {
      search($("#search").val());
     }
});
   
 function searchRegion(){
    var region=$("#region").val();
    if(region!=''){
    $('.load_block').css('display','block');
     $.get("http://geocode-maps.yandex.ru/1.x/",{format:'json',geocode:region, results:1},function(data){  
         if(data){
   if(data.response.GeoObjectCollection.featureMember!=''){        
       var pos=data.response.GeoObjectCollection.featureMember[0].GeoObject.Point.pos; 
       var BoundY=data.response.GeoObjectCollection.featureMember[0].GeoObject.boundedBy.Envelope.lowerCorner; 
       var BoundX=data.response.GeoObjectCollection.featureMember[0].GeoObject.boundedBy.Envelope.upperCorner; 
       pos=pos.split(' ');
       BoundY=BoundY.split(' ');
       BoundX=BoundX.split(' ');
       myMap.panTo([Number(pos[1]),Number(pos[0])], {flying:true});
       myMap.setBounds([
       [Number(BoundY[1]),Number(BoundY[0])],
       [Number(BoundX[1]),Number(BoundX[0])]
       ]); 
       myMap.zoomRange.get([Number(pos[1]),Number(pos[0])]).then(function (range) {//функция для поиска существующего зума
            if (myMap.getZoom() > range[1] ) {
                myMap.setZoom(range[1]); 
            } 
       });
       $('.load_block').css('display','none');
   }
     else{
            alert('Введите точней данные');
            $('.load_block').css('display','none');
         }
      }else{
        alert('Введите точней данные');
          $('.load_block').css('display','none');
      };
     
 },'json')}else{
 alert('Введите что-то');
 };
 };
$('.ccategory').bind('click',function(){
    var id=$(this).attr('id');
    if($(this).attr('checked')){
        $('.category:[id='+id+']').css('background-color','white');
            cluster.add(myCollection[id]);
        $(this).attr('checked',true);
    }else{
        $('.category:[id='+id+']').css('background','none');
            cluster.remove(myCollection[id]);
            // myMap.geoObjects.remove(cluster);
        $(this).attr('checked',false);
    }
})




function mybred(e) {

     var balloon = e.get('balloon'),

          $balloonContainer = $(balloon.getOverlay().getBalloonElement());

          $balloonContainer.appendTo($(balloon.getMap().panes.get('outers')));

}
 function onClick(e) {
     $('#help-visibility').click();
 $('#balun').css('display','none'); // и двигаем балун
var placemark = e.get('target'),
name = placemark.properties.get('myid'); // Получаем данные для запроса из свойств метки.
var names = [],str='';

var coords=placemark.geometry.getCoordinates();
 ymaps.geocode([coords[0],coords[1]],{kind:'metro',results:'2'})
    .then(function (res) {
                    res.geoObjects.each(function (obj) {
                        names.push(obj.properties.get('name'));
                    });
                    if(names!=''){
                       str=' '+names[0];
                    }
                    if(names!=''){
                       str+='<p>'+names[1];
                    }
                   $.post("obrabotka.php",{idget:name},function (json){
                      var strbal=json.markers[0].f_n+'<p>'+
                     json.markers[0].s_d+'<p>'+
                     'Адрес: '+json.markers[0].ads+'<p>'+
                     'Телефон: '+json.markers[0].ph+'<p>'+
                     'Email: '+json.markers[0].eil+'<p>'+
                     'Skype: '+json.markers[0].sk+'<p>'+
                     'Время работы: '+json.markers[0].w_h+'<p>'+
                     'Ссылка: '+json.markers[0].lin+'<p>';                    
                    placemark.properties.set('body',strbal);//устанавливаем ввесь контент
                    placemark.properties.set('metro',str);//установили точки метро
                   },'json');  
                    
    });
        }
   function search (stry){
       var json;
       $.post("obrabotka.php",{search:stry},function(json){
              cluster.removeAll();
              mySerCollection=[];
            for (i = 0; i < json.markers.length; i++) {
                    createMetkaRes(json.markers[i].id,Number(json.markers[i].x),Number(json.markers[i].y),json.markers[i].id_s);
            } 
            mySerCollection.forEach(function(index,key){
                cluster.add(mySerCollection[key]);
            })
    
                },'json') 
   } 

function createMetkaRes(id,x,y,id_s){
       var coord=new Array();      
       coord.push(x);
       coord.push(y);
       if(mySerCollection[id_s]==null){
         mySerCollection[id_s] =[];  
       }
        mySerPlacemark[id] = new ymaps.Placemark(coord,
        {
            myid:id,
            body : 'Загрузка...'
        }, 
        {
         balloonContentBodyLayout :myBalloonContentBodyLayout,
            balloonAutoPan:true,
            preset: setStyle(id_s-1)
        }); 
       mySerCollection[id_s].push(mySerPlacemark[id]); 
       mySerPlacemark[id].events.add('click', onClick);
  }

////////******************************ниже не вписывать ничего        
        }
    </script>
</head>
 
<body>
	<!-- Линия, декор --> 
	<div class="line"></div>
     <!-- / Линия, декор --> 
    
    <!-- Основной блок с балунми и картой -->
    <div class="mainWrap">
    
    <!-- Балун -->
    <div id="balun">
                        <div class="content">ssssssssssssssssssssssssss
                        ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss
                        ssssssssssssssssssssssssssssssssssssssssssssssss
                        ssssssssssssssssssssssssssssssssssssssss</div>
                        <div class="close"></div>
                        <div class="tail"></div>
    </div>    
    <!-- /Балун -->
     
    <!-- Блок для карт -->   
	<div class="open" id='mapinthis'>
		
    <!-- Мапа -->    
        <div id="map"></div>
    <!-- / Мапа -->
    
    <!--Helps-->  
     <div class="block_help h_one">
     <div class="tail"></div>
            <div class="content">
            	<p>Кнопка для определения Вашего приблизительного местоположения</p>
            </div>
    	</div>
   	 
     <div class="block_help h_two">
     <div class="tail"></div>
            <div class="content">
            	<p>Нажав на ссылку ниже вы закроете все подсказки</p>
                
            </div>
    	</div>     
       
     <div class="block_help h_three">
     <div class="tail"></div>
            <div class="content">
            	<p>ssssssss</p>
                <p>sssssdddddddddddddddsss</p>
                <p>ssssssdddddddddddss</p>
                <p>sssdddddddddd d d  dddddddddsssss</p>
                <p>ssssssdss</p>
            </div>
    	</div>  
     
     <div class="block_help h_four">
     <div class="tail"></div>
     <div class="tail_two"></div>
            <div class="content">
            	<p>ssssssss</p>
                <p>sssssdddddddddddddddsss</p>
                <p>ssssssdddddddddddss</p>
                <p>sssdddddddddd d d  dddddddddsssss</p>
                <p>ssssssdss</p>
                <p>ssssssss</p>
            </div>
    	</div> 
        
     <div class="block_help h_five">
     <div class="tail"></div>
            <div class="content">
            	
                <p>sssssdddddddddddddddsss</p>
                <p>ssssssdddddddddddss</p>
                <p>sssdddddddddd d d  dddddddddsssss</p>
            </div>
    	</div>
        
    <!-- Дополнительные блоки для фильтров -->    
        <div class="b_over">
        	<div class="block_one">
                
<?
  $resso = mysql_query("SELECT * FROM temp_section");
  $mar= mysql_fetch_assoc($resso);
  if($mar){
  do{
     echo <<<HERE
      <p class="category"  id="$mar[id_section]"><input type="checkbox" class="ccategory" checked  id="$mar[id_section]" /> $mar[name] </p>
HERE;
     
  }while($mar = mysql_fetch_assoc($resso));
  }               
?>
            </div>
            <div class="block_two">
            	<input type="text" name="search" id="search" placeholder="Поиск на карте..."/>
            </div>
            <div class="block_third">
            	<input type="text" name="region" id="region" placeholder="Введите регион..."/>
            </div>
             <div class="block_fourth">
                <a href ="registration.php" >Регистрация на сайте</a>
            </div>
        </div>  
    <!-- / Дополнительные блоки для фильтров -->
    
    <!-- Совалка -->
        <img src="img/resize.png" width="27" height="27" alt=">" onmousedown="hook(event)" class="resize"/>
    <!-- / Совалка -->
    
    <!-- Блок загрузки -->
        <div class="load_block"><img src="img/loading.gif" alt="загрузка..."/></div>
    <!-- / Блок загрузки -->    
    
	</div>
    <!-- / Блок для карт --> 
    
    </div>
    <!-- / Основной блок с балунми и картой -->
    
    <!-- Открывашка -->
    <div class="map_center">
        <a id="help-visibility" href="#">Скрыть подсказки</a> <a id="map-visibility" href="#">Открыть карту</a>
    </div>
    <!-- / Открывашка -->    
    
</body>
</html>