<!DOCTYPE html>
<html>
<head>
<? require_once 'conectdb.php';?>
    <meta charset="utf-8" />
    <title>Координаты</title>
    <link rel="stylesheet" href="css/main.css" />
 	<script src="js/jquery-1.7.2.min.js"></script> 
	<script src="js/jquery-ui-1.8.22.custom.min.js"></script> 
    <!-- Подключаем API  карт 2.x  -->
    <script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
    	<style>
		.ymaps-b-balloon, .ymaps-balloon-overlay, .ymaps-shadows-pane {
			display: none; 
		}
		
		.close {
			position: absolute;
			top: 4px;
			right: 4px;
			width: 12px;
			height: 12px;
			background: #f00 url("close.png");
			cursor: pointer;
		}
		
		.balloon-bottom {
			position: absolute;
			bottom: -50px;
			left: 50%;
			margin-left: -13px;
			width: 26px;
			height: 51px;
                       
			background: url("balloon_bottom.png");
		}
	</style>
 <script type="text/javascript">
	var cur = null; var myMap,gg=0;

ymaps.ready(init);// Как только будет загружен API и готов DOM, выполняем инициализацию 
  function init () {
      var position=[], centy=[], fade,str;
    $("#mapinthis").resizable({//функция изменения размера карты
    maxWidth: 1000,
    minWidth: 1000,
    minHeight: 330,
    start: function(event, ui) {//действия которые выполняются при старте изменения размеров карты
        $('#help-visibility').click();//сворачиваем подсказки
        /** скрываем форму ссылки на кату  */
        $('.blockactive45').css('background-position','0 0');	   
        $('.blockactive45').attr('class','close');	
        $(".block_fourth").animate({"height": "17"},500)
        lin=0;//счетчик который указывает что карта скрыта
    },
        resize: function(event, ui) { //функия действий которые выполняются при непосредственно изменении размеров карты
            myMap.container.fitToViewport();//изменяем размер карты соотвествено к контейнеру
        } 
    });  
      function link(){//функция которая срабатывает если перешли по созданой ранее ссылке на карту
            if('<?=$_GET[center]?>'!=''){//если есть параметр центра карты...
                var str='<?=$_GET[center]?>'; //присваиваем...
                linkcentr=str.split(',')//разбиваем кординаты в массив..
                myMap.setCenter(linkcentr, 12, "map");  //устанавливаем координаты карты
            }
            if('<?=$_GET[zoom]?>'!=''){//если есть параметр зума
            myMap.setZoom('<?=$_GET[zoom]?>'); //устанавливаем зум карты
            }
            if('<?=$_GET[type]?>'!=''){//если есть параметр типа карты
            myMap.setType('yandex#'+'<?=$_GET[type]?>'); //устанавливаем тп карты
            }
            if('<?=$_GET[cat]?>'!=''){//если есть массив с зарытыми категориями...
            var catt='<?=$_GET[cat]?>';
            var cattc=catt.split(',');//разбываем на массив
            cattc.forEach(function(idv){//цикл перебора строчек с категориями
                $('.category:[id='+idv+']').css('background','none');//указвает соответственной категории фон
                $('.ccategory:[id='+idv+']').attr('checked',false);//указвает соответственной категории неактивную галочку
                masrem.push(idv); //записываем в массив
                cluster.remove(myCollection[idv]);//удаляем коллекцию с кластера   
            })
            }
            myMap.container.fitToViewport();
            if('<?=$_GET[r]?>'!=''){//если регион был указан в ссылке...
            $('#region').val('<?=$_GET[r]?>'); //указываем текст в инпуте формы
            }
            if('<?=$_GET[q]?>'!=''){//если поиск был указан в ссылке... 
            $('#search').val('<?=$_GET[q]?>'); //указываем текст в инпуте формы
            }
      }
    var mySerPlacemark = new Array(); //создаем массив для меток поиска
    var myPlacemark = new Array(); //массив для обычных меток
    var myMap,l='',iflag,iflagR,iflagQ,open,lin=0, masrem=[],oldpost=0;//куча параметров
    $('.ccategory').attr('checked',true);//ставим все категории изначально выбраные
    var mySerCollection=[];//массив для коллекций меток поиска
    var mas=[];//массив
    var myCollection=[],active=null;//еще пареметры
    /** создаем стиль балуна кластера пока не комментирую  */
   
   var cluster = new ymaps.Clusterer();
    /**такойже стиль описываю под обычную метку ТАКЖЕ пока не описываю */
   
     //* конец писания стиля метки*/   
        cluster.options.set({
            gridSize:50
        });
        if('<?=$_GET[zoom]?>'!=''){
            work();
        }
        $("#map-visibility").click(function(){
            work(); 
        })  
        function work() {
            if ($(".open").css('height')=="0px"){

                $(".open").animate({
                        "height": "+=550"
                }, 1000,
                function() {                
                    if(l==''){
                        if('<?=$_GET[q]?>'!=''){
                            search('<?=$_GET[q]?>'); 
                        }else{
                            l=getdata('all'); 
                        }
                    }
                    myMap.container.fitToViewport();
                    $("#map-visibility").text('Скрыть карту');
                });
            $("#help-openlity").css('display','block');
            $("#help-visibility").css('display','block');
            $("#help-visibility").text('Скрыть подсказки');     
        }


        if (!active) {
            active=true;
            if (!myMap) {                        
            // Создание экземпляра карты и его привязка к контейнеру с
            // заданным id ("map")
            myMap = new ymaps.Map('map', 
            {
                // При инициализации карты, обязательно нужно указать
                // ее центр и коэффициент масштабирования
                center: [55.753559,37.609218], // центр карты установка
                zoom: 12
            }); 
            link();
            myMap.geoObjects.add(cluster);
            // элемента управления и его параметры.
            myMap.controls
            // Кнопка изменения масштаба
            .add('zoomControl')
            // Список типов карты
            .add('typeSelector', { top : 5, left : 128 })
            // Стандартный набор кнопок
            .add('mapTools');
            razvert();
             //*********
        
            myMap.zoomRange.get(myMap.getCenter()).then(
            function (range){//функция для поиска существующего зума
                if (myMap.getZoom() > range[1] ) {
                    myMap.setZoom(range[1]); 
                } 
            });
            //myMap.behaviors.get('drag').options.set('inertia', false);
            myMap.events.add('click', function(){
                $('#help-visibility').click();
            })
            myMap.events.add('mousedown', function(){
                $('#help-visibility').click();
                $('.b_over').click();
            })
            ///************** изминения зума при смене типа карты
            myMap.events.add("typechange", function (e) { 
                myMap.zoomRange.get(myMap.getCenter()).then(function (range) {
                    if (myMap.getZoom() > range[1] ) {
                        myMap.setZoom(range[1]); 
                    } 
                }); 
            });
            myMap.events.add('actiontick', moveMap); //меняем положение balloon - а при перемещении карты
            myMap.events.add('boundschange', moveMap); //меняем положение balloon - а при зумировании карты
            myMap.events.add('click', moveMap);
            myMap.events.add('sizechange', moveMap); 
            }                    
        }else {
                $(".open").animate({
                    "height": "0px"},
                function() {
                    $("#map-visibility").text('Показать карту снова');   
                        $("#help-visibility").css('display','none');
                        $("#help-openlity").css('display','none');
                    active=null;
                });
        }
            $("#help-visibility").text('Скрыть подсказки');
            $("#help-visibility").attr('id','help-visibility');
    };
	/*---------------------------------------------------------------------------*///блоки закрытие и открытия
	
        $('.block_fourth').live('click',function(e){
        if (!$(e.target).closest('input:[name=link]').length == 0){}
        else{
                        if(lin==0){
                                $('.close').css('background-position','0 10px');
                                $('.close').attr('class','blockactive45');
                                $(".block_fourth").animate({"height": '+=28' },500)
                                $('#link').select();
                                setLocationParam ();
                                lin=1;

                        }else{
                                $('.blockactive45').css('background-position','0 0');	   
                                $('.blockactive45').attr('class','close');	
                                $(".block_fourth").animate({"height": "17"},500)
                                lin=0;
                        }
                }
	})
	
    /*---------------------------------------------------------------------------*///блоки закрытие и открытия подказок 
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
            $(".h_one").animate({"height": "45px","opacity": "1"},1000)
            .css('overflow','visible');
            $(".h_two").animate({"height": "45px","opacity": "1"},1000)
            .css('overflow','visible');
            $(".h_three").animate({"height": "45px","opacity": "1"},1000)
            .css('overflow','visible');
            $(".h_four").animate({"height": "90px","opacity": "1"},1000)
            .css('overflow','visible');
            $(".h_five").animate({"height": "45px","opacity": "1"},1000)
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
         $('#help-visibility').click();
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
   function createGeoI(lat,longs){//функция установки метки местонахождения
   iflag=[lat,longs];
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
                myMap.zoomRange.get([lat,longs]).then(
                    function (range) {//функция для поиска существующего зума
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
            if('<?=$_GET[cat]?>'!=''){
                    var catt='<?=$_GET[cat]?>';
                    var cattc=catt.split(',');
                    cattc.forEach(function(idv){
                    cluster.remove(myCollection[idv]);   
                })
            }
            myMap.geoObjects.add(cluster);
          /*  cluster.events.add('objectsaddtomap', function () {
               placemarkClick(myPlacemark[86])
            });*/
           //  cluster.events.add('objectsaddtomap', openB);   
            $('.load_block').css('display','none');
     },'json')
   }//end getdata
  function setStyle(id_s){//функция создание стилей меткам
        var stylek=['twirl#blueIcon' ,'twirl#orangeIcon','twirl#darkblueIcon','twirl#pinkIcon',
        'twirl#darkgreenIcon','twirl#redIcon',
        'twirl#darkorangeIcon' ,'twirl#violetIcon',
        'twirl#greenIcon','twirl#whiteIcon',
        'twirl#greyIcon' ,'twirl#yellowIcon',
        'twirl#lightblueIcon' ,'twirl#brownIcon',
        'twirl#nightIcon' ,'twirl#blackIcon']  
        return stylek[id_s];
  }
  function createMetka(id,x,y,id_s){//функция создания метки и всей параметров для нее
        var coord=new Array();      
        coord.push(x);
        coord.push(y);
        if(myCollection[id_s]==null){
            myCollection[id_s]=[];  
        }
        myPlacemark[id] = new ymaps.Placemark(coord,
        {
            myid:id,
            body: 'Загрузка...'
        }, 
        {
            balloonMinWidth:500,
            balloonMinHeight:300,
            balloonAutoPanMargin:[100,250],
           // balloonContentBodyLayout:myBalloonContentBodyLayout,
            balloonAutoPan:true,
            preset: setStyle(id_s-1)
        }); 
        myCollection[id_s].push(myPlacemark[id]);
        myPlacemark[id].events.add('click', placemarkClick);
  }

    $("#region").live("keypress", function(e)//поиск региона при клике на ентер
    {
        if(e.keyCode==13)
        {
            searchRegion();
        }
    }); 
    $("#search").live("keypress", function(e)//поиск нужной метки при клике на ентер
    {
        if(e.keyCode==13)
        {
            search($("#search").val());
            $('.remSearch').css('display','block');
        }
    });
    function searchRegion(){
        var region=$("#region").val();
        if(region!=''){
                $('.load_block').css('display','block');
                ymaps.geocode(region,{results:1})
            .then(function (res) {
                var pos=res.geoObjects.get(0).geometry.getCoordinates(); 
                var Bound =res.geoObjects.get(0).properties.get('boundedBy');  
                var BoundY=Bound[1]; 
                var BoundX=Bound[0]; 
                iflagR=1;
                myMap.panTo([Number(pos[0]),Number(pos[1])], {flying:true});
                    myMap.setBounds([
                        BoundX,
                        BoundY,
                    ]);
                $('.load_block').css('display','none'); 
            },
            function (err) {
                $('.load_block').css('display','none');
                alert('Поиск не дал результатов!');
            }) 
        }else{
                alert('Введите что-то');
             };
    };
  
    $('.ccategory').bind('click',function(){//функция скрывания/показа меток категорий
        var id=$(this).attr('id');
        if($(this).attr('checked')){
            $('.category:[id='+id+']').css('background-color','white');
            if(iflagQ!=1){
                cluster.add(myCollection[id]);
            }else{
                cluster.add(mySerCollection[id]);
            }   
                masrem.forEach(function(index,idg){
                    if(index==id){
                        masrem.splice(idg, 1);
                    }
                })                
            $(this).attr('checked',true);
        }else{
            $('.category:[id='+id+']').css('background','none');
            if(iflagQ!=1){
                cluster.remove(myCollection[id]);
            }else{
                cluster.remove(mySerCollection[id]);
            }            
                masrem.push(id); 
            $(this).attr('checked',false);
        }
            $('#help-visibility').click();
    })

    function search (stry){//функция поиска по контенту введенных слов
       var json;
       $.post("obrabotka.php",{search:stry},function(json){
           $('.load_block').css('display','none');
           if(json){
           iflagQ=1;
              cluster.removeAll();
              mySerCollection=[];
            for (i = 0; i < json.markers.length; i++) {
                    createMetkaRes(json.markers[i].id,Number(json.markers[i].x),Number(json.markers[i].y),json.markers[i].id_s);
            } 
            $('.ccategory').attr('checked',false)
            $('.category').css('background','none');
            mySerCollection.forEach(function(index,key){
                $('.category:[id='+key+']').css('background-color','white');
                $('.ccategory:[id='+key+']').attr('checked',true)
                cluster.add(mySerCollection[key]);
            })
           }
       },'json') 
       
   }
    function createMetkaRes(id,x,y,id_s){//функция создания меток РЕЗУЛЬТАТА поиска
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
            
            balloonAutoPan:true,
            preset: setStyle(id_s-1)
        }); 
       mySerCollection[id_s].push(mySerPlacemark[id]); 
     mySerPlacemark[id].events.add('click', placemarkClick);
    }
   function setLocationParam () { 
       var mapt=myMap.getType();
       mapt=mapt.split('#');
       var params = [
            'center=' + myMap.getCenter(), // карта у получателя ссылки будет центрироваться на метке
            'type='+mapt[1], //в теле ссылки передаю простой поясняющий текст
            'zoom=' + myMap.getZoom()
        ]
       if(iflag){
           params.push('ia=' + iflag);
       }
       if(iflagQ){
           params.push('q=' + $('#search').val());
       }
       if(iflagR){
           params.push('r=' + $('#region').val());
       }
       if(masrem[0]!=''){
           params.push('cat=' + masrem);
       }
        if(open){
           params.push('O=' + open);
        }
           var myurl='http://svadbagolik.cybers.net.ua/?'+encodeURI(params.join('&')); //собственно, сама ссылка в сборе
            $('#link').val(myurl);
        }    
        $('.b_over').click(function(e){
            if (!$(e.target).closest('.ccategory').length == 0){ 
                console.log('block_one');
                $('.blockactive45').css('background-position','0 0');	   
                $('.blockactive45').attr('class','close');	
		$(".block_fourth").animate({"height": "17"},500)
                lin=0;
            }
            else{
                $('#help-visibility').click(); 
                if (!$(e.target).closest('.block_fourth').length == 0){ 
                
                }else{
                    $('.blockactive45').css('background-position','0 0');	   
                $('.blockactive45').attr('class','close');	
                $(".block_fourth").animate({"height": "17"},500)
                lin=0;
                }
            }
        })
        $('#map').click(function(){
           //console.log('click');
           $('#help-visibility').click(); 
           $('.blockactive45').css('background-position','0 0');	   
                $('.blockactive45').attr('class','close');	
		$(".block_fourth").animate({"height": "17"},500)
                lin=0;
        })
        $('.remSearch').click(function(){
            $('#search').val('');
            $('.remSearch').css('display','none')
            cluster.removeAll();
             for(var j=1; j<=15; j++){
                cluster.add(myCollection[j]);
            }
            iflagQ=0;
            $('.ccategory').attr('checked',true);
            $('.category').css('background-color','white');
            
        })
    //********************/////////
    ///////////////////////////////*****************************/////////////////////////////
    //
   
    function placemarkClick(e) {
        var names = [], carr=[];str='';
        baloonMetki(e,e.get('target').properties.get('body'))
        //myMap.panTo(e.get('target').geometry.getCoordinates(),{flying:true});        
        var name=e.get('target').properties.get('myid');
        var coords= e.get('target').geometry.getCoordinates();
        var oldindex='';
        
        if(e.get('target').properties.get('body')=='Загрузка...'){
            $.post("obrabotka.php",{idget:name},function (json){
                        //placemark.properties.set('myid','0')
                        var strbal=json.markers[0].f_n+'<p>'+
                        json.markers[0].s_d+'<p>'+
                        'Адрес: '+json.markers[0].ads+'<p>'+
                        'Телефон: '+json.markers[0].ph+'<p>'+
                        'Email: '+json.markers[0].eil+'<p>'+
                        'Skype: '+json.markers[0].sk+'<p>'+
                        'Время работы: '+json.markers[0].w_h+'<p>'+
                        'Ссылка: '+json.markers[0].lin+'<p>';  
                  ymaps.geocode([coords[0],coords[1]],{kind:'metro', results:4,spn:[0.01,0.01]})
        .then(function (res) {
                    res.geoObjects.each(function (obj) {
                        names.push(obj.properties.get('name'));
                        carr.push(obj.geometry.getCoordinates());
                    });
            var mrt=[],g=1;
            names.forEach(function(index,idc){
                if(g>2){

                }else{
                    if(index!='' && index!=oldindex){
                    oldindex=index;                     
                        if(ymaps.coordSystem.geo.rulerDistance(carr[idc],coords)<=1000){
                        str+=' '+index+'('+Math.round(ymaps.coordSystem.geo.getDistance(carr[idc],coords))+' м.)';
                            mrt.push(Math.round(ymaps.coordSystem.geo.getDistance(carr[idc],coords)));
                            g++;
                        }
                    } 
                }
            })
           strbal+=str;
            baloonMetki(e,strbal) ;  
            e.get('target').properties.set('body',strbal);//устанавливаем ввесь контент
        })     
            },'json');
       }
       
       
    }
  
