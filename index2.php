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
    <script src="http://api-maps.yandex.ru/2.0.15/?load=package.full&mode=debug&lang=ru-RU" type="text/javascript"></script>
 <script type="text/javascript">
	 var myMap,gg=0,open=null,bopen=null;var eventsGroup=null,  mapSize=null,openForZoom=null, mov=0,openCoord=null;var balloon; 
ymaps.ready(init);// Как только будет загружен API и готов DOM, выполняем инициализацию 
  function init () {
      var myPl;
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
            myMap.balloon.close();
            mapSize = myMap.container.getSize();
    } 
    }); 

    /**такойже стиль описываю под обычную метку ТАКЖЕ пока не описываю */
    var myBalloonContentBodyLayout = ymaps.templateLayoutFactory.createClass(
    '<div>$[properties.body]<p><div>$[properties.metro]</div></div>' 
    );
     //* конец писания стиля метки*/ 
     ///****
     ///****
     ///*****************************************
     MyMainContentLayout = ymaps.templateLayoutFactory.createClass('', {
                        build: function () {
                            MyMainContentLayout.superclass.build.call(this);
                            // будем следить за изменением состояния кластераset
                            // и при смене выбранного геообъекта перезадавать содержимое
                            this.stateListener = this.getData().state.events.group()
                                .add('change', this.onStateChange, this);
                            this.activeObject = this.getData().state.get('activeObject');
                            bopen=this.activeObject.properties.get('myid');
                            openForZoom=this.activeObject.properties.get('myid');
                            this.applyContent();
                        },
                        clear: function () {
                            this.stateListener.removeAll();
                            MyMainContentLayout.superclass.clear.call(this);
                        },
                        onStateChange: function () {
                            var newActiveObject = this.getData().state.get('activeObject');
                            if (newActiveObject != this.activeObject) {
                                this.activeObject = newActiveObject;
                                bopen=newActiveObject.properties.get('myid'); 
                                openForZoom=newActiveObject.properties.get('myid');
                                this.applyContent();
                            }
                        },
                        applyContent: function () {
                            // Для того, чтобы макет автоматически изменялся при обновлении данных
                            // в геообъекте, создадим дочерний макет через фабрику
                            var subLayout = new MyMainContentSubLayout({
                                    options: this.options,
                                    properties: this.activeObject.properties
                                });
                            // прицепим новый макет к родителю
                            subLayout.setParentElement(this.getParentElement());
                        }
                    }),

                    // А вот и сам дочерний макет - он принимает на вход данные текущего выбранного геообъекта и показывает их.
        MyMainContentSubLayout = ymaps.templateLayoutFactory.createClass('$[properties.balloonContentBody]')
     ///****
     ///****
     ///*****************************************
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
    var myCollection=[],active=null,F=0;//еще пареметры
    /** создаем стиль балуна кластера пока не комментирую  */
   var cluster = new ymaps.Clusterer();
    cluster.options.set({
        clusterBalloonMainContentLayout: MyMainContentLayout,
        gridSize:50,
        clusterBalloonHeight:320,
        clusterBalloonSidebarWidth:25,
        clusterBalloonPane: 'movableOuters'
    });
// Открытие балуна кластера с выбранным объектом.
var PLC='<?=$_GET[O]?>',bylo=0;var PLBC='<?=$_GET[BO]?>';
// Поскольку по умолчанию объекты добавляются асинхронно,
// обработку данных можно делать только после события, сигнализирующего об
// окончании добавления объектов на карту.
    cluster.events.add('objectsaddtomap', function () {
        if(openForZoom){
                        // Получим данные о состоянии объекта внутри кластера.
                var geoObjectState = cluster.getObjectState(myPlacemark[openForZoom]);
                // Проверяем, находится ли объект находится в видимой области карты.
               ///и объект попадает в кластер, открываем балун кластера с нужным выбранным объектом.
                if (geoObjectState.isShown) {
                    if (geoObjectState.isClustered) {
                        geoObjectState.cluster.state.set('activeObject', myPlacemark[openForZoom]);
                        geoObjectState.cluster.balloon.open();
                    } else {
                        // Если объект не попал в кластер, открываем его собственный балун.
                        myPlacemark[openForZoom].balloon.open();
                    }
                }
        }
       if(PLC!=''){
           myPlacemark[PLC].balloon.open();
           $('#help-visibility').click();
           PLC='';
       }  
          if(PLBC!=''){  
              $('#help-visibility').click();
                // Получим данные о состоянии объекта внутри кластера.
                var geoObjectState = cluster.getObjectState(myPlacemark[PLBC]);
                // Проверяем, находится ли объект находится в видимой области карты.
                if (geoObjectState.isShown) {
                    
                    // Если объект попадает в кластер, открываем балун кластера с нужным выбранным объектом.
                        geoObjectState.cluster.balloon.open();
                        geoObjectState.cluster.state.set('activeObject', myPlacemark[PLBC]);
                        PLBC='';
                }
          }
    });
