<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>report - PERFORMANCE</title>
    <link rel="stylesheet" href="style.css"> 
	<link rel="shortcut icon" href="/img/test.jpg" type="image/gif">
	<link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/> 
    
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" crossorigin="anonymous"></script> 
    
 <link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap/dist/css/bootstrap.min.css"/>
<link type="text/css" rel="stylesheet" href="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.css"/>
 
</head>
<body> 
 <div  id="app2">    
	<div id="logo">
        <h5>MEMORANDUM OF UNDERSTANDING ON _____ IN WEST AND CENTRAL AFRICA</h5>
    </div>
	<hr/>
		<div class="container">
					<div class="row"> 
                                
                            <div class="col">
                                <label for="sel1"><b>Year: {{Year}}</b></label>
                                <select @change="YearHandler"  class="form-control" id="sel1">
                                        <option></option>
                                        <option>2018</option><option>2017</option><option>2016</option><option>2015</option><option>2014</option><option>2013</option><option>2012</option><option>2011</option><option>2010</option><option>2009</option><option>2008</option><option>2007</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="sel2"><b>Month: 
								{{	Month==01?'January':
									Month==02?'February':
									Month==03?'March':
									Month==04?'April':
									Month==05?'May':
									Month==06?'June':
									Month==07?'July':
									Month==08?'August':
									Month==09?'September':
									Month==10?'October':
									Month==11?'November':
									Month==12?'December': 
								Month}}</b></label>
                                <select @change="monthHandler"  class="form-control" id="sel3">
                                        <option></option>
										<option value="01">January</option> 
										<option value="02">February</option> 
										<option value="03">March</option> 
										<option value="04">April</option> 
										<option value="05">May</option>
										<option value="06">June</option> 
										<option value="07">July</option> 
										<option value="08">August</option> 
										<option value="09">September</option> 
										<option value="10">October</option> 
										<option value="11">November</option> 
										<option value="12">December</option>
                                </select>
                            </div>
                           <div class="col">
                                <label for="sel3"><b>Years back: {{back}} ({{Year-back}})</b></label>
                                <select @change="backHandler"  class="form-control" id="sel3">
                                        <option></option>
										<option value="1">1 year</option> 
										<option value="2">2 years</option> 
										<option value="3">3 years</option> 
										<option value="4">4 years</option> 
										<option value="5">5 years</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="sel4"><b>Detentions: {{dets}}</b></label>
                                <select @change="detsHandler"  class="form-control" id="sel4">
                                        <option></option>
                                        <option>0</option><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option>
                                </select>
                            </div>
                            <div class="col">
								<button v-if="load==0" style="margin-top:30px" type="button" class="btn btn-warning" @click="parseData">GO!</button>
								<p v-else style="margin-top:30px" type="button" class="btn btn-warning"
									@click="parseData">
									<i class="fa fa-spinner fa-spin"></i>
									Loading...
								</p>
							</div> 
                        </div> 
                    </div>
	 
  <hr/>
 
	<div class="container">
<h1 align="center">SHIP WATCH LIST</h1>
		<table class="table firsttable">
			<thead>
				<tr>
					<th>#</th>
					<th>Det</th>
					<th>Name</th>
					<th>IMO</th>
					<th>Flag</th>
					<th>CompanyName</th>
					<th>CompanyIMO</th>
					<th>ShipStatus</th>
				
				</tr>
			</thead>
			<tbody>
				<tr v-for="(row,key) in otv0" :key="row.CompanyIMO+row.IMO">		
					<td>{{key+1}}</td>	
					<td>{{row.Det}}</td>
					<td>{{row.Name}}</td>			
					<td>{{row.IMO}}</td>
					<td>{{row.Flag}}</td>
					<td>{{row.CompanyName}}</td>
					<td>{{row.CompanyIMO}}</td>
					<td>{{row.ShipStatus}}</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<!-- без группировки :
 <div class="container">
<h1>SHIP UNDER PERFORMANCE</h1>
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>IMO_No</th>
					<th>Callsign</th>
					<th>Dets</th>
					<th>ShipName</th>
					<th>InspDate</th>
					<th>InspPlaceAndAuth</th>
					<th>Company</th>
					<th>ExternalID</th>
					<th>ShipFlag</th>
					<th>ISM DOC issuing RO</th> 
				</tr>
			</thead>
			<tbody>
				<tr v-for="(row,key) in otv1" :key="row.IMO_No+row.InspDate">	
					<td>{{key+1}}</td>					
					<td>{{row.IMO_No}}</td>
					<td>{{row.Callsign}}</td>			
					<td>{{row.Dets}}</td>
					<td>{{row.ShipName}}</td>
					<td>{{row.InspDate}}</td>
					<td>{{row.InspPlaceAndAuth}}</td>
					<td>{{row.Company}}</td>
					<td>{{row.ExternalID}}</td>
					<td>{{row.ShipFlag}}</td>
					<td>{{row.ISM_DOC_issuing_RO}}</td> 
				</tr>
			</tbody>
		</table>
	</div>
	-->
	
	 <div class="container">