///*************************************
 function baloonMetki(e,text){
        $('#myBalloon').remove();
        // HTML-содержимое
        var menuContent ='<div id="myBalloon"><div class="close"></div>'+text +'<div class="balloon-bottom"></div>';
        // Размещаем на странице
        $('body').append(menuContent);
        // ... и задаем его стилевое оформление.
            $('#myBalloon').css({
                position: 'absolute',
                background: '#fff',
                border: '1px solid #ccc',
                'border-radius': '4px',
                width: '320px',
                'font-size': 'small',
                'margin-bottom': 'auto',
                'margin-top': 'auto',
                'padding': '10px',
                'z-index': 999999
            });
            
        position = e.get('target').geometry.getCoordinates();
        
        // Преобразуем географические координаты в пиксели окна браузера
        var projection = myMap.options.get('projection');
      
        if (position.length) {
            centy = myMap.converter.globalToPage(
                projection.toGlobalPixels(
                    // географические координаты
                    [position[0], position[1]],
                    myMap.getZoom()
                )
            );
        }
        
            if (centy.length) {
                $('#myBalloon').css({
                    left: centy[0] - ($('#myBalloon').width()/2),
                    top: centy[1] - ($('#myBalloon').height()+70)
                });
         
                // кнопка закрыть
                $('#myBalloon .close').click(function () {
                    $('#myBalloon').remove();
                    myMap.balloon.close();
                });
            }
         }            