cluster.events.add('balloonclose', function (e) {bopen=null})
cluster.events.add('balloonopen', function (e) {
    
    if(mov!=1){
 ///// создание смещения метки к низу карты
    var placemark=e.get('target');
    ///смещение карти вниз и по центру
    var geo=placemark.geometry.getCoordinates(),
       projection =  myMap.options.get('projection'),
       globalPixel = projection.toGlobalPixels(geo, myMap.getZoom()),
       size = myMap.container.getSize();
       myMap.panTo(projection.fromGlobalPixels([globalPixel[0], (globalPixel[1] - size[1]/2 +10) ], myMap.getZoom()),
                    {delay: 0});
    /////*****
    }
 gg=0
var geoObjects=[];
    var cluster = e.get('target'),
    geoObjects = cluster.properties.get('geoObjects'); 
    if(geoObjects){
    geoObjects.forEach(function(index, i){
        if(i>9){
           gg=10;
        }
    })
    if(gg==10&&openForZoom){ cluster.balloon.close()}else{
    geoObjects.forEach(function(index, i){        
        (function (currentGeoObject) {
            currentGeoObject.properties.set({
                clusterCaption: i+1
            });
             if(currentGeoObject.properties.get('change')==0){  
                    $.post("obrabotka.php",{idget:currentGeoObject.properties.get('myid')},function (json){
                                    var strbal=json.markers[0].f_n+'<p>'+
                                        json.markers[0].s_d+'<p>'+
                                        'Адрес: '+json.markers[0].ads+'<p>'+
                                        'Телефон: '+json.markers[0].ph+'<p>'+
                                        'Email: '+json.markers[0].eil+'<p>'+
                                        'Skype: '+json.markers[0].sk+'<p>'+
                                        'Время работы: '+json.markers[0].w_h+'<p>'+
                                        'Ссылка: '+json.markers[0].lin+'<p>'; 
                                        currentGeoObject.properties.set({
                                            balloonContentBody: strbal,
                                            body:strbal,
                                            change:1
                                        });
                            },'json');
             }
             if(currentGeoObject.properties.get('change')==1 && currentGeoObject.properties.get('balloonContentBody')==''){
                        currentGeoObject.properties.set({
                            balloonContentBody: currentGeoObject.properties.get('body')
                        });
             }
             
            })            
            (index);
       
        })
        }
    }  
    
      /////////////
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
                    mapSize = myMap.container.getSize();
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
       myMap.setZoom = function (zoom, options) {

    var globalPixelCenter = this.getGlobalPixelCenter(),
        scale = Math.pow(2, zoom - this.getZoom()),
        newPixelCenter = [globalPixelCenter[0] * scale, globalPixelCenter[1] * scale];

    if (self.balloon && self.balloon.isOpen()) {

        var ballonCenter = self.balloon.getPosition(),
            offsetBase = [(ballonCenter[0] - globalPixelCenter[0]), (ballonCenter[1] - globalPixelCenter[1])];
        newPixelCenter[0] += offsetBase[0] * scale - offsetBase[0];

        newPixelCenter[1] += offsetBase[1] * scale - offsetBase[1];

    }

    this.setGlobalPixelCenter(newPixelCenter, zoom, options);
}
        ////******* 
                /*
 * Далее по коду скрываем балун, когда точка его привязки выходит за видимую область на карте.
 */       
    myMap.balloon.events
                // При открытии балуна начинаем слушать изменение центра карты
                    .add('open', function (e) {
                         ///// создание смещения метки к низу карты (смещает вниз и по центру)
        
         balloon = e.get('balloon');

                       
                       var isRemoved = false;
                         var mapCenter =myMap.getGlobalPixelCenter();
                                mapBounds = [
                                    [mapCenter[0] - mapSize[0] / 2, mapCenter[1] - mapSize[1] / 2],
                                    [mapCenter[0] + mapSize[0] / 2, mapCenter[1] + mapSize[1] / 2]
                                ],
                                isVisible = isPointInBounds(mapBounds, balloon.getPosition());
                                mov=1;
                            // Проверяем, находится ли балун в видимой области
                            if (!isVisible && !isRemoved) {
                                balloon.close();
                                //openForZoom=null;
                                isRemoved = true;
                                mov=null;
                            } else if (isVisible && isRemoved) {
                                balloon.open();
                                isRemoved = false;
                            }
                        
                        eventsGroup = myMap.action.events.group().add('tick', function (e) {
                            var mapCenter = e.get('tick').globalPixelCenter,
                                mapBounds = [
                                    [mapCenter[0] - mapSize[0] / 2, mapCenter[1] - mapSize[1] / 2],
                                    [mapCenter[0] + mapSize[0] / 2, mapCenter[1] + mapSize[1] / 2]
                                ],
                                isVisible = isPointInBounds(mapBounds, balloon.getPosition());
                                mov=1;
                            // Проверяем, находится ли балун в видимой области
                            if (!isVisible && !isRemoved) {
                                balloon.close();
                               // openForZoom=null;
                                isRemoved = true;
                                mov=null;
                            } else if (isVisible && isRemoved) {
                                balloon.open();
                                isRemoved = false;
                            }
                        });
                        
                        if(balloon.getData().properties.get('myid')){
                        openForZoom=balloon.getData().properties.get('myid');//вот тут я запоминаю ИД открытого балуна
                        
                        openCoord=balloon.getPosition();//запомнил координаты
                        }
                    })
                // При закрытии балуна удаляем слушатели
                    .add('close', function () {
                        eventsGroup.removeAll();
                       // openForZoom=null;
                    });
        function isPointInBounds (mbr, point) {
            return point[0] >= mbr[0][0] && point[0] <= mbr[1][0] && point[1] >= mbr[0][1] && point[1] <= mbr[1][1];
        }
            myMap.zoomRange.get(myMap.getCenter()).then(
            function (range){//функция для поиска существующего зума
                if (myMap.getZoom() > range[1] ) {
                    myMap.setZoom(range[1]); 
                } 
            });
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
            }                    
        }else {
        myMap.balloon.close();
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
         .add('select', function (e) {
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
            
        })
        .add('deselect',function(e){
         myMap.geoObjects.remove(myPl);
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
            myCollection[id_s] =[];  
        }
        myPlacemark[id] = new ymaps.Placemark(coord,
        {   
            myid:id,
            body : 'Загрузка...',
            change:0
        }, 
        {
              balloonPane: 'movableOuters',
              balloonMinWidth:500,
              balloonMinHeight:300,
              balloonAutoPanMargin:[100,250],
              balloonContentBodyLayout:myBalloonContentBodyLayout,
              balloonAutoPan:true,
              preset: setStyle(id_s-1)
        }); 
        myCollection[id_s].push(myPlacemark[id]);
        myPlacemark[id].events.add('balloonopen', loadContentBalloon);
        myPlacemark[id].events.add('balloonclose', function(){open=null;});
  }
function loadContentBalloon(e){
     ///// создание смещения метки к низу карты
    var placemark=e.get('target');
    if(mov!=1){
    ///смещение карти вниз и по центру
    var geo=placemark.geometry.getCoordinates(),
       projection =  myMap.options.get('projection'),
       globalPixel = projection.toGlobalPixels(geo, myMap.getZoom()),
       size = myMap.container.getSize();
       myMap.panTo(projection.fromGlobalPixels([globalPixel[0], (globalPixel[1] - size[1]/2 +10) ], myMap.getZoom()),
                    {delay: 0});
    /////*****
    }
        open=null;bopen=null;
    var name=e.get('target').properties.get('myid');
    open=name;
            var names = [],str='', carr=[];
            var coords=e.get('target').geometry.getCoordinates();
            var oldindex='';
                    if(name!=0){
                              if(e.get('target').properties.get('change')==0){
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
                                            if(ymaps.coordSystem.geo.getDistance(carr[idc],coords)<=1000){
                                            str+=' '+index+'('+Math.round(ymaps.coordSystem.geo.getDistance(carr[idc],coords))+' м.)';
                                                mrt.push(Math.round(ymaps.coordSystem.geo.getDistance(carr[idc],coords)));
                                                g++;
                                            }
                                        } 
                                    }
                                })
                              if(e.get('target').properties.get('change')==0 || e.get('target').properties.get('body')=='Загрузка...'){
                                  console.log('its here');
                                 e.get('target').properties.set('change',1);
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
                                        e.get('target').properties.set('body',strbal);//устанавливаем ввесь контент
                                        e.get('target').properties.set('metro',str);//установили точки метро
            
                                    },'json'); 
                                }
                            }); 
                            }
                    }
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
            body : 'Загрузка...',
            change:0
        }, 
        {
           balloonPane: 'movableOuters',
            balloonMinWidth:500,
            balloonMinHeight:300,
            balloonAutoPanMargin:[100,250],
            balloonContentBodyLayout:myBalloonContentBodyLayout,
            balloonAutoPan:true,
            preset: setStyle(id_s-1)
        }); 
       mySerCollection[id_s].push(mySerPlacemark[id]); 
     mySerPlacemark[id].events.add('balloonopen', loadContentBalloon);
       mySerPlacemark[id].events.add('balloonclose', function(){open=null;});
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
        if(bopen){
           params.push('BO=' + bopen);
        }
           var myurl='http://svadbagolik.cybers.net.ua/?'+encodeURI(params.join('&')); //собственно, сама ссылка в сборе
            $('#link').val(myurl);
        }    
        $('.b_over').click(function(e){
            if (!$(e.target).closest('.ccategory').length == 0){ 
                
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
    //********************
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
                            