<h1 align="center">SHIP UNDER PERFORMANCE</h1>
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th>IMO_No</th>					
					<th>Dets</th>
					
					<th>Callsign</th>
					<th>ShipName</th>
					<th width="120px">InspDate</th>
					<th>InspPlaceAndAuth</th>
					<th>Company</th>
					<th>ExternalID</th>
					<th>ShipFlag</th>
					<th>ISM DOC issuing RO</th>
					<!--<th>HUID</th>-->
				</tr>
			</thead>
			<tbody>
				<template v-for="(row,key) in otv00" >
					<tr>	
						<td :rowspan="row.second.length+1">{{key+1}}</td>
						<td :rowspan="row.second.length+1">{{row.second[0].IMO_No}}</td>
						<td :rowspan="row.second.length+1">{{row.second[0].Dets}}</td>
						<!--<td :rowspan="row.second.length">{{row.first}}</td>-->
					</tr>
					<tr v-for="(row2,key2) in row.second">
						<td>{{row2.Callsign}}</td>
						<td>{{row2.ShipName}}</td>
						<td width="100px">{{row2.InspDate}}</td>
						<td>{{row2.InspPlaceAndAuth}}</td>
						<td>{{row2.Company}}</td>
						<td>{{row2.ExternalID}}</td>
						<td>{{row2.ShipFlag}}</td>
						<td>{{row2.ISM_DOC_issuing_RO}}</td>
						<!--
						<td>{{row.IMO_No}}</td>
						<td>{{row.Callsign}}</td>			
						<td>{{row.Dets}}</td>
						<td>{{row.ShipName}}</td>
						<td>{{row.InspDate}}</td>
						<td>{{row.InspPlaceAndAuth}}</td>
						<td>{{row.Company}}</td>
						<td>{{row.ExternalID}}</td>
						<td>{{row.ShipFlag}}</td>
						<td>{{row.ISM_DOC_issuing_RO}}</td>
						<td>{{row.HUID}}</td>
						-->
					</tr>
				</template>
			</tbody>
		</table>
	</div>
	
	<footer id="footer" class="footer"> 
				  <!--Copyright-->
				  <div class="container footer-copyright text-center">
					  <div class="container-fluid text-muted">
						  © Hosted by Information and Coordinating Center on Port and Flag State Control. Moscow, Russia, 2018</a>
					  </div>
				  </div>  
	</footer>
 
     
 
 </div> 
	
    <script src="https://unpkg.com/vue/dist/vue.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/vue-resource/1.3.6/vue-resource.js"></script>
  <script src="//unpkg.com/babel-polyfill@latest/dist/polyfill.min.js"></script>
<script src="//unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.js"></script>


	<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.10.0/lodash.min.js"></script>
    <!--<script src="https://cdn.jsdelivr.net/npm/vue-lodash@1.0.4/dist/vue-lodash.min.js"></script>-->
	
 <script>
 var w = new Vue ({
    el: '#app2',
    data: { 
	dets: 2,
	load: 0,
	Year: 2018,
	Month: 2, 
	back: 2,
    otv0: [],
	otv1: [],
	otv00: []
    }, 
    methods: {
		parseData: function(){ 
			this.load = 1;
			var options = {
				params: {
					Year: 	  this.Year,
					Month:    this.Month,
					back:     this.back,
					dets:     this.dets
				}
			};
			
			var that = this;
			this.$http.get('./api.php', options)
			.then(function(res){
				 window.otv = res;
				that.otv0 = JSON.parse(res.bodyText.split('<breaker>')[0]); //UPD: 22.2.2019 => :D  it's old code =))
				console.log(that.otv0);
				that.otv1 = JSON.parse(res.bodyText.split('<breaker>')[1].replace(/ISM DOC issuing RO/gim,'ISM_DOC_issuing_RO'));
				console.log(that.otv1);
			}, console.log)
			.then( () => {
				this.load = 0;
				that.otv00 = _.chain(that.otv1)
				.groupBy("HUID")
				.pairs()
				.map(function (currentItem) {
					return _.object(_.zip(["first", "second"], currentItem));
				})
				.value();
				console.log(that.otv00); 
			})
		},
		YearHandler: function(ev){
			this.Year = ev.target.value; //this.parseData();
		},
		monthHandler: function(ev){
			this.Month = ev.target.value; //this.parseData();
		},
		backHandler: function(ev){
			this.back = ev.target.value;
		},
		detsHandler: function(ev){
			this.dets = ev.target.value;
		}
    }
})
</script>

</body>
</html>