//                /*****************************    
    // движение карты
function moveMap(e) {
// Преобразуем географические координаты в пиксели окна браузера
var projection = myMap.options.get('projection');

if ($('#myBalloon').lenght) {
        position = e.get('target').geometry.getCoordinates();
}

if (position.length) {
centy = myMap.converter.globalToPage(
projection.toGlobalPixels(
// географические координаты
[position[0], position[1]],
myMap.getZoom()
)
);
}
if (centy.length) {
$('#myBalloon').css({
left: centy[0] - ($('#myBalloon').width()/2),
top: centy[1] - ($('#myBalloon').height()+70)
});
if ($('#myBalloon').lenght) {
        e.get('target').click();
}

if (((centy[0] >= ($('#map').offset().left + $('#map').width()))) || ((centy[0] <= ($('#map').offset().left))) || (centy[1] < $('#map').offset().top) || (centy[1] > ($('#map').offset().top + $('#map').height()))){ } else {
                fade = false;
        }
        if (fade) {
                $('#myBalloon').fadeOut();
        } else {
                $('#myBalloon').fadeIn();
        };
    }
    if (e.get('newZoom') != e.get('oldZoom')) {
            if (centy.length) {
                   if (((centy[0] >= ($('#map').offset().left + $('#map').width() + 80))) || ((centy[0] <= ($('#map').offset().left - 80))) || (centy[1] < $('#map').offset().top) || (centy[1] > ($('#map').offset().top + $('#map').height()))){ fade = true;
                    } else {
                            fade = false;
                    }	
            }	
    }
    if (fade) {
            $('#myBalloon').fadeOut();
    } else {
            $('#myBalloon').fadeIn();
            };


}
    /////////////////////////////////////////////**
    ///*******************************************************///////
  // Открытие балуна кластера с выбранным объектом.

  }
 </script>





