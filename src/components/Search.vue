<template>
  <div class="container">
    <div class="row">
      <div class="col">
        <label for="sel1">
          <b>From Year: {{year}}</b>
        </label>
        <select @change="parseData" v-model="year" class="form-control" id="sel1">
          <option v-for="y in yearsArr" :key="y" :value="y">{{y}}</option>
        </select>
      </div>
      <div class="col">
        <label for="sel2">
          <b>Month: {{monthComp}}</b>
        </label>
        <select @change="parseData" v-model="month" class="form-control" id="sel3">
          <option v-for="(m,i) in months" :key="m" :value="i">{{m}}</option>
        </select>
      </div>
      <div class="col">
        <label for="sel3">
          <b>Years back: {{back}} ({{year-back}})</b>
        </label>
        <select @change="parseData" v-model="back" class="form-control" id="sel3">
          <option
            v-for="(e,i) in new Array(5).fill(1)"
            :key="++i"
            :value="i"
          >{{i}} year{{i>1?'s':''}}</option>
        </select>
      </div>
      <div class="col">
        <label for="sel4">
          <b>Detentions: {{dets}} {{dets?'or more':''}}</b>
        </label>
        <select @change="parseData" v-model="dets" class="form-control" id="sel4">
          <option v-for="d in detsArr" :key="d">{{d}}</option>
        </select>
      </div>
      <div class="col">
		  
        <button
          v-if="load==0"
          type="button"
		  :class="{block_btn, btn_canceled}"
          class="btn btn-warning "
          @click="parseData"
        >
          <img v-if="isDevMode" src="../assets/google.gif">
          <img v-else :src="props_data.assets_path+'google.gif'">
		  {{search_text}}
        </button>

        <button
          v-else
          type="button"
		  :class="block_btn"
          class="btn btn-danger btn-cancel"
          @click="cancelHandler"
        >
          <!-- <i class="fa fa-spinner fa-spin"></i>-->
          <img v-if="isDevMode" src="../assets/spinner.gif">
          <img v-else :src="props_data.assets_path+'spinner.gif'">
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>  

 <script>
export default {
  props: {
    props_data: {
      type: Object,
      default: () => {
        return {
          api_url: "/api.php?",
          assets_path: process.env.BASE_URL// "../assets/"
        };
      }
    }
  },
  data() {
    return {
      cancel_req_token: null,
      dets: 2,
	  load: 0,
	  cancel_txt: 'Canceled!',
	  search_text: 'Search',
	  btn_canceled:'',
	  block_btn: '',// .block_btn { /*css*/}
      year: new Date().getFullYear(),
      month: new Date().getMonth() + 1,
	  back: 1,
      months: [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
      ]
    };
  },
  mounted() {
	this.month = new Date().getMonth();
  },
  methods: {
	  cancelHandler(){
		  this.cancel_req_token?this.cancel_req_token.cancel('Операция отменена пользователем'):'';
		  this.cancel_req_token = null;
	  },
	changeBtnTextTemp(text) {
		this.block_btn = 'block_btn';
		let old = this.search_text;
		if(old==text) return;
		this.search_text = text;
		 
		setTimeout( () => {
			this.search_text = old;
			this.block_btn = '';
			this.btn_canceled = '';
		}, 1000);
	},
    parseData: function() {
      this.load = 1;
      let axios = this.$http;
      const CancelToken = axios.CancelToken;
      const source = CancelToken.source();
	  this.cancel_req_token?this.cancel_req_token.cancel():'';
	  this.cancel_req_token = source;
      let options = {
        cancelToken: source.token,
        params: {
          Year: this.year,
          Month: this.month + 1,
          back: this.back,
          dets: this.dets
        }
      };
      axios
        .get(this.props_data.api_url, options)
        .then(res => {
          window.otv = res;
          let d = res.data.split("<breaker>");

          let watch_list = JSON.parse(d[0]);

          let otv1 = JSON.parse(
            d[1].replace(/ISM DOC issuing RO/gim, "ISM_DOC_issuing_RO")
          );

          var k = "HUID"; // group by;

          let under_perfomance = otv1.reduce((map, obj) => {
            //if(!obj.selected) {    return map;  }
            let makeCode = (map[obj[k]] = map[obj[k]] || {}); // var modelCode = makeCode[obj.HUID] = makeCode[obj.HUID] || { count: 0 };
            let l = map[obj[k]];
            let m = Object.keys(l).length;
            l[m] = { ...obj };
            return map;
          }, {});
          this.$emit("response", { watch_list, under_perfomance });
		  this.load = 0;
		  this.changeBtnTextTemp('Success!');
        })
        .catch(e => {
          if (axios.isCancel(e)) {
			console.log("[GET] => Request canceled", e, e.message);
			this.btn_canceled = 'btn_canceled';
			this.changeBtnTextTemp(this.cancel_txt);
          } else {
			console.warn("[GET] handle error ->", e);
			this.changeBtnTextTemp('Error!');
          }
          this.load = 0;
        });
    }
  },
  computed: {
	  isDevMode(){
		  return process.env.NODE_ENV === 'development'
	  },
    yearsArr() {
      let d = new Date().getFullYear();
      // return new Array(13).fill(1).map((e, i) => (i == 0 ? "" : d--));//селект с пустой опцией
      return new Array(13).fill(1).map(() => d--);
    },
    detsArr() {
      //   return new Array(12).fill(1).map((e, i) => (i == 0 ? "" : --i)); // ['',0,1...,10];//селект с пустой опцией
      return new Array(11).fill(1).map((e, i) => i); // [0,1...,10];//селект
    },
    monthComp() {
      let M = +this.month; //let arr = this.months.filter((e,ee)=>Object.keys(e)[0] == this.Month); //return arr.length>0?arr[M]:'';
      return this.months[M];
    }
  }
};
</script>
 <style scoped>
img {
  width: 18px;
  vertical-align: text-top;
}
.btn {
  width: 130px;
  margin-top: 30px;
}
.btn-cancel { background: #4caf50; }

.btn_canceled {
	 background: darkred;
	 color: white;
}

.block_btn {
	pointer-events: none;
}
</style>
 
