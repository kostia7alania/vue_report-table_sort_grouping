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