</head>
<body>
<!-- Линия, декор --> 
<div class="line"></div>
<!-- / Линия, декор --> 
    <!-- Основной блок с балунми и картой -->
    <div class="mainWrap"> 
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
            	<p>Выбирете определеную, или несколько категорий для фильтра объектов на карте.</p>
            </div>
    	</div>  
     
     <div class="block_help h_four">
     <div class="tail"></div>
     <div class="tail_two"></div>
            <div class="content">
            	<p>Поля для быстрого поиска объектов на нашей карт. В первом поле Вы може быстро найти конкретный объект по его названию. Во втором - введите название региона и он автоматически появится на карте</p>
            </div>
    	</div>
     <div class="block_help h_five">
     <div class="tail"></div>
            <div class="content">
            	
                <p>Нажмите для того что бы пройти регистрацию на нашем сайте!</p>
            </div>
    	</div>
    <!-- Дополнительные блоки для фильтров -->    
        <div class="b_over">
        	<div class="block_one">
<?//создание чеков для категорий
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
            	<input type="text" name="search" id="search" placeholder="Поиск на карте..."/><input type="button" name="reset" title="Очистить результат поиска" style="display: none" class="remSearch" value=""/>
            </div>
            <div class="block_third">
            	<input type="text" name="region" id="region" placeholder="Введите регион..."/>
            </div>
            <div class="block_fourth">
            	<span class="close"></span>
                <p>Ссылка на карту</p>
                <input type="text" name="link" onclick="this.select();" id="link" value=""/>
            </div>
            <div class="block_fifth">
                <a href ="registration.php" >Регистрация на сайте</a>
            </div>
        </div>  
    <!-- / Дополнительные блоки для фильтров -->
    <!-- Совалка -->
<!--        <img src="img/resize.png" width="27" height="27" alt=">"  class="resize"/>-->
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
         <a id="help-visibility" href="#" style="display:none">Скрыть подсказки</a> <a id="map-visibility" href="#">Открыть карту</a>
    </div>
    <!-- / Открывашка -->  
</body>
</html>                           
                            