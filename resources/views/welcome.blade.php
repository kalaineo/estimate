<!DOCTYPE html>
<html>
<head>
    
    <title>Quotation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="{{ asset('resources/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- styles -->
    <link href="{{ asset('resources/css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/css/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('resources/css/rzslider.min.css') }}" rel="stylesheet">
</head>
<body>
<div class="header">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <!-- Logo -->
                <div class="logo">
                    <!--<img style="width:50%" height="50px" src="./resources/img/logo2.jpg" /> -->
                  Sample Project
                </div>
            </div>
            <div class="col-md-5">

            </div>
            <div class="col-md-3">
                <div class="navbar navbar-inverse" role="banner">
                    <nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"></span> Welcome <b class="caret"></b></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<div ng-app="myapp" ng-controller="myctrl">
<div class="page-content">
    <div class="row">
        <div class="col-md-2">
            <div class="sidebar content-box" style="display: block;">
                <ul class="nav">
                    <!-- Main menu -->
                    <li class="current"><a><i class="glyphicon pull-right glyphicon-search"></i> Filters</a></li>
                     <li>
                        
                       <a> <i class="glyphicon glyphicon-list"></i> Location</a>
                        <select class = "form-control" ng-change="filterData()" ng-model="location" ng-options="x for x in locationData" >
                        </select>  
                        
                    </li>
                  
                  <li>
                    <a><i class="glyphicon glyphicon-list"></i> Hard Disc Type <br></a>
                      <select class = "form-control" ng-change="filterData()" ng-model="hard_disc_type" ng-options="x for x in hdtypeData">
                        </select>  
                    </li>
                  
                  <li>
                        <a><i class="glyphicon glyphicon-list"></i>Ram Size</a>
                      <!--<label> <button ng-click="checkAll()">All</button><button ng-click="uncheckAll()">None</button>-->
                      <div ng-repeat="ram in ramsizeData">
                      <label> <input type="checkbox" ng-click="filterData()" checklist-model="ramArr" checklist-value="ram"> @{{ram}} GB</label>
                        </div>   
                    </li>
                  
                  <li>
                        <a><i class="glyphicon glyphicon-list"></i>Hard Disc Size (GB)</a>
                    <div>
                    <rzslider class="custom-slider" rz-slider-model="slider.minValue" rz-slider-high="slider.maxValue" rz-slider-options="slider.options"></rzslider>
                    </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-10">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
                <thead>
                <tr>
                    <th>Quote Id</th>
                    <th>Model</th>
                    <th>RAM</th>
                    <th>Hard Disc</th>
                    <th>Location</th>
                    <th class="text-center">Price</th>
                </tr>
                </thead>
                <tbody>
                  <tr class="gradeX" ng-repeat="quote in quotation">
                  <td>@{{quote.quote_id}}</td>
                  <td>@{{quote.model}}</td>
                  <td>@{{quote.ram_size}} @{{quote.ram_size_unit}} @{{quote.ram_type}} </td>
                  <td>@{{quote.hard_disc_size}} @{{quote.hard_disc_unit}} @{{quote.hard_disc_type}} </td>
                  <td>@{{quote.location}}</td>
                  <td>@{{quote.currency}} @{{quote.price}}</td>
                  </tr>
                  
                  <tr ng-hide="quotation.length"><td colspan="6" align="center"> No Data Found</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<footer>
    <div class="container">

        <div class="copy text-center">
            Copyright 2018
        </div>

    </div>
</footer>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="{{ asset('resources/bootstrap/js/jquery-3.1.1.min.js') }}"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{ asset('resources/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('resources/js/custom.js') }}"></script>
<script src="{{ asset('resources/js/jquery-ui.js') }}"></script>
<script src="{{ asset('resources/js/angular.min.js') }}"></script>
<script src="{{ asset('resources/js/rzslider.min.js') }}"></script>
<script src="{{ asset('resources/js/checklist-model.js') }}"></script>

<script type="text/javascript">
    jQuery('#created_at,#updated_at,#from_date, #to_date').datepicker({ dateFormat: 'yy-mm-dd' });
</script>

<script>
var app = angular.module('myapp',['rzModule','checklist-model']);
app.controller('myctrl',function($scope,$http){

	  $http.get('estimate').then(function(response){
          $scope.quotation = response.data.listData;
          $scope.locationData = response.data.locationData;
          $scope.hdtypeData = response.data.hdtypeData;
          $scope.ramsizeData = response.data.ramsizeData;
          $scope.hdsizeData = response.data.hdsizeData;
          $scope.slider = {
                            minValue: 0,
                            maxValue: 3840,
                            options: {
                              floor: 0,
                              ceil: 3840,
                              step: 2,
                              showTicks: true,
                              onChange: $scope.filterData
                            }
                          };
          $scope.ramArr = Object.keys($scope.ramsizeData);
	  });
    //$scope.ramArr = [];
  
    $scope.filterData = function(){
        $scope.msg = "Sending data ...";
      
      
        $http({
          method:'POST',
          url:'estimate',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          data:{
                hard_disc_type:$scope.hard_disc_type,
                location:$scope.location,
                ram_size:$scope.ramArr,
                hard_disc_size_min:$scope.slider.minValue,
                hard_disc_size_max:$scope.slider.maxValue
              },
          transformRequest: function(obj) {
            var str = [];
            for(var p in obj)
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
            return str.join("&");
          }
        }).then(function(response) {
              // on succes
              	$scope.quotation = response.data.filteredData;
        });
	  }
    
    
    $scope.checkAll = function() {
      //$scope.ramArr = $scope.ramsizeData.map(function(item) { return item; });
      $scope.ramArr = Object.keys($scope.ramsizeData);
      $scope.filterData();
    };
    $scope.uncheckAll = function() {
      $scope.ramArr = [];
      $scope.filterData();
	  };
});

</script>
</body>
</html>