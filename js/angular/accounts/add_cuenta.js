angular.module("ggccApp")


.controller("add_cuentaController",['$scope','$http',"CONFIG", function($scope,$http,CONFIG){
	$scope.titulo = 'Listado de Cuentas sin autorizar';
	$scope.sel = 'ver';
	$scope.proveedores = [];
	$scope.conceptos = [];
	//console.log(CONFIG.BASE_URL);
	$scope.listacuentas = {
		cuentas : []
	};
	$scope.ver = function() {
		$scope.titulo = 'Listado de Cuentas sin autorizar';
		$scope.sel = 'ver';
	};

	$scope.agregar = function() {
		$scope.titulo = 'Agregar Cuenta';
		$scope.sel = 'agregar';
		console.log("asdasdasd");
		console.log($scope.proveedores)
		if($scope.proveedores.length == 0){
			$http.get(CONFIG.BASE_URL + "admins/get_proveedores/")
			.success(function(data){
				console.log(data);
				$scope.proveedores = data;
			})
			.error(function(err){
				console.log(err);
			});			
		}

		if($scope.conceptos.length == 0){
			$http.get(CONFIG.BASE_URL + "admins/get_conceptos/")
			.success(function(data){
				console.log(data);
				$scope.conceptos = data;
			})
			.error(function(err){
				console.log(err);
			});			
		}		
	};


	$scope.submit = function(){
		console.log("no hizo submit");

	}


}]);