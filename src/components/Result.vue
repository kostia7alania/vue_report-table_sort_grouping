<template>
  <div>
    <div class="container-fluid">
      <h1 align="center">SHIP WATCH LIST ({{watch_list.length}})</h1>
      <table class="table firsttable">
        <thead>
          <tr class="pointer" @click="trClick($event)">
            <th class="block">#</th>
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
          <tr v-for="(row,key) in watch_list" :key="++key">
            <td>{{key}}</td>
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
 
    <div class="container-fluid">
      <h1 align="center">SHIP UNDER PERFORMANCE ({{under_perfomance_count.b + ', ' + under_perfomance_count.a }})</h1>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>IMO_No</th>
            <th>Dets</th> 
            <th>Callsign</th>
            <th>ShipName</th>
            <th width="100px">InspDate</th>
            <th>InspPlaceAndAuth</th>
            <th>Company</th>
            <th>ExternalID</th>
            <th>ShipFlag</th>
            <th>ISM DOC issuing RO</th>
            <!--<th>HUID</th>-->
          </tr>
        </thead>
        <tbody>
          <template v-for="(row,key, rowNumber) in under_perfomance">
            <tr
              v-for="(row2,key2) in row"
              @mouseover="trMouseOver(row2.IMO_No)"
              @mouseout="active=''"
              :key="key+key2"
            >
              <template v-if="key2==0">
                <td
                  :class="{active: active == row2.IMO_No}"
                  :rowspan="Object.keys(under_perfomance[key]).length"
                >{{++rowNumber}}</td>
                <td
                  :class="{active: active == row2.IMO_No}"
                  :rowspan="Object.keys(under_perfomance[key]).length"
                >{{row2.IMO_No}}</td>
                <td
                  :class="{active: active == row2.IMO_No}"
                  :rowspan="Object.keys(under_perfomance[key]).length"
                >{{row2.Dets}}</td>
              </template>
              <!--<td :rowspan="row.second.length">{{row.first}}</td>-->
              <td>{{row2.Callsign}}</td>
              <td>{{row2.ShipName}}</td>
              <td width="100px">{{row2.InspDate}}</td>
              <td>{{row2.InspPlaceAndAuth}}</td>
              <td>{{row2.Company}}</td>
              <td>{{row2.ExternalID}}</td>
              <td>{{row2.ShipFlag}}</td>
              <td>{{row2.ISM_DOC_issuing_RO}}</td>
              <!--<td>{{row.IMO_No}}</td><td>{{row.Callsign}}</td><td>{{row.Dets}}</td><td>{{row.ShipName}}</td><td>{{row.InspDate}}</td><td>{{row.InspPlaceAndAuth}}</td><td>{{row.Company}}</td><td>{{row.ExternalID}}</td><td>{{row.ShipFlag}}</td><td>{{row.ISM_DOC_issuing_RO}}</td><td>{{row.HUID}}</td>-->
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>
</template>  

 <script>
export default {
  props: { responce: { type: Object } },
  data() {
    return {
      orderBy: { key:"IMO", sort: true },	// true = asc, false - descend
      active: []
    };
  },
  methods: { 
	  trClick(e){ 
		  let key = e.target.innerHTML;
		  if(key.search('</th>')>-1) return; // all row
		  else {
			  if(this.orderBy.key == key){//уже нажимали, перекюлчаем режим сортировки;
				this.orderBy.sort = !this.orderBy.sort;
			  } else {
				  this.orderBy.key = key;
				  this.orderBy.sort = true;
			  }
		  }
	}, 
    trMouseOut(e) {
      //this.active = '';
      //that.target.parentElement.querySelectorAll('td').forEach(el=>el.classList.remove('active') )
      // $event.target.parentElement.classList.remove('active');
    },
    trMouseOver(IMO_No) {
      this.active = IMO_No;
      //that.target.parentElement.querySelectorAll('td').forEach(el=>el.classList.add('active') )
      /*$event.target.parentElement.classList.add('active');
		  if($event.target.previousSibling.attributes) $event.target.previousSibling.classList.add('active'); */
	},
	  sortAsc({obj, key}) { return obj.sort( (a, b) => a[key] > b[key] ? 1 : a[key] < b[key] ? -1 : 0 )},// a должно быть равным b}	
	  sortDesc({obj, key}) { return obj.sort( (a, b) => a[key] < b[key] ? 1 : a[key] > b[key] ? -1 : 0 )},// a должно быть равным b}	
  },
  	watch: {
		//watch_list() { window.watch_list = this.watch_list;  },
		//under_perfomance() { window.under_perfomance = this.under_perfomance;  }
	},
  computed: {
	under_perfomance_count(){
		let a = 0;
		let b = 0;
		let u = this.under_perfomance;
		if(!u)  return {};
		Object.keys(u).forEach(e=>{
			a += Object.keys(u[e]).length
			b++;
			} )
		return {a,b};
  	},
    watch_list() {
		let w = "watch_list" in this.responce ? this.responce.watch_list : [];
		let k    = this.orderBy.key;
		this.orderBy.sort ? 
		this.sortAsc({obj:w, key:k})
		:
		this.sortDesc({obj:w, key:k})
		return w 
    },
    under_perfomance() {
      return "under_perfomance" in this.responce
        ? this.responce.under_perfomance
        : {};
    }
  }
};
</script>
 <style scoped>
  

 .firsttable{
	 margin-bottom: 50px;
 }
td {
  vertical-align: middle !important;
  text-align: center !important;
}

.pointer th:hover {
	cursor: pointer;
	background-color: red !important;
}
.block {
	pointer-events: none;
}

.active,
tr:hover {
  background: #ddd;
}
 
 

@media (hover) {
  td:hover {
    color: #ea4c89;
  }
}
 

.table td,
.table th {
  padding: 0.5rem !important;
  vertical-align: inherit !important;
}
 

th,
td {
  text-align: center !important;  
  border: 1px solid #ddd;
  padding: 8px;
} 

/*  tr:nth-child(even){background-color: #f2f2f2;}*/
  tr:nth-child(even) {
  background-color: #f2f2f2;
}

 tr:hover {
  background-color: #ddd;
}

 th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4caf50;
  color: white;
}



</style>