<!DOCTYPE html>
<html>
<head>
<title>Observer .DAE</title>

﻿<?php
function getExtension($filename) {
    return substr(strrchr($fileName, '.'), 1);
  }
// Каталог, в который мы будем принимать файл:
$uploaddir = './files/';

$uploadfile = $uploaddir.basename($_FILES['uploadfile']['name']);
if( isset( $_POST['submit'] ) ){

// Копируем файл из каталога для временного хранения файлов:


$array = array('dae', 'DAE');
$name = explode(".", $_FILES['uploadfile']['name']);
$exp = end($name);
 
if(in_array($exp, $array))
{
copy($_FILES['uploadfile']['tmp_name'], $uploadfile);
}else
{
echo "<script>alert('Неверное расширение файла');</script>";
}
}

?>

<script>
function openModel(){
var uploadModel = "<?php echo $_FILES['uploadfile']['name'] ?>";	
secondModel.setName(uploadModel);
addObj(secondModel);
}
</script>

<style>
body {
				font-family: Monospace;
				background-color: #000000;
				margin: 0px;
				overflow: hidden;
			}
	#info {
				color: #fff;
				position: absolute;
				top: 10px;
				width: 100%;
				text-align: center;
				z-index: 100;
				display:block;

			}

		
			a { color: skyblue }
.open_load{
     padding-top: 2px;
     padding-left: 5px;
border:1px dashed #08eb3c;
 border-radius: 9px;
    height: 32px;
    color: #07b3ff;
float: left;
    margin-right: 5px;
    background-color: #555;
}
.Load{
    padding-top: 5px;
     padding-left: 5px;
border:1px dashed #eb8b08;
 border-radius: 9px;
    height: 25px;
     margin-right: 5px;
       background-color: #5a5a5a;
    color: #07b3ff;
float: left;
}
    .open_load:hover{
    background-color: #797979;
     color: #ace4fd;   
    }
    .Load:hover{
    background-color: #797979;
     color: #ace4fd;   
    }
		</style>

		<script src="three.min.js"></script>
		<script src="ColladaLoader.js"></script>
		<script src="Detector.js"></script>
		<script src="stats.min.js"></script>
		<script src="OrbitControls.js"></script>

<script>

function Model(config) {
    this.setName(config.name);
}

Model.prototype = {
    getName: function() {
        return this._name;
    },

    setName: function(name) {
        this._name = name;
    }
};

var firstModel = new Model({
    name: 'start_scene.dae'
});

var secondModel = new Model({
    name: ''
});


function addObj(Model){
 delObj();
/*-----------------------------------------------------------------------------*/
			var loader = new THREE.ColladaLoader();
			loader.options.convertUpAxis = true;
			loader.load(path+Model.getName(), function ( collada ) {
//path+secondModel.getName()
			dae = collada.scene;

				dae.traverse( function ( child ) {

					if ( child instanceof THREE.SkinnedMesh ) {

						var animation = new THREE.Animation( child, child.geometry.animation );
						animation.play();
					}


				} );

				dae.scale.x = dae.scale.y = dae.scale.z =1.0;
				dae.updateMatrix();
scene.add(dae);
console.log("Obj add");
animate();
} );
/*-----------------------------------------------------------------------------*/


}
function delObj(){

var lastIndex = scene.children.length-1; 
endElement = scene.children[lastIndex];
scene.remove(endElement);

animate();
console.log("Obj delete");
}
</script>

<script>

	if ( ! Detector.webgl ) Detector.addGetWebGLMessage();
			var path =  './files/';
			var clock = new THREE.Clock();
			var container;
			var camera, scene, renderer, objects;
			var particleLight;
			var dae;
			var controls;
		
		//Загрузка стартовой модели


			var loader = new THREE.ColladaLoader();
			loader.options.convertUpAxis = true;
			loader.load(path+firstModel.getName(), function ( collada ) {

				dae = collada.scene;

				dae.traverse( function ( child ) {

					if ( child instanceof THREE.SkinnedMesh ) {

						var animation = new THREE.Animation( child, child.geometry.animation );
						animation.play();
					}

				} );

				dae.scale.x = dae.scale.y = dae.scale.z = 0.0001;
				dae.updateMatrix();

			init();	
			animate();
			} );

		

			function init() {

				container = document.createElement( 'div' );
				document.body.appendChild( container );

				camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 1, 2000 );
				camera.position.set( 2, 2, 3 );

				scene = new THREE.Scene();

				// Сетка

				var size = 14, step = 1;

				var geometry = new THREE.Geometry();
				var material = new THREE.LineBasicMaterial( { color: 0x303030 } );

				for ( var i = - size; i <= size; i += step ) {

					geometry.vertices.push( new THREE.Vector3( - size, - 0.04, i ) );
					geometry.vertices.push( new THREE.Vector3(   size, - 0.04, i ) );

					geometry.vertices.push( new THREE.Vector3( i, - 0.04, - size ) );
					geometry.vertices.push( new THREE.Vector3( i, - 0.04,   size ) );

				}

				var line = new THREE.Line( geometry, material, THREE.LinePieces );
				scene.add( line );

				

				particleLight = new THREE.Mesh( new THREE.SphereGeometry( 0.1, 0.1, 0.1), new THREE.MeshBasicMaterial( { color: 0xffffff } ) );
				scene.add( particleLight );

				// Свет

				scene.add( new THREE.AmbientLight( 0xcccccc ) );

				var directionalLight = new THREE.DirectionalLight(/*Math.random() * 0xffffff*/0xeeeeee );
				directionalLight.position.x = Math.random() - 0.5;
				directionalLight.position.y = Math.random() - 0.5;
				directionalLight.position.z = Math.random() - 0.5;
				directionalLight.position.normalize();
				scene.add( directionalLight );

				var pointLight = new THREE.PointLight( 0xffffff, 4 );
				particleLight.add( pointLight );

				renderer = new THREE.WebGLRenderer();
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				container.appendChild( renderer.domElement );

				scene.add( dae );
				
				//Управление камерой

				controls = new THREE.OrbitControls( camera );
				controls.damping = 0.1;
				controls.addEventListener( 'change', render );
				window.addEventListener( 'resize', onWindowResize, false );

				animate();
				

			}

			function onWindowResize() 
			{
				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );
				console.log('Resize()');
			}

			//


			function animate() {
				requestAnimationFrame( animate );
				controls.update();
				render();
			}

			function render() {

				var timer = Date.now() * 0.0005;

				camera.lookAt( scene.position );

				particleLight.position.x = Math.sin( timer * 4 ) * 2009;
				particleLight.position.y = Math.cos( timer * 5 ) * 3000;
				particleLight.position.z = Math.cos( timer * 4 ) * 2009;

				THREE.AnimationHandler.update( clock.getDelta() );

				renderer.render( scene, camera );
		
			}


</script>
</head>

<body>

<form method="POST" enctype=multipart/form-data>

<div class="Load">
      <input type="file" name=uploadfile id="fileInput" >
    </div>

	<input type="submit" name="submit" value="Загрузить модель" class = "open_load" class = "submit_in">
	<input type="button" value="Открыть модель" class = "open_load" onclick="openModel()">
</form>

</body>
</html>