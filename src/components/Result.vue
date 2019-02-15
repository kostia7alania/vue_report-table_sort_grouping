<template>
  <div id="results">
    <div class="container-fluid">
      <h1 class="pointer" align="center" v-b-tooltip.hover :title=" searched ? `Click to ${watch_list_show?'hide':'show'} section`:'First you need to click on the Search button!'"
      @click="showTooltipHandler('watch_list_show')" 
      >
        SHIP WATCH LIST
        <b-badge v-if="watch_list.length" variant="success" v-b-tooltip.hover title="Number of records">
          <animated-number :value="watch_list.length" :formatValue="formatNum" :duration="500"/>
        </b-badge>
      </h1>


      <transition name="fade">
        <template v-if="searched && watch_list_show">  
      
          <p v-if="!watch_list.length">No results found.</p>

          <table v-else class="table firsttable">
            <thead>
              <tr class="pointer" @click="trClick($event)">
                <th v-for="(el, k) in firstColumnCols" :key="k" :class="{block: k==0}" :style="k==1?'width:70px':k==1?'width:100px':''">
                  {{el}}
                  <template v-if="watch_list.length && el==orderBy.key.trim()">
                    <img class="arrows" v-if="orderBy.sort" src="../assets/down3.png">
                    <img class="arrows" v-if="orderBy.sort == false" src="../assets/arrow_up.png">
                  </template>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row,key) in watch_list" :key="++key">
                <td v-for="(el, k) in firstColumnCols" :key="k"
                >
                  {{el=='#'?key:row[el]}}
                </td>
              </tr>
            </tbody>
          </table> 
        </template> 
      </transition> 
    </div>

    <div class="container-fluid"> 
      <h1 class="pointer" align="center" v-b-tooltip.hover :title=" searched ? `Click to ${under_perfomance_show?'hide':'show'} section`:'First you need to click on the Search button!'"
      @click="showTooltipHandler('under_perfomance_show')">
      SHIP UNDER PERFORMANCE
        <b-badge v-if="under_perfomance_count.b" variant="info" v-b-tooltip.hover title="Number of ships">
          <animated-number
            :value="under_perfomance_count.b"
            :formatValue="formatNum"
            :duration="500"
          />
        </b-badge>
        <b-badge v-if="under_perfomance_count.a" variant="success" v-b-tooltip.hover title="Number of records">
          <animated-number
            :value="under_perfomance_count.a"
            :formatValue="formatNum"
            :duration="500"
          />
        </b-badge>
      </h1>
    <transition name="fade">
      <template v-if="searched && under_perfomance_show"> 
        <p v-if="!under_perfomance_count.a">No results found.</p> 
        <table v-else class="table">
              <thead>
                <tr><th v-for="(el, k) in secondColumnCols" :key="k">{{el}}</th></tr>
                  <!--<th>#</th><th>IMO_No</th><th>Dets</th><th>Callsign</th><th>ShipName</th><th width="100px">InspDate</th><th>InspPlaceAndAuth</th><th>Company</th><th>ExternalID</th><th>ShipFlag</th><th>ISM DOC issuing RO</th> <! --<th>HUID</th>-->
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
            
        </template>
      </transition>
   
    </div>
  </div>
</template>  

 <script>
export default {
  props: { responce: { type: Object } },
  data() {
    return {
      orderBy: { key: "Det", sort: false }, // true = asc, false - descend
      active: [],
      watch_list_show: true,
      under_perfomance_show: true,
      searched: false,
      firstColumnCols: '#,Det,Name,IMO,Flag,CompanyName,CompanyIMO,ShipStatus'.split(','),
      secondColumnCols: '#,IMO_No,Dets,Callsign,ShipName,InspDate,InspPlaceAndAuth,Company,ExternalID,ShipFlag,ISM DOC issuing RO'.split(',')
    };
  },
  methods: {
    showTooltipHandler(name){
      if(this.searched) this[name] = !this[name];
    },
    formatNum(e) {
      return e.toFixed(0); //this.$nextTick( () => +e.toFixed(0) )
    },
    trClick(e) {
      let key = e.target.innerText.trim();
      if (e.target.innerHTML.search("</th>") > -1) return;
      // all row
      else {
        if (this.orderBy.key == key) {
          //уже нажимали, перекюлчаем режим сортировки;
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
    sortAsc({ obj, key }) {
      return obj.sort((a, b) =>
        a[key] > b[key] ? 1 : a[key] < b[key] ? -1 : 0
      );
    }, // a должно быть равным b}
    sortDesc({ obj, key }) {
      return obj.sort((a, b) =>
        a[key] < b[key] ? 1 : a[key] > b[key] ? -1 : 0
      );
    } // a должно быть равным b}
  },
  watch: {
    //watch_list() { window.watch_list = this.watch_list;  },
    under_perfomance() {
      this.searched = true;
    },
    watch_list_show(){ },
    under_perfomance_show(){ },
  },
  computed: {
    under_perfomance_count() {
      let a = 0;
      let b = 0;
      let u = this.under_perfomance;
      if (!u) return {};
      Object.keys(u).forEach(e => {
        a += Object.keys(u[e]).length;
        b++;
      });
      return { a, b };
    },
    watch_list() {
      let w = "watch_list" in this.responce ? this.responce.watch_list : [];
      let k = this.orderBy.key.trim();
      this.orderBy.sort
        ? this.sortAsc({ obj: w, key: k })
        : this.sortDesc({ obj: w, key: k });
      return w;
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
 #results{
  margin-bottom: 40px;
 }
  .firsttable {
    margin-bottom: 50px;
  }
  td {
    vertical-align: middle !important;
    text-align: center !important;
  }
.pointer {
  cursor: pointer;
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

  .arrows {
    width: 20px;
  }

  .badge {
    transition: 1s;
    vertical-align: top;
  }

  .badge:hover {
    transform: scale(1.1);
  } 




  .fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active до версии 2.1.8 */ {
  opacity: 0;
}
